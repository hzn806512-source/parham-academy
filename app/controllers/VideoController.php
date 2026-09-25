<?php
declare(strict_types=1);

/**
 * معماری امنیت ویدیو:
 * 1) فایل‌ها در storage/videos خارج از Document Root قرار دارند → دسترسی مستقیم ممکن نیست.
 * 2) لینک پخش فقط با توکن HMAC وابسته به کاربر/درس/نشست کار می‌کند و عمر محدود دارد.
 * 3) قبل از ارسال هر بایت، مجوز دوباره از دیتابیس کنترل می‌شود (انقضای لحطه‌ای).
 * 4) پشتیبانی از Range برای پخش روان و جابجایی زمان در موبایل.
 * 5) در صورت استفاده از لینک اینترنتی (CDN / هاست دانلود)، ریدایرکت امن بعد از احراز هویت انجام می‌شود.
 */
final class VideoController
{
    private const MIME_MAP = [
        'mp4'  => 'video/mp4',
        'm4v'  => 'video/mp4',
        'webm' => 'video/webm',
        'mov'  => 'video/quicktime',
    ];

    public static function stream(array $params = []): void
    {
        $lessonId = (int) ($params['lesson'] ?? 0);
        $token    = get_val('t');

        $user = Auth::user();
        if ($user === null) {
            self::deny(401);
        }

        if (!VideoToken::verify((int) $user['id'], $lessonId, $token)) {
            self::deny(403);
        }

        $lesson = Database::selectOne(
            'SELECT id, course_id, video_file, is_free_preview FROM lessons WHERE id = ? LIMIT 1',
            [$lessonId]
        );

        if ($lesson === null) {
            self::deny(404);
        }

        // مرجع نهایی مجوز: سمت سرور، هر بار درخواست
        if (!Access::canWatch($user, (int) $lesson['course_id'], (int) $lesson['is_free_preview'] === 1)) {
            self::deny(403);
        }

        $videoFile = (string) $lesson['video_file'];

        // اگر ویدیو یک آدرس اینترنتی مستقیم است (CDN / هاست دانلود)، ریدایرکت امن بعد از تایید دسترسی
        if (is_url($videoFile)) {
            header('Location: ' . $videoFile, true, 302);
            exit;
        }

        // جلوگیری از Path Traversal: فقط نام فایل محلی پذیرفته می‌شود
        $fileName = basename($videoFile);
        $fullPath = rtrim((string) Config::get('paths.videos'), '/') . '/' . $fileName;

        if ($fileName === '' || !is_file($fullPath) || !is_readable($fullPath)) {
            self::deny(404);
        }

        self::send($fullPath);
    }

    private static function deny(int $code): never
    {
        http_response_code($code);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'دسترسی مجاز نیست.';
        exit;
    }

    /** ارسال فایل با پشتیبانی HTTP Range */
    private static function send(string $path): never
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $size      = (int) filesize($path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime      = self::MIME_MAP[$extension] ?? 'application/octet-stream';

        $start = 0;
        $end   = $size - 1;

        header_remove('X-Powered-By');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline');
        header('Accept-Ranges: bytes');
        header('Cache-Control: private, no-store, max-age=0');
        header('Pragma: no-cache');
        header('X-Content-Type-Options: nosniff');
        header('X-Robots-Tag: noindex, nofollow');

        $range = (string) ($_SERVER['HTTP_RANGE'] ?? '');
        if ($range !== '' && preg_match('/bytes=(\d*)-(\d*)/', $range, $m) === 1) {
            if ($m[1] !== '') {
                $start = (int) $m[1];
            }
            if ($m[2] !== '') {
                $end = min((int) $m[2], $size - 1);
            }

            if ($start > $end || $start >= $size) {
                http_response_code(416);
                header('Content-Range: bytes */' . $size);
                exit;
            }

            http_response_code(206);
            header(sprintf('Content-Range: bytes %d-%d/%d', $start, $end, $size));
        }

        $length = $end - $start + 1;
        header('Content-Length: ' . $length);

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            self::deny(500);
        }

        fseek($handle, $start);
        $remaining = $length;
        $chunkSize = 262144; // 256KB

        while ($remaining > 0 && !feof($handle) && connection_status() === CONNECTION_NORMAL) {
            $buffer = fread($handle, (int) min($chunkSize, $remaining));
            if ($buffer === false) {
                break;
            }
            echo $buffer;
            flush();
            $remaining -= strlen($buffer);
        }

        fclose($handle);
        exit;
    }
}