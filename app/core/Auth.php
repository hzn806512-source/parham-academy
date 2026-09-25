<?php
declare(strict_types=1);

/**
 * احراز هویت امن با پشتیبانی از ماندگاری نشست (Remember Me)
 */
final class Auth
{
    private static ?array $cachedUser = null;
    private static ?string $lastError = null;

    public static function lastError(): ?string
    {
        return self::$lastError;
    }

    public static function attempt(string $username, string $password): ?array
    {
        self::$lastError = null;

        $user = Database::selectOne(
            'SELECT * FROM users WHERE username = ? LIMIT 1',
            [$username]
        );

        if ($user === null || (int) $user['is_active'] !== 1) {
            self::$lastError = 'invalid_credentials';
            return null;
        }

        if (!self::verifyPassword($password, (string) $user['password_hash'], (int) $user['id'])) {
            self::$lastError = 'invalid_credentials';
            return null;
        }

        // اگر لاگین است، بررسی می‌کنیم که آیا همین دستگاه است یا خیر
        if ((int) ($user['is_logged_in'] ?? 0) === 1) {
            $sameDevice = false;
            $cookieToken = $_COOKIE['pa_remember'] ?? '';
            if ($cookieToken !== '') {
                $sameDevice = self::verifyToken((int) $user['id'], $cookieToken);
            }
            if (!$sameDevice) {
                self::$lastError = 'already_logged_in';
                return null;
            }
        }

        Session::regenerate();
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role']    = (string) $user['role'];
        $_SESSION['_last_activity'] = time();

        Database::execute(
            'UPDATE users SET last_login_at = NOW(), is_logged_in = 1 WHERE id = ?',
            [(int) $user['id']]
        );
        self::log((int) $user['id'], 'login', 'ورود موفق به حساب');

        $user['is_logged_in'] = 1;
        self::$cachedUser = $user;

        self::setRememberCookie($user);
        return $user;
    }

    public static function autoLoginWithToken(string $username, string $token): ?array
    {
        $user = Database::selectOne(
            'SELECT * FROM users WHERE username = ? AND is_active = 1 LIMIT 1',
            [$username]
        );

        if ($user === null || !self::verifyToken((int) $user['id'], $token)) {
            return null;
        }

        Session::regenerate();
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role']    = (string) $user['role'];
        $_SESSION['_last_activity'] = time();

        Database::execute(
            'UPDATE users SET last_login_at = NOW(), is_logged_in = 1 WHERE id = ?',
            [(int) $user['id']]
        );

        $user['is_logged_in'] = 1;
        self::$cachedUser = $user;
        self::setRememberCookie($user);
        return $user;
    }

    public static function makeRememberToken(array $user): string
    {
        $payload = (string) $user['id'] . '|' . (string) $user['username'] . '|' . (string) $user['password_hash'];
        $hash = hash_hmac('sha256', $payload, (string) Config::get('security.app_key'));
        return (string) $user['id'] . '.' . $hash;
    }

    public static function verifyToken(int $userId, string $token): bool
    {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return false;
        }
        [$idStr, $hash] = $parts;
        if ((int) $idStr !== $userId) {
            return false;
        }

        $user = Database::selectOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$userId]);
        if ($user === null) {
            return false;
        }

        $expected = self::makeRememberToken($user);
        return hash_equals($expected, $token);
    }

    public static function userRememberToken(): string
    {
        $u = self::user();
        return $u !== null ? self::makeRememberToken($u) : '';
    }

    private static function setRememberCookie(array $user): void
    {
        $token = self::makeRememberToken($user);
        $expire = time() + (86400 * 60); // 60 روز
        $secure = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        setcookie('pa_remember', $token, [
            'expires'  => $expire,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private static function clearRememberCookie(): void
    {
        if (isset($_COOKIE['pa_remember'])) {
            setcookie('pa_remember', '', [
                'expires' => time() - 3600,
                'path'    => '/',
            ]);
            unset($_COOKIE['pa_remember']);
        }
    }

    private static function verifyPassword(string $password, string $hash, int $userId): bool
    {
        if (str_starts_with($hash, 'PLAINTEXT:')) {
            if (!hash_equals(substr($hash, 10), $password)) {
                return false;
            }
            self::setPassword($userId, $password);
            return true;
        }

        if (!password_verify($password, $hash)) {
            return false;
        }

        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            self::setPassword($userId, $password);
        }

        return true;
    }

    public static function setPassword(int $userId, string $plainPassword): void
    {
        Database::execute(
            'UPDATE users SET password_hash = ? WHERE id = ?',
            [password_hash($plainPassword, PASSWORD_DEFAULT), $userId]
        );
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            // تلاش برای بازیابی خودکار از کوکی پایدار
            $remember = $_COOKIE['pa_remember'] ?? '';
            if ($remember !== '') {
                $parts = explode('.', $remember, 2);
                if (count($parts) === 2 && ctype_digit($parts[0])) {
                    $u = Database::selectOne('SELECT * FROM users WHERE id = ? AND is_active = 1 LIMIT 1', [(int) $parts[0]]);
                    if ($u !== null && self::verifyToken((int) $u['id'], $remember)) {
                        $_SESSION['user_id'] = (int) $u['id'];
                        $_SESSION['role']    = (string) $u['role'];
                        $_SESSION['_last_activity'] = time();
                        Database::execute('UPDATE users SET is_logged_in = 1 WHERE id = ?', [(int) $u['id']]);
                        return self::$cachedUser = $u;
                    }
                }
            }
            return null;
        }

        if (self::$cachedUser !== null && (int) self::$cachedUser['id'] === (int) $_SESSION['user_id']) {
            return self::$cachedUser;
        }

        $user = Database::selectOne(
            'SELECT * FROM users WHERE id = ? LIMIT 1',
            [(int) $_SESSION['user_id']]
        );

        if ($user === null || (int) $user['is_active'] !== 1) {
            self::logout(false);
            return null;
        }

        return self::$cachedUser = $user;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user === null ? null : (int) $user['id'];
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === 'admin';
    }

    public static function requireUser(): array
    {
        $user = self::user();
        if ($user === null) {
            flash('warning', 'برای دسترسی به این بخش ابتدا وارد حساب خود شوید.');
            redirect('/login');
        }
        return $user;
    }

    public static function requireAdmin(): array
    {
        $user = self::requireUser();
        if ($user['role'] !== 'admin') {
            abort(403, 'شما اجازهٔ دسترسی به پنل مدیریت را ندارید.');
        }
        return $user;
    }

    public static function logout(bool $log = true): void
    {
        if (self::check()) {
            $userId = (int) $_SESSION['user_id'];
            try {
                Database::execute('UPDATE users SET is_logged_in = 0 WHERE id = ?', [$userId]);
            } catch (\Throwable $e) {}
            if ($log) {
                self::log($userId, 'logout', 'خروج از حساب');
            }
        }
        self::clearRememberCookie();
        self::$cachedUser = null;
        Session::destroy();
        Session::start();
    }

    public static function tooManyAttempts(string $username): bool
    {
        $window = (int) Config::get('security.lockout_seconds', 900);
        $max    = (int) Config::get('security.max_attempts', 6);

        $count = (int) Database::scalar(
            'SELECT COUNT(*) FROM login_attempts
             WHERE (username = ? OR ip_address = ?)
               AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)',
            [$username, client_ip(), $window]
        );

        return $count >= $max;
    }

    public static function recordFailure(string $username): void
    {
        Database::execute(
            'INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)',
            [mb_substr($username, 0, 60), client_ip()]
        );
        Database::execute('DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY)');
    }

    public static function clearAttempts(string $username): void
    {
        Database::execute(
            'DELETE FROM login_attempts WHERE username = ? OR ip_address = ?',
            [$username, client_ip()]
        );
    }

    public static function log(?int $userId, string $action, string $description = ''): void
    {
        Database::execute(
            'INSERT INTO activity_log (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)',
            [$userId, $action, mb_substr($description, 0, 255), client_ip()]
        );
    }

    public static function validateUsername(string $username): ?string
    {
        if (!preg_match('/^[A-Za-z0-9_\.]{4,30}$/', $username)) {
            return 'نام کاربری باید ۴ تا ۳۰ کاراکتر و شامل حروف لاتین، اعداد، نقطه یا زیرخط باشد.';
        }
        return null;
    }

    public static function validatePassword(string $password): ?string
    {
        if (mb_strlen($password) < 8) {
            return 'رمز عبور باید حداقل ۸ کاراکتر باشد.';
        }
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return 'رمز عبور باید حداقل یک حرف و یک عدد داشته باشد.';
        }
        return null;
    }
}