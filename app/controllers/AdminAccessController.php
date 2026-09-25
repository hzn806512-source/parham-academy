<?php
declare(strict_types=1);

/**
 * قلب سیستم: مدیریت دستی دسترسی کاربران به دوره‌ها.
 * هیچ پرداختی وجود ندارد؛ مدیر دستی دسترسی می‌دهد، تاریخ می‌گذارد یا نامحدود می‌کند.
 * همهٔ تاریخ‌ها قبل از ذخیره اعتبارسنجی می‌شوند و مقدار نامعتبر پذیرفته نمی‌شود.
 */
final class AdminAccessController
{
    public static function index(array $params = []): void
    {
        Auth::requireAdmin();

        $search   = get_val('q');
        $courseId = (int) get_val('course', '0');
        $state    = get_val('state');

        $where = ['1 = 1'];
        $args  = [];

        if ($search !== '') {
            $where[] = '(u.username LIKE ? OR u.full_name LIKE ?)';
            $args[]  = '%' . $search . '%';
            $args[]  = '%' . $search . '%';
        }

        if ($courseId > 0) {
            $where[] = 'ca.course_id = ?';
            $args[]  = $courseId;
        }

        $rows = Database::selectAll(
            'SELECT ca.*, u.username, u.full_name, c.title AS course_title, c.slug AS course_slug
             FROM course_access ca
             JOIN users u   ON u.id = ca.user_id
             JOIN courses c ON c.id = ca.course_id
             WHERE ' . implode(' AND ', $where) . '
             ORDER BY ca.updated_at DESC, ca.id DESC',
            $args
        );

        // وضعیت لحطه‌ای هر ردیف با همان موتور مرکزی Access محاسبه می‌شود
        foreach ($rows as &$row) {
            $row['state']          = Access::stateOf($row);
            $row['remaining_days'] = Access::remainingDays($row);
        }
        unset($row);

        if (in_array($state, ['active', 'expired', 'pending', 'revoked'], true)) {
            $rows = array_values(array_filter($rows, static fn ($r) => $r['state'] === $state));
        }

        view('admin/access', [
            'title'    => 'مدیریت دسترسی‌ها',
            'rows'     => $rows,
            'users'    => Database::selectAll(
                "SELECT id, username, full_name FROM users WHERE role = 'user' ORDER BY username ASC"
            ),
            'courses'  => Database::selectAll(
                'SELECT id, title, status FROM courses ORDER BY sort_order ASC, id DESC'
            ),
            'search'   => $search,
            'courseId' => $courseId,
            'state'    => $state,
        ], 'layouts/admin');
    }

    /** دادن یا بروزرسانی دسترسی — هر کاربر/دوره فقط یک ردیف دارد */
    public static function grant(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $userId      = (int) post_val('user_id', '0');
        $courseId    = (int) post_val('course_id', '0');
        $isUnlimited = post_val('is_unlimited') === '1' ? 1 : 0;
        $note        = mb_substr(post_val('note'), 0, 255);

        $user   = Database::selectOne("SELECT id, username FROM users WHERE id = ? AND role = 'user' LIMIT 1", [$userId]);
        $course = Database::selectOne('SELECT id, title FROM courses WHERE id = ? LIMIT 1', [$courseId]);

        if ($user === null || $course === null) {
            flash('error', 'کاربر یا دورهٔ انتخابی معتبر نیست.');
            redirect('/admin/access');
        }

        $startDate = self::parseDate(post_val('start_date'));
        $endDate   = self::parseDate(post_val('end_date'));

        if (post_val('start_date') !== '' && $startDate === null) {
            flash('error', 'تاریخ شروع معتبر نیست. قالب صحیح: YYYY-MM-DD');
            redirect('/admin/access');
        }

        if (post_val('end_date') !== '' && $endDate === null) {
            flash('error', 'تاریخ پایان معتبر نیست. قالب صحیح: YYYY-MM-DD');
            redirect('/admin/access');
        }

        if ($isUnlimited === 1) {
            $endDate = null;   // دسترسی نامحدود تاریخ پایان ندارد
        }

        if ($isUnlimited === 0 && $startDate !== null && $endDate !== null && $endDate < $startDate) {
            flash('error', 'تاریخ پایان نمی‌تواند پیش از تاریخ شروع باشد.');
            redirect('/admin/access');
        }

        // روزشمار سریع: مدیر می‌تواند جای تاریخ پایان، تعداد روز بدهد
        $days = (int) post_val('days', '0');
        if ($isUnlimited === 0 && $endDate === null && $days > 0) {
            $base    = new DateTimeImmutable($startDate ?? date('Y-m-d'));
            $endDate = $base->modify('+' . min($days, 3650) . ' days')->format('Y-m-d');
        }

        Database::execute(
            'INSERT INTO course_access
                (user_id, course_id, start_date, end_date, is_unlimited, is_active, note, granted_by)
             VALUES (?, ?, ?, ?, ?, 1, ?, ?)
             ON DUPLICATE KEY UPDATE
                start_date   = VALUES(start_date),
                end_date     = VALUES(end_date),
                is_unlimited = VALUES(is_unlimited),
                is_active    = 1,
                note         = VALUES(note),
                granted_by   = VALUES(granted_by)',
            [
                (int) $user['id'],
                (int) $course['id'],
                $startDate,
                $endDate,
                $isUnlimited,
                $note !== '' ? $note : null,
                (int) $admin['id'],
            ]
        );

        Auth::log(
            (int) $admin['id'],
            'admin_access_grant',
            'دسترسی برای ' . $user['username'] . ' به دورهٔ ' . $course['title']
        );

        flash(
            'success',
            'دسترسی کاربر «' . $user['username'] . '» به دورهٔ «' . $course['title'] . '» '
            . ($isUnlimited === 1 ? 'به‌صورت نامحدود ' : '') . 'فعال شد.'
        );
        redirect('/admin/access');
    }

    /** تغییر وضعیت نامحدود بودن دسترسی */
    public static function toggleUnlimited(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $row = self::findAccess((int) ($params['id'] ?? 0));
        $new = (int) $row['is_unlimited'] === 1 ? 0 : 1;

        if ($new === 1) {
            Database::execute(
                'UPDATE course_access SET is_unlimited = 1, end_date = NULL WHERE id = ?',
                [(int) $row['id']]
            );
        } else {
            // بازگشت از نامحدود به محدود: پیش‌فرض ۳۰ روز از امروز
            Database::execute(
                'UPDATE course_access
                 SET is_unlimited = 0,
                     start_date = COALESCE(start_date, CURDATE()),
                     end_date = DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                 WHERE id = ?',
                [(int) $row['id']]
            );
        }

        Auth::log((int) $admin['id'], 'admin_access_unlimited', 'تغییر نامحدودی دسترسی #' . (int) $row['id']);
        flash('success', $new === 1
            ? 'دسترسی به حالت نامحدود تغییر کرد.'
            : 'دسترسی محدود شد و تاریخ پایان ۳۰ روز بعد تنطیم شد. در صورت نیاز ویرایش کنید.');
        redirect('/admin/access');
    }

    /** قطع/وصل دستی دسترسی */
    public static function toggleActive(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $row = self::findAccess((int) ($params['id'] ?? 0));
        $new = (int) $row['is_active'] === 1 ? 0 : 1;

        Database::execute('UPDATE course_access SET is_active = ? WHERE id = ?', [$new, (int) $row['id']]);

        Auth::log(
            (int) $admin['id'],
            $new === 1 ? 'admin_access_enable' : 'admin_access_disable',
            ($new === 1 ? 'وصل' : 'قطع') . ' دسترسی کاربر ' . $row['username'] . ' از دورهٔ ' . $row['course_title']
        );

        flash('success', $new === 1
            ? 'دسترسی دوباره فعال شد.'
            : 'دسترسی فوراً قطع شد؛ پخش ویدیو هم همان لحطه متوقف می‌شود.');
        redirect('/admin/access');
    }

    /** تمدید سریع بر حسب روز */
    public static function extend(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $row  = self::findAccess((int) ($params['id'] ?? 0));
        $days = (int) post_val('days', '30');

        if ($days < 1 || $days > 3650) {
            flash('error', 'تعداد روز تمدید باید بین ۱ تا ۳۶۵۰ باشد.');
            redirect('/admin/access');
        }

        // مبنای تمدید: تاریخ پایان فعلی اگر منقضی نشده، وگرنه امروز
        $today = date('Y-m-d');
        $base  = (!empty($row['end_date']) && $row['end_date'] >= $today)
            ? (string) $row['end_date']
            : $today;

        $newEnd = (new DateTimeImmutable($base))->modify('+' . $days . ' days')->format('Y-m-d');

        Database::execute(
            'UPDATE course_access
             SET end_date = ?, is_unlimited = 0, is_active = 1,
                 start_date = COALESCE(start_date, CURDATE())
             WHERE id = ?',
            [$newEnd, (int) $row['id']]
        );

        Auth::log((int) $admin['id'], 'admin_access_extend', 'تمدید ' . $days . ' روزه برای ' . $row['username']);
        flash('success', 'دسترسی تا تاریخ ' . jalali_date($newEnd) . ' تمدید شد.');
        redirect('/admin/access');
    }

    public static function destroy(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $row = self::findAccess((int) ($params['id'] ?? 0));

        Database::execute('DELETE FROM course_access WHERE id = ?', [(int) $row['id']]);

        Auth::log(
            (int) $admin['id'],
            'admin_access_delete',
            'حذف دسترسی ' . $row['username'] . ' از دورهٔ ' . $row['course_title']
        );

        flash('success', 'رکورد دسترسی حذف شد.');
        redirect('/admin/access');
    }

    /** ---------- کمکی‌ها ---------- */

    private static function findAccess(int $id): array
    {
        $row = $id > 0
            ? Database::selectOne(
                'SELECT ca.*, u.username, c.title AS course_title
                 FROM course_access ca
                 JOIN users u   ON u.id = ca.user_id
                 JOIN courses c ON c.id = ca.course_id
                 WHERE ca.id = ? LIMIT 1',
                [$id]
            )
            : null;

        if ($row === null) {
            abort(404, 'رکورد دسترسی یافت نشد.');
        }

        return $row;
    }

    /** اعتبارسنجی دقیق تاریخ میلادی ورودی فرم */
    private static function parseDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if ($date === false || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}