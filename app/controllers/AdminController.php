<?php
declare(strict_types=1);

/**
 * داشبورد مدیر، تنطیمات حساب مدیر و راهنمای داخلی پنل.
 * تغییر نام کاربری یا رمز فقط با تایید رمز فعلی مجاز است.
 */
final class AdminController
{
    public static function dashboard(array $params = []): void
    {
        $admin = Auth::requireAdmin();

        $activeCondition =
            "ca.is_active = 1 AND (
                 ca.is_unlimited = 1
                 OR (
                     (ca.start_date IS NULL OR ca.start_date <= CURDATE())
                     AND (ca.end_date IS NULL OR ca.end_date >= CURDATE())
                 )
             )";

        $stats = [
            'users'         => (int) Database::scalar("SELECT COUNT(*) FROM users WHERE role = 'user'"),
            'admins'        => (int) Database::scalar("SELECT COUNT(*) FROM users WHERE role = 'admin'"),
            'inactiveUsers' => (int) Database::scalar('SELECT COUNT(*) FROM users WHERE is_active = 0'),
            'courses'       => (int) Database::scalar('SELECT COUNT(*) FROM courses'),
            'published'     => (int) Database::scalar("SELECT COUNT(*) FROM courses WHERE status = 'published'"),
            'lessons'       => (int) Database::scalar('SELECT COUNT(*) FROM lessons'),
            'minutes'       => (int) Database::scalar('SELECT COALESCE(SUM(duration_minutes), 0) FROM lessons'),
            'accessTotal'   => (int) Database::scalar('SELECT COUNT(*) FROM course_access'),
            'accessActive'  => (int) Database::scalar("SELECT COUNT(*) FROM course_access ca WHERE {$activeCondition}"),
            'accessExpired' => (int) Database::scalar(
                'SELECT COUNT(*) FROM course_access ca
                 WHERE ca.is_unlimited = 0
                   AND ca.end_date IS NOT NULL
                   AND ca.end_date < CURDATE()'
            ),
            'accessRevoked' => (int) Database::scalar('SELECT COUNT(*) FROM course_access WHERE is_active = 0'),
            'unlimited'     => (int) Database::scalar('SELECT COUNT(*) FROM course_access WHERE is_unlimited = 1 AND is_active = 1'),
        ];

        // دسترسی‌هایی که تا ۷ روز آینده تمام می‌شوند — برای یادآوری تمدید
        $expiringSoon = Database::selectAll(
            'SELECT ca.id, ca.end_date, u.id AS user_id, u.username, c.title AS course_title
             FROM course_access ca
             JOIN users u   ON u.id = ca.user_id
             JOIN courses c ON c.id = ca.course_id
             WHERE ca.is_active = 1
               AND ca.is_unlimited = 0
               AND ca.end_date IS NOT NULL
               AND ca.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
             ORDER BY ca.end_date ASC
             LIMIT 10'
        );

        $recentUsers = Database::selectAll(
            "SELECT id, username, full_name, is_active, created_at, last_login_at
             FROM users
             WHERE role = 'user'
             ORDER BY id DESC
             LIMIT 8"
        );

        $recentActivity = Database::selectAll(
            'SELECT al.action, al.description, al.created_at, u.username
             FROM activity_log al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.id DESC
             LIMIT 30'
        );

        view('admin/dashboard', [
            'title'          => 'داشبورد مدیریت',
            'admin'          => $admin,
            'stats'          => $stats,
            'expiringSoon'   => $expiringSoon,
            'recentUsers'    => $recentUsers,
            'recentActivity' => $recentActivity,
        ], 'layouts/admin');
    }

    public static function profile(array $params = []): void
    {
        $admin = Auth::requireAdmin();

        view('admin/profile', [
            'title' => 'تنطیمات حساب مدیر',
            'admin' => $admin,
        ], 'layouts/admin');
    }

    /** بروزرسانی نام کاربری / نام کامل / رمز — همیشه نیازمند رمز فعلی */
    public static function updateProfile(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $username        = post_val('username', (string) $admin['username']);
        $fullName        = mb_substr(post_val('full_name'), 0, 120);
        $newPassword     = (string) ($_POST['new_password'] ?? '');
        $confirm         = (string) ($_POST['new_password_confirm'] ?? '');

        // ۱) تایید هویت با رمز فعلی — شرط لازم برای هر تغییری
        $hash  = (string) $admin['password_hash'];
        $valid = str_starts_with($hash, 'PLAINTEXT:')
            ? hash_equals(substr($hash, 10), $currentPassword)
            : password_verify($currentPassword, $hash);

        if (!$valid) {
            flash('error', 'رمز عبور فعلی نادرست است. هیچ تغییری اعمال نشد.');
            redirect('/admin/profile');
        }

        // ۲) اعتبارسنجی نام کاربری در صورت تغییر
        $usernameChanged = $username !== (string) $admin['username'];

        if ($usernameChanged) {
            $error = Auth::validateUsername($username);
            if ($error !== null) {
                flash('error', $error);
                redirect('/admin/profile');
            }

            $exists = Database::scalar(
                'SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1',
                [$username, (int) $admin['id']]
            );

            if ($exists !== null) {
                flash('error', 'این نام کاربری قبلاً توسط حساب دیگری گرفته شده است.');
                redirect('/admin/profile');
            }
        }

        // ۳) اعتبارسنجی رمز جدید در صورت ورود
        if ($newPassword !== '') {
            $error = Auth::validatePassword($newPassword)
                ?? ($newPassword !== $confirm ? 'رمز جدید و تکرار آن یکسان نیستند.' : null)
                ?? ($newPassword === $currentPassword ? 'رمز جدید باید با رمز قبلی متفاوت باشد.' : null);

            if ($error !== null) {
                flash('error', $error);
                redirect('/admin/profile');
            }
        }

        Database::execute(
            'UPDATE users SET username = ?, full_name = ? WHERE id = ?',
            [$username, $fullName !== '' ? $fullName : null, (int) $admin['id']]
        );

        if ($newPassword !== '') {
            Auth::setPassword((int) $admin['id'], $newPassword);
            Session::regenerate();
            Auth::log((int) $admin['id'], 'admin_password_change', 'تغییر رمز عبور مدیر');
        }

        if ($usernameChanged) {
            Auth::log((int) $admin['id'], 'admin_username_change', 'تغییر نام کاربری مدیر به: ' . $username);
        }

        flash('success', 'تنطیمات حساب مدیر با موفقیت ذخیره شد.');
        redirect('/admin/profile');
    }

    /** راهنمای مدیر درون پنل */
    public static function help(array $params = []): void
    {
        Auth::requireAdmin();

        view('admin/help', [
            'title' => 'راهنمای مدیریت آکادمی',
        ], 'layouts/admin');
    }
}