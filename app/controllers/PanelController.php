<?php
declare(strict_types=1);

final class PanelController
{
    /** داشبورد: نام کاربری، دوره‌های فعال و اطلاعات انقضا */
    public static function dashboard(array $params = []): void
    {
        $user    = Auth::requireUser();
        $courses = Access::coursesOf((int) $user['id']);

        $summary = [
            'total'   => count($courses),
            'active'  => count(array_filter($courses, static fn ($c) => $c['state'] === 'active')),
            'expired' => count(array_filter($courses, static fn ($c) => in_array($c['state'], ['expired', 'revoked'], true))),
        ];

        view('panel/dashboard', [
            'title'   => 'پنل کاربری',
            'user'    => $user,
            'courses' => $courses,
            'summary' => $summary,
        ], 'layouts/panel');
    }

    /** پروفایل و تغییر رمز */
    public static function profile(array $params = []): void
    {
        $user = Auth::requireUser();

        view('panel/profile', [
            'title' => 'پروفایل من',
            'user'  => $user,
        ], 'layouts/panel');
    }

    public static function updateProfile(array $params = []): void
    {
        $user = Auth::requireUser();
        csrf_verify();

        $fullName = mb_substr(post_val('full_name'), 0, 120);

        Database::execute('UPDATE users SET full_name = ? WHERE id = ?', [
            $fullName !== '' ? $fullName : null,
            (int) $user['id'],
        ]);

        flash('success', 'اطلاعات پروفایل به‌روز شد.');
        redirect('/panel/profile');
    }

    /** تغییر رمز: رمز فعلی + رمز جدید + تکرار */
    public static function changePassword(array $params = []): void
    {
        $user = Auth::requireUser();
        csrf_verify();

        $current = (string) ($_POST['current_password'] ?? '');
        $new     = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['new_password_confirm'] ?? '');

        // بررسی رمز فعلی — الزامی
        $hash  = (string) $user['password_hash'];
        $valid = str_starts_with($hash, 'PLAINTEXT:')
            ? hash_equals(substr($hash, 10), $current)
            : password_verify($current, $hash);

        if (!$valid) {
            flash('error', 'رمز عبور فعلی نادرست است.');
            redirect('/panel/profile');
        }

        $error = Auth::validatePassword($new)
            ?? ($new !== $confirm ? 'رمز جدید و تکرار آن یکسان نیستند.' : null)
            ?? ($new === $current ? 'رمز جدید باید با رمز قبلی متفاوت باشد.' : null);

        if ($error !== null) {
            flash('error', $error);
            redirect('/panel/profile');
        }

        Auth::setPassword((int) $user['id'], $new);
        Auth::log((int) $user['id'], 'password_change', 'تغییر رمز عبور توسط کاربر');
        Session::regenerate();

        flash('success', 'رمز عبور شما با موفقیت تغییر کرد.');
        redirect('/panel/profile');
    }

    /** صفحهٔ پخش درس — بدون چاپ آدرس در سورس HTML */
    public static function watch(array $params = []): void
    {
        $user     = Auth::requireUser();
        $lessonId = (int) ($params['lesson'] ?? 0);

        $lesson = Database::selectOne(
            'SELECT l.*, c.title AS course_title, c.slug AS course_slug, c.id AS course_id
             FROM lessons l
             JOIN courses c ON c.id = l.course_id
             WHERE l.id = ? LIMIT 1',
            [$lessonId]
        );

        if ($lesson === null) {
            abort(404, 'درس مورد نطر یافت نشد.');
        }

        $access = Access::status($user, (int) $lesson['course_id']);
        $allowed = $access['granted'] || (int) $lesson['is_free_preview'] === 1;

        if (!$allowed) {
            view('panel/denied', [
                'title'  => 'دسترسی غیرفعال',
                'lesson' => $lesson,
                'access' => $access,
            ], 'layouts/panel');
            return;
        }

        $playlist = Database::selectAll(
            'SELECT id, title, duration_minutes, is_free_preview
             FROM lessons WHERE course_id = ? ORDER BY sort_order ASC, id ASC',
            [(int) $lesson['course_id']]
        );

        view('panel/watch', [
            'title'    => $lesson['title'],
            'lesson'   => $lesson,
            'playlist' => $playlist,
            'access'   => $access,
        ], 'layouts/panel');
    }

    /** دریافت امن و پویا آدرس استریم ویدیو از طریق ایجکس (بدون نمایش در Ctrl+U) */
    public static function streamTicket(array $params = []): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $user = Auth::user();
        if ($user === null) {
            http_response_code(401);
            echo json_encode(['error' => 'unauthorized'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $lessonId = (int) ($params['lesson'] ?? 0);
        $lesson = Database::selectOne(
            'SELECT id, course_id, is_free_preview FROM lessons WHERE id = ? LIMIT 1',
            [$lessonId]
        );

        if ($lesson === null) {
            http_response_code(404);
            echo json_encode(['error' => 'not_found'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!Access::canWatch($user, (int) $lesson['course_id'], (int) $lesson['is_free_preview'] === 1)) {
            http_response_code(403);
            echo json_encode(['error' => 'forbidden'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // ساخت توکن موقت و ارسال آدرس پخش
        $token = VideoToken::make((int) $user['id'], (int) $lesson['id']);
        $streamUrl = url('/media/lesson/' . (int) $lesson['id']) . '?t=' . $token;

        echo json_encode([
            'ok'  => true,
            'src' => $streamUrl,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}