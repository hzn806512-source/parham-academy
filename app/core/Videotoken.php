<?php
declare(strict_types=1);

/**
 * لینک پخش ویدیو هیچ‌گاه مسیر فایل را فاش نمی‌کند.
 * فرمت توکن: {expiry}.{hmac}  — وابسته به کاربر، درس و نشست.
 */
final class VideoToken
{
    public static function make(int $userId, int $lessonId): string
    {
        $expiry = time() + (int) Config::get('security.video_token_ttl', 10800);
        return $expiry . '.' . self::signature($userId, $lessonId, $expiry);
    }

    public static function verify(int $userId, int $lessonId, string $token): bool
    {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return false;
        }

        [$expiry, $signature] = $parts;

        if (!ctype_digit($expiry) || (int) $expiry < time()) {
            return false;
        }

        return hash_equals(self::signature($userId, $lessonId, (int) $expiry), $signature);
    }

    private static function signature(int $userId, int $lessonId, int $expiry): string
    {
        $payload = $userId . '|' . $lessonId . '|' . $expiry . '|' . session_id();
        return hash_hmac('sha256', $payload, (string) Config::get('security.app_key'));
    }
}