<?php
declare(strict_types=1);

return [
    'app' => [
        'name'     => 'آکادمی پرهام | مرجع آموزش تخصصی آرایشگری و گریم',
        'tagline'  => 'مرجع آموزش تخصصی آرایشگری، استایل مو و گریم حرفه‌ای با مدرک معتبر',
        'url'      => '/parham-academy/public',
        'debug'    => false,
        'timezone' => 'Asia/Tehran',
        'contact'  => [
            'telegram'  => '@parham_academy',
            'instagram' => 'parham.academy',
            'phone'     => '021-22334455',
        ],
    ],

    'db' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'parham_academy',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    'security' => [
        'app_key'         => 'parham_academy_secure_random_key_987654321_abcdef',
        'session_name'    => 'parham_session',
        'session_idle'    => 2592000,
        'video_token_ttl' => 10800,
        'max_attempts'    => 6,
        'lockout_seconds' => 900,
    ],

    'paths' => [
        'videos'  => BASE_PATH . '/storage/videos',
        'uploads' => BASE_PATH . '/public/uploads/courses',
    ],

    'upload' => [
        'image_max_bytes' => 0,
        'image_mimes'     => ['image/jpeg', 'image/png', 'image/webp'],
        'video_max_bytes' => 0,
        'video_mimes'     => ['video/mp4', 'video/webm', 'video/quicktime'],
    ],
];
