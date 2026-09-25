<?php
declare(strict_types=1);

/**
 * مدیریت دوره‌ها (پکیج‌ها) و دروس ویدیویی.
 * پشتیبانی کامل از آپلود مستقیم فایل یا درج آدرس اینترنتی (URL)
 */
final class AdminCourseController
{
    public static function index(array $params = []): void
    {
        Auth::requireAdmin();

        $search = get_val('q');
        $args   = [];
        $sql    = "SELECT c.*,
                          (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS lesson_count,
                          (SELECT COALESCE(SUM(l.duration_minutes),0) FROM lessons l WHERE l.course_id = c.id) AS total_minutes,
                          (SELECT COUNT(*) FROM course_access ca WHERE ca.course_id = c.id) AS access_count
                   FROM courses c
                   WHERE 1 = 1";

        if ($search !== '') {
            $sql   .= ' AND (c.title LIKE ? OR c.slug LIKE ?)';
            $args[] = '%' . $search . '%';
            $args[] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY c.sort_order ASC, c.id DESC';

        view('admin/courses', [
            'title'   => 'مدیریت دوره‌ها',
            'courses' => Database::selectAll($sql, $args),
            'search'  => $search,
        ], 'layouts/admin');
    }

    public static function create(array $params = []): void
    {
        Auth::requireAdmin();

        view('admin/course-edit', [
            'title'  => 'ساخت دورهٔ جدید',
            'course' => null,
        ], 'layouts/admin');
    }

    public static function store(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $data = self::validatedCourseInput();

        if ($data['error'] !== null) {
            flash('error', $data['error']);
            redirect('/admin/courses/create');
        }

        $slug  = self::uniqueSlug($data['slug'] !== '' ? $data['slug'] : make_slug($data['title']));
        $cover = self::storeCoverImage();

        $courseId = Database::insert(
            'INSERT INTO courses
                (title, slug, short_description, description, price_display,
                 cover_image, level, status, is_featured, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['title'],
                $slug,
                $data['short_description'],
                $data['description'],
                $data['price_display'],
                $cover,
                $data['level'],
                $data['status'],
                $data['is_featured'],
                $data['sort_order'],
            ]
        );

        Auth::log((int) $admin['id'], 'admin_course_create', 'ساخت دوره: ' . $data['title']);
        flash('success', 'دوره ساخته شد. الان درس‌های ویدیویی را اضافه کنید.');
        redirect('/admin/courses/' . $courseId . '/lessons');
    }

    public static function edit(array $params = []): void
    {
        Auth::requireAdmin();

        $course = self::findCourse((int) ($params['id'] ?? 0));

        view('admin/course-edit', [
            'title'  => 'ویرایش دوره: ' . $course['title'],
            'course' => $course,
        ], 'layouts/admin');
    }

    public static function update(array $params = []): void
    {
        $admin  = Auth::requireAdmin();
        csrf_verify();

        $course = self::findCourse((int) ($params['id'] ?? 0));
        $id     = (int) $course['id'];
        $data   = self::validatedCourseInput();

        if ($data['error'] !== null) {
            flash('error', $data['error']);
            redirect('/admin/courses/' . $id . '/edit');
        }

        $slug = $data['slug'] !== '' ? $data['slug'] : make_slug($data['title']);
        $slug = self::uniqueSlug($slug, $id);

        $cover = self::storeCoverImage();
        if ($cover === null) {
            $cover = post_val('remove_cover') === '1' ? null : $course['cover_image'];
        }

        Database::execute(
            'UPDATE courses SET
                title = ?, slug = ?, short_description = ?, description = ?,
                price_display = ?, cover_image = ?, level = ?, status = ?,
                is_featured = ?, sort_order = ?
             WHERE id = ?',
            [
                $data['title'],
                $slug,
                $data['short_description'],
                $data['description'],
                $data['price_display'],
                $cover,
                $data['level'],
                $data['status'],
                $data['is_featured'],
                $data['sort_order'],
                $id,
            ]
        );

        Auth::log((int) $admin['id'], 'admin_course_update', 'ویرایش دوره: ' . $data['title']);
        flash('success', 'تغییرات دوره ذخیره شد.');
        redirect('/admin/courses/' . $id . '/edit');
    }

    public static function destroy(array $params = []): void
    {
        $admin  = Auth::requireAdmin();
        csrf_verify();

        $course = self::findCourse((int) ($params['id'] ?? 0));

        $lessons = Database::selectAll(
            'SELECT video_file FROM lessons WHERE course_id = ?',
            [(int) $course['id']]
        );

        foreach ($lessons as $lesson) {
            self::deleteVideoFile((string) $lesson['video_file']);
        }

        self::deleteCoverFile($course['cover_image']);

        Database::execute('DELETE FROM courses WHERE id = ?', [(int) $course['id']]);

        Auth::log((int) $admin['id'], 'admin_course_delete', 'حذف دوره: ' . $course['title']);
        flash('success', 'دوره، دروس و دسترسی‌های مربوط به آن حذف شد.');
        redirect('/admin/courses');
    }

    /** ---------- دروس ---------- */

    public static function lessons(array $params = []): void
    {
        Auth::requireAdmin();

        $course = self::findCourse((int) ($params['id'] ?? 0));

        $lessons = Database::selectAll(
            'SELECT * FROM lessons WHERE course_id = ? ORDER BY sort_order ASC, id ASC',
            [(int) $course['id']]
        );

        view('admin/lessons', [
            'title'   => 'دروس دوره: ' . $course['title'],
            'course'  => $course,
            'lessons' => $lessons,
        ], 'layouts/admin');
    }

    public static function storeLesson(array $params = []): void
    {
        $admin  = Auth::requireAdmin();
        csrf_verify();

        $course = self::findCourse((int) ($params['id'] ?? 0));
        $id     = (int) $course['id'];

        $title       = mb_substr(post_val('title'), 0, 180);
        $description = post_val('description');
        $duration    = max(0, min(65535, (int) post_val('duration_minutes', '0')));
        $sortOrder   = (int) post_val('sort_order', '0');
        $freePreview = post_val('is_free_preview') === '1' ? 1 : 0;

        if ($title === '') {
            flash('error', 'عنوان درس الزامی است.');
            redirect('/admin/courses/' . $id . '/lessons');
        }

        // روش‌ها: آپلود فایل، وارد کردن آدرس اینترنتی (URL) یا نام فایل در storage/videos
        $videoFile = self::storeVideoFile();

        if ($videoFile === null) {
            $rawVideo = trim(post_val('video_file'));
            if ($rawVideo !== '') {
                $videoFile = is_url($rawVideo) ? $rawVideo : basename($rawVideo);
            }
        }

        if ($videoFile === null) {
            flash('error', 'یا فایل ویدیو را آپلود کنید یا آدرس اینترنتی / نام فایل را وارد نمایید.');
            redirect('/admin/courses/' . $id . '/lessons');
        }

        if ($sortOrder === 0) {
            $sortOrder = 1 + (int) Database::scalar(
                'SELECT COALESCE(MAX(sort_order), 0) FROM lessons WHERE course_id = ?',
                [$id]
            );
        }

        Database::insert(
            'INSERT INTO lessons
                (course_id, title, description, video_file, duration_minutes, sort_order, is_free_preview)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $id,
                $title,
                $description !== '' ? $description : null,
                $videoFile,
                $duration,
                $sortOrder,
                $freePreview,
            ]
        );

        Auth::log((int) $admin['id'], 'admin_lesson_create', 'افزودن درس به دوره: ' . $course['title']);
        flash('success', 'درس جدید افزوده شد.');
        redirect('/admin/courses/' . $id . '/lessons');
    }

    public static function updateLesson(array $params = []): void
    {
        $admin  = Auth::requireAdmin();
        csrf_verify();

        $lesson = self::findLesson((int) ($params['id'] ?? 0));

        $title       = mb_substr(post_val('title', (string) $lesson['title']), 0, 180);
        $description = post_val('description');
        $duration    = max(0, min(65535, (int) post_val('duration_minutes', '0')));
        $sortOrder   = (int) post_val('sort_order', (string) $lesson['sort_order']);
        $freePreview = post_val('is_free_preview') === '1' ? 1 : 0;

        if ($title === '') {
            flash('error', 'عنوان درس الزامی است.');
            redirect('/admin/courses/' . (int) $lesson['course_id'] . '/lessons');
        }

        $newVideo = self::storeVideoFile();
        if ($newVideo === null) {
            $rawVideo = trim(post_val('video_file'));
            if ($rawVideo !== '') {
                $newVideo = is_url($rawVideo) ? $rawVideo : basename($rawVideo);
            } else {
                $newVideo = (string) $lesson['video_file'];
            }
        } else {
            self::deleteVideoFile((string) $lesson['video_file']);
        }

        Database::execute(
            'UPDATE lessons SET
                title = ?, description = ?, video_file = ?, duration_minutes = ?,
                sort_order = ?, is_free_preview = ?
             WHERE id = ?',
            [
                $title,
                $description !== '' ? $description : null,
                $newVideo,
                $duration,
                $sortOrder,
                $freePreview,
                (int) $lesson['id'],
            ]
        );

        Auth::log((int) $admin['id'], 'admin_lesson_update', 'ویرایش درس: ' . $title);
        flash('success', 'درس بروز شد.');
        redirect('/admin/courses/' . (int) $lesson['course_id'] . '/lessons');
    }

    public static function destroyLesson(array $params = []): void
    {
        $admin  = Auth::requireAdmin();
        csrf_verify();

        $lesson   = self::findLesson((int) ($params['id'] ?? 0));
        $courseId = (int) $lesson['course_id'];

        if (post_val('delete_file') === '1') {
            self::deleteVideoFile((string) $lesson['video_file']);
        }

        Database::execute('DELETE FROM lessons WHERE id = ?', [(int) $lesson['id']]);

        Auth::log((int) $admin['id'], 'admin_lesson_delete', 'حذف درس: ' . $lesson['title']);
        flash('success', 'درس حذف شد.');
        redirect('/admin/courses/' . $courseId . '/lessons');
    }

    /** ---------- اعتبارسنجی و کمکی‌ها ---------- */

    private static function validatedCourseInput(): array
    {
        $title = mb_substr(post_val('title'), 0, 180);
        $slug  = make_slug(post_val('slug'));
        $price = preg_replace('/[^0-9]/', '', post_val('price_display')) ?? '';

        $level  = post_val('level');
        $status = post_val('status');

        return [
            'error'             => $title === '' ? 'عنوان دوره الزامی است.' : null,
            'title'             => $title,
            'slug'              => post_val('slug') === '' ? '' : $slug,
            'short_description' => mb_substr(post_val('short_description'), 0, 300) ?: null,
            'description'       => post_val('description') !== '' ? post_val('description') : null,
            'price_display'     => $price === '' ? 0 : (int) $price,
            'level'             => in_array($level, ['beginner', 'intermediate', 'advanced'], true) ? $level : 'beginner',
            'status'            => in_array($status, ['draft', 'published', 'archived'], true) ? $status : 'draft',
            'is_featured'       => post_val('is_featured') === '1' ? 1 : 0,
            'sort_order'        => (int) post_val('sort_order', '0'),
        ];
    }

    private static function uniqueSlug(string $slug, int $ignoreId = 0): string
    {
        $base    = $slug !== '' ? $slug : 'course';
        $current = $base;
        $suffix  = 2;

        while (Database::scalar(
            'SELECT id FROM courses WHERE slug = ? AND id <> ? LIMIT 1',
            [$current, $ignoreId]
        ) !== null) {
            $current = $base . '-' . $suffix;
            $suffix++;
        }

        return $current;
    }

    private static function findCourse(int $id): array
    {
        $course = $id > 0
            ? Database::selectOne('SELECT * FROM courses WHERE id = ? LIMIT 1', [$id])
            : null;

        if ($course === null) {
            abort(404, 'دورهٔ مورد نطر یافت نشد.');
        }

        return $course;
    }

    private static function findLesson(int $id): array
    {
        $lesson = $id > 0
            ? Database::selectOne('SELECT * FROM lessons WHERE id = ? LIMIT 1', [$id])
            : null;

        if ($lesson === null) {
            abort(404, 'درس مورد نطر یافت نشد.');
        }

        return $lesson;
    }

    /** آپلود تصویر دوره یا استفاده از آدرس اینترنتی */
    private static function storeCoverImage(): ?string
    {
        $coverUrl = trim(post_val('cover_url'));
        if ($coverUrl !== '' && is_url($coverUrl)) {
            return $coverUrl;
        }

        $file = $_FILES['cover_image'] ?? null;

        if (!is_array($file) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file((string) $file['tmp_name'])) {
            flash('error', 'آپلود تصویر ناموفق بود.');
            return null;
        }

        $maxBytes = (int) Config::get('upload.image_max_bytes', 0);
        if ($maxBytes > 0 && (int) $file['size'] > $maxBytes) {
            flash('error', 'حجم تصویر بیش از حد مجاز است.');
            return null;
        }

        $mime  = self::detectMime((string) $file['tmp_name']);
        $allow = (array) Config::get('upload.image_mimes', []);

        if (!in_array($mime, $allow, true)) {
            flash('error', 'فقط تصویر JPG، PNG یا WebP قابل آپلود است.');
            return null;
        }

        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $name       = 'course-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$mime];
        $directory  = rtrim((string) Config::get('paths.uploads'), '/');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            flash('error', 'پوشهٔ آپلود قابل ساخت نیست.');
            return null;
        }

        if (!move_uploaded_file((string) $file['tmp_name'], $directory . '/' . $name)) {
            flash('error', 'ذخیرهٔ تصویر ناموفق بود. دسترسی نوشتن پوشه را بررسی کنید.');
            return null;
        }

        return $name;
    }

    /** آپلود ویدیو در storage/videos */
    private static function storeVideoFile(): ?string
    {
        $file = $_FILES['video'] ?? null;

        if (!is_array($file) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file((string) $file['tmp_name'])) {
            $err = (int) $file['error'];
            if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                flash('error', 'حجم فایل بیشتر از سقف مجاز سرور است. از لینک مستقیم یا FTP استفاده کنید.');
            } else {
                flash('error', 'آپلود ویدیو ناموفق بود.');
            }
            return null;
        }

        @set_time_limit(0);
        $maxBytes = (int) Config::get('upload.video_max_bytes', 0);
        if ($maxBytes > 0 && (int) $file['size'] > $maxBytes) {
            flash('error', 'حجم ویدیو بیش از حد مجاز است.');
            return null;
        }

        $mime  = self::detectMime((string) $file['tmp_name']);
        $allow = (array) Config::get('upload.video_mimes', []);

        if (!in_array($mime, $allow, true)) {
            flash('error', 'فقط فرمت‌های MP4، WebM یا MOV پذیرفته می‌شوند.');
            return null;
        }

        $extensions = ['video/mp4' => 'mp4', 'video/webm' => 'webm', 'video/quicktime' => 'mov'];
        $name       = 'lesson-' . date('Ymd-His') . '-' . bin2hex(random_bytes(5)) . '.' . $extensions[$mime];
        $directory  = rtrim((string) Config::get('paths.videos'), '/');

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            flash('error', 'پوشهٔ storage/videos قابل ساخت نیست.');
            return null;
        }

        if (!move_uploaded_file((string) $file['tmp_name'], $directory . '/' . $name)) {
            flash('error', 'ذخیرهٔ ویدیو ناموفق بود.');
            return null;
        }

        return $name;
    }

    private static function detectMime(string $path): string
    {
        if (!function_exists('finfo_open')) {
            return (string) mime_content_type($path);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return '';
        }

        $mime = (string) finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mime;
    }

    private static function deleteVideoFile(?string $fileName): void
    {
        if ($fileName === null || is_url((string) $fileName)) {
            return;
        }

        $name = basename((string) $fileName);
        if ($name === '') {
            return;
        }

        $path = rtrim((string) Config::get('paths.videos'), '/') . '/' . $name;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private static function deleteCoverFile(?string $fileName): void
    {
        if ($fileName === null || is_url((string) $fileName)) {
            return;
        }

        $name = basename((string) $fileName);
        if ($name === '') {
            return;
        }

        $path = rtrim((string) Config::get('paths.uploads'), '/') . '/' . $name;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}