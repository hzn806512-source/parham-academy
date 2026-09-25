<?php
declare(strict_types=1);

/**
 * مدیریت کامل کاربران: فهرست، جستجو، ساخت، ویرایش، تغییر رمز توسط مدیر و حذف.
 * محافط: مدیر نمی‌تواند حساب خودش را حذف/غیرفعال کند و آخرین مدیر قابل حذف نیست.
 */
final class AdminUserController
{
    private const PER_PAGE = 20;

    public static function index(array $params = []): void
    {
        Auth::requireAdmin();

        $search = get_val('q');
        $role   = get_val('role');
        $page   = max(1, (int) get_val('page', '1'));

        $where = ['1 = 1'];
        $args  = [];

        if ($search !== '') {
            $where[] = '(u.username LIKE ? OR u.full_name LIKE ? OR u.note LIKE ?)';
            $like    = '%' . $search . '%';
            $args[]  = $like;
            $args[]  = $like;
            $args[]  = $like;
        }

        if (in_array($role, ['user', 'admin'], true)) {
            $where[] = 'u.role = ?';
            $args[]  = $role;
        }

        $whereSql = implode(' AND ', $where);

        $total  = (int) Database::scalar("SELECT COUNT(*) FROM users u WHERE {$whereSql}", $args);
        $pages  = max(1, (int) ceil($total / self::PER_PAGE));
        $page   = min($page, $pages);
        $offset = ($page - 1) * self::PER_PAGE;

        $users = Database::selectAll(
            "SELECT u.*,
                    (SELECT COUNT(*) FROM course_access ca WHERE ca.user_id = u.id) AS access_count,
                    (SELECT COUNT(*) FROM course_access ca
                      WHERE ca.user_id = u.id
                        AND ca.is_active = 1
                        AND (ca.is_unlimited = 1
                             OR ((ca.start_date IS NULL OR ca.start_date <= CURDATE())
                                 AND (ca.end_date IS NULL OR ca.end_date >= CURDATE())))
                    ) AS active_access_count
             FROM users u
             WHERE {$whereSql}
             ORDER BY u.role = 'admin' DESC, u.id DESC
             LIMIT " . self::PER_PAGE . " OFFSET {$offset}",
            $args
        );

        view('admin/users', [
            'title'  => 'مدیریت کاربران',
            'users'  => $users,
            'search' => $search,
            'role'   => $role,
            'total'  => $total,
            'page'   => $page,
            'pages'  => $pages,
        ], 'layouts/admin');
    }

    public static function create(array $params = []): void
    {
        Auth::requireAdmin();

        view('admin/user-edit', [
            'title'   => 'ساخت کاربر جدید',
            'user'    => null,
            'courses' => self::courseList(),
            'access'  => [],
        ], 'layouts/admin');
    }

    public static function store(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $username = post_val('username');
        $password = (string) ($_POST['password'] ?? '');
        $fullName = mb_substr(post_val('full_name'), 0, 120);
        $note     = mb_substr(post_val('note'), 0, 255);
        $role     = post_val('role') === 'admin' ? 'admin' : 'user';
        $isActive = post_val('is_active') === '1' ? 1 : 0;

        $error = Auth::validateUsername($username) ?? Auth::validatePassword($password);

        if ($error === null) {
            $exists = Database::scalar('SELECT id FROM users WHERE username = ? LIMIT 1', [$username]);
            if ($exists !== null) {
                $error = 'این نام کاربری قبلاً ثبت شده است.';
            }
        }

        if ($error !== null) {
            flash('error', $error);
            redirect('/admin/users/create');
        }

        $userId = Database::insert(
            'INSERT INTO users (username, password_hash, full_name, role, is_active, note)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $fullName !== '' ? $fullName : null,
                $role,
                $isActive,
                $note !== '' ? $note : null,
            ]
        );

        Auth::log((int) $admin['id'], 'admin_user_create', 'ساخت کاربر: ' . $username);
        flash('success', 'کاربر «' . $username . '» ساخته شد. می‌توانید الان دسترسی دوره برایش فعال کنید.');
        redirect('/admin/users/' . $userId . '/edit');
    }

    public static function edit(array $params = []): void
    {
        Auth::requireAdmin();

        $user = self::findUser((int) ($params['id'] ?? 0));

        view('admin/user-edit', [
            'title'   => 'ویرایش کاربر: ' . $user['username'],
            'user'    => $user,
            'courses' => self::courseList(),
            'access'  => Access::coursesOf((int) $user['id']),
        ], 'layouts/admin');
    }

    public static function update(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $user = self::findUser((int) ($params['id'] ?? 0));
        $id   = (int) $user['id'];

        $fullName    = mb_substr(post_val('full_name'), 0, 120);
        $note        = mb_substr(post_val('note'), 0, 255);
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $isActive    = post_val('is_active') === '1' ? 1 : 0;
        $role        = post_val('role') === 'admin' ? 'admin' : 'user';

        // محافطت: مدیر نمی‌تواند نقش یا وضعیت حساب خودش را در اینجا عوض کند
        if ($id === (int) $admin['id']) {
            $role     = (string) $user['role'];
            $isActive = 1;
        }

        // محافطت: آخرین مدیر فعال نباید از دسترس خارج شود
        if ((string) $user['role'] === 'admin' && ($role !== 'admin' || $isActive === 0)) {
            $activeAdmins = (int) Database::scalar(
                "SELECT COUNT(*) FROM users WHERE role = 'admin' AND is_active = 1 AND id <> ?",
                [$id]
            );
            if ($activeAdmins === 0) {
                flash('error', 'این تنها مدیر فعال سایت است و نمی‌تواند غیرفعال یا عادی شود.');
                redirect('/admin/users/' . $id . '/edit');
            }
        }

        if ($newPassword !== '') {
            $error = Auth::validatePassword($newPassword);
            if ($error !== null) {
                flash('error', $error);
                redirect('/admin/users/' . $id . '/edit');
            }
        }

        Database::execute(
            'UPDATE users SET full_name = ?, note = ?, role = ?, is_active = ? WHERE id = ?',
            [
                $fullName !== '' ? $fullName : null,
                $note !== '' ? $note : null,
                $role,
                $isActive,
                $id,
            ]
        );

        if ($newPassword !== '') {
            Auth::setPassword($id, $newPassword);
            Auth::log((int) $admin['id'], 'admin_reset_password', 'تغییر رمز کاربر: ' . $user['username']);
        }

        Auth::log((int) $admin['id'], 'admin_user_update', 'ویرایش کاربر: ' . $user['username']);
        flash('success', 'اطلاعات کاربر بروز شد.');
        redirect('/admin/users/' . $id . '/edit');
    }

    public static function destroy(array $params = []): void
    {
        $admin = Auth::requireAdmin();
        csrf_verify();

        $user = self::findUser((int) ($params['id'] ?? 0));
        $id   = (int) $user['id'];

        if ($id === (int) $admin['id']) {
            flash('error', 'حساب کاربری خودتان را نمی‌توانید حذف کنید.');
            redirect('/admin/users');
        }

        if ((string) $user['role'] === 'admin') {
            $otherAdmins = (int) Database::scalar(
                "SELECT COUNT(*) FROM users WHERE role = 'admin' AND id <> ?",
                [$id]
            );
            if ($otherAdmins === 0) {
                flash('error', 'آخرین حساب مدیر قابل حذف نیست.');
                redirect('/admin/users');
            }
        }

        // دسترسی‌ها به لطف ON DELETE CASCADE خودکار پاک می‌شوند
        Database::execute('DELETE FROM users WHERE id = ?', [$id]);

        Auth::log((int) $admin['id'], 'admin_user_delete', 'حذف کاربر: ' . $user['username']);
        flash('success', 'کاربر «' . $user['username'] . '» و دسترسی‌هایش حذف شد.');
        redirect('/admin/users');
    }

    /** ---------- کمکی‌ها ---------- */

    private static function findUser(int $id): array
    {
        $user = $id > 0
            ? Database::selectOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$id])
            : null;

        if ($user === null) {
            abort(404, 'کاربر مورد نطر یافت نشد.');
        }

        return $user;
    }

    private static function courseList(): array
    {
        return Database::selectAll(
            'SELECT id, title, status FROM courses ORDER BY sort_order ASC, id DESC'
        );
    }
}