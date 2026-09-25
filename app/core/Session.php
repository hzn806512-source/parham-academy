<?php
declare(strict_types=1);

/** مدیریت امن نشست: کوکی دائمی، HttpOnly، SameSite و انقضای بی‌کاری */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        $lifetime = (int) Config::get('security.session_idle', 2592000);

        session_name((string) Config::get('security.session_name', 'parham_session'));
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        self::enforceIdleTimeout();
        self::enforceFingerprint();
    }

    private static function enforceIdleTimeout(): void
    {
        $idle = (int) Config::get('security.session_idle', 2592000);
        $last = (int) ($_SESSION['_last_activity'] ?? 0);

        if ($last > 0 && (time() - $last) > $idle) {
            if (isset($_SESSION['user_id'])) {
                try {
                    Database::execute('UPDATE users SET is_logged_in = 0 WHERE id = ?', [(int) $_SESSION['user_id']]);
                } catch (\Throwable $e) {}
            }
            self::destroy();
            session_start();
            flash('warning', 'به دلیل عدم فعالیت طولانی، از حساب خارج شدید.');
        }

        $_SESSION['_last_activity'] = time();
    }

    private static function enforceFingerprint(): void
    {
        $current = self::fingerprint();

        if (!isset($_SESSION['_fingerprint'])) {
            $_SESSION['_fingerprint'] = $current;
            return;
        }

        if (!hash_equals((string) $_SESSION['_fingerprint'], $current)) {
            if (isset($_SESSION['user_id'])) {
                try {
                    Database::execute('UPDATE users SET is_logged_in = 0 WHERE id = ?', [(int) $_SESSION['user_id']]);
                } catch (\Throwable $e) {}
            }
            self::destroy();
            session_start();
            $_SESSION['_fingerprint'] = $current;
        }
    }

    public static function fingerprint(): string
    {
        return hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $params['path'],
                'domain'   => $params['domain'],
                'secure'   => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Lax',
            ]);
        }
        session_destroy();
    }
}