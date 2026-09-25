<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
    http_response_code(500);
    exit('BASE_PATH is not defined.');
}

require BASE_PATH . '/app/core/helpers.php';

// بارگزاری خودکار کلاس‌ها
spl_autoload_register(static function (string $class): void {
    foreach (['core', 'controllers'] as $dir) {
        $file = BASE_PATH . '/app/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

Config::load(require BASE_PATH . '/app/config.php');

date_default_timezone_set((string) Config::get('app.timezone', 'Asia/Tehran'));
mb_internal_encoding('UTF-8');

if (Config::get('app.debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
}

// هدرهای امنیتی پایه (مستقل از وب‌سرور)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

Session::start();