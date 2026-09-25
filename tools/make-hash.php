<?php
declare(strict_types=1);

// اجرا فقط از خط فرمان:  php tools/make-hash.php admin1234
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only.');
}

$password = $argv[1] ?? '';
if ($password === '') {
    exit("Usage: php tools/make-hash.php <password>\n");
}

echo password_hash($password, PASSWORD_DEFAULT), PHP_EOL;