<?php
declare(strict_types=1);

final class AuthController
{
    /* ---------------- ورود ---------------- */

    public static function showLogin(array $params = []): void
    {
        if (Auth::check()) {
            redirect(Auth::isAdmin() ? '/admin' : '/panel');
        }

        view('auth/login', ['title' => 'ورود به حساب کاربری']);
    }

    public static function login(array $params = []): void
    {
        csrf_verify();

        $username = post_val('username');
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            flash('error', 'نام کاربری و رمز عبور را وارد کنید.');
            redirect('/login');
        }

        if (Auth::tooManyAttempts($username)) {
            flash('error', 'تلاش‌های ناموفق بیش از حد مجاز است. لطفاً ۱۵ دقیقه دیگر دوباره تلاش کنید.');
            redirect('/login');
        }

        $user = Auth::attempt($username, $password);

        if ($user === null) {
            if (Auth::lastError() === 'already_logged_in') {
                flash('error', 'شما یک بار با این حساب وارد شدید و تا زمانی که از آن حساب خارج نشوید نمی‌توانید با این حساب وارد شوید.');
                redirect('/login');
            }

            Auth::recordFailure($username);
            flash('error', 'نام کاربری یا رمز عبور نادرست است.');
            redirect('/login');
        }

        Auth::clearAttempts($username);
        flash('success', 'خوش آمدید، ' . $user['username'] . '!');

        redirect($user['role'] === 'admin' ? '/admin' : '/panel');
    }

    /** لاگین خودکار بر اساس اطلاعات ذخیره‌شده در localStorage */
    public static function autoLogin(array $params = []): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode((string) file_get_contents('php://input'), true);
        $username = trim((string) ($input['username'] ?? ''));
        $token    = trim((string) ($input['token'] ?? ''));

        if ($username === '' || $token === '') {
            echo json_encode(['ok' => false, 'error' => 'empty_credentials'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $user = Auth::autoLoginWithToken($username, $token);

        if ($user === null) {
            echo json_encode(['ok' => false, 'error' => 'invalid_token'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode(['ok' => true, 'username' => $user['username']], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /* ---------------- ثبت‌نام ---------------- */

    public static function showRegister(array $params = []): void
    {
        if (Auth::check()) {
            redirect('/panel');
        }

        view('auth/register', ['title' => 'ساخت حساب کاربری']);
    }

    public static function register(array $params = []): void
    {
        csrf_verify();

        $username = post_val('username');
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirm'] ?? '');

        $error = Auth::validateUsername($username)
            ?? Auth::validatePassword($password)
            ?? ($password !== $confirm ? 'رمز عبور و تکرار آن یکسان نیستند.' : null);

        if ($error === null) {
            $exists = Database::scalar('SELECT id FROM users WHERE username = ? LIMIT 1', [$username]);
            if ($exists !== null) {
                $error = 'این نام کاربری قبلاً ثبت شده است. نام دیگری انتخاب کنید.';
            }
        }

        if ($error !== null) {
            flash('error', $error);
            $_SESSION['_old_username'] = $username;
            redirect('/register');
        }

        $userId = Database::insert(
            "INSERT INTO users (username, password_hash, role, is_active, is_logged_in) VALUES (?, ?, 'user', 1, 0)",
            [$username, password_hash($password, PASSWORD_DEFAULT)]
        );

        Auth::log($userId, 'register', 'ساخت حساب کاربری جدید');
        Auth::attempt($username, $password);

        flash('success', 'حساب شما ساخته شد. برای فعال‌سازی دوره‌ها با مدیر آکادمی در تماس باشید.');
        redirect('/panel');
    }

    /* ---------------- خروج ---------------- */

    public static function logout(array $params = []): void
    {
        csrf_verify();
        Auth::logout();
        flash('success', 'از حساب خود خارج شدید.');
        redirect('/');
    }
}