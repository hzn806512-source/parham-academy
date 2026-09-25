<?php
declare(strict_types=1);

/**
 * منطق مرکزی دسترسی کاربر به دوره.
 * وضعیت‌ها: none | pending | active | expired | revoked | admin
 * محاسبه در لحطه انجام می‌شود → بدون نیاز به Cron؛ دقیقاً در پایان تاریخ، دسترسی قطع می‌شود.
 */
final class Access
{
    public const EXPIRED_MESSAGE =
        'دسترسی شما به پایان رسیده است. لطفاً برای تمدید دسترسی با مدیر آکادمی تماس بگیرید.';

    public static function record(int $userId, int $courseId): ?array
    {
        return Database::selectOne(
            'SELECT * FROM course_access WHERE user_id = ? AND course_id = ? LIMIT 1',
            [$userId, $courseId]
        );
    }

    /** وضعیت یک رکورد دسترسی */
    public static function stateOf(?array $row): string
    {
        if ($row === null) {
            return 'none';
        }
        if ((int) $row['is_active'] !== 1) {
            return 'revoked';
        }
        if ((int) $row['is_unlimited'] === 1) {
            return 'active';
        }

        $today = date('Y-m-d');

        if (!empty($row['start_date']) && $row['start_date'] > $today) {
            return 'pending';
        }
        if (!empty($row['end_date']) && $row['end_date'] < $today) {
            return 'expired';
        }

        return 'active';
    }

    /** وضعیت کامل دسترسی یک کاربر به یک دوره */
    public static function status(?array $user, int $courseId): array
    {
        if ($user === null) {
            return [
                'state'   => 'guest',
                'granted' => false,
                'row'     => null,
                'label'   => 'برای مشاهده وارد حساب شوید',
                'message'  => 'برای بررسی وضعیت دسترسی، ابتدا وارد حساب خود شوید.',
            ];
        }

        if ($user['role'] === 'admin') {
            return [
                'state'   => 'admin',
                'granted' => true,
                'row'     => null,
                'label'   => 'دسترسی مدیر',
                'message' => 'شما به‌عنوان مدیر به همهٔ دروس دسترسی دارید.',
            ];
        }

        $row   = self::record((int) $user['id'], $courseId);
        $state = self::stateOf($row);

        $labels = [
            'none'    => 'دسترسی فعال ندارید',
            'pending' => 'دسترسی هنوز شروع نشده',
            'active'  => 'دسترسی فعال',
            'expired' => 'دسترسی منقضی شده',
            'revoked' => 'دسترسی غیرفعال شده',
        ];

        $messages = [
            'none'    => 'این دوره برای حساب شما فعال نشده است. برای تهیه و فعال‌سازی دسترسی با مدیر آکادمی تماس بگیرید.',
            'pending' => 'دسترسی شما ثبت شده اما هنوز آغاز نشده است.',
            'active'  => 'دسترسی شما به این دوره فعال است.',
            'expired' => self::EXPIRED_MESSAGE,
            'revoked' => self::EXPIRED_MESSAGE,
        ];

        return [
            'state'   => $state,
            'granted' => $state === 'active',
            'row'     => $row,
            'label'   => $labels[$state],
            'message' => $messages[$state],
        ];
    }

    /** مجوز تماشای یک درس — مرجع نهایی برای پخش ویدیو */
    public static function canWatch(?array $user, int $courseId, bool $isFreePreview = false): bool
    {
        if ($isFreePreview && $user !== null) {
            return true;
        }
        return self::status($user, $courseId)['granted'] === true;
    }

    /** روزهای باقی‌مانده (null = نامحدود) */
    public static function remainingDays(?array $row): ?int
    {
        if ($row === null || (int) $row['is_unlimited'] === 1 || empty($row['end_date'])) {
            return null;
        }
        $end   = strtotime((string) $row['end_date'] . ' 23:59:59');
        $diff  = $end - time();
        return $diff <= 0 ? 0 : (int) ceil($diff / 86400);
    }

    /** دوره‌های یک کاربر همراه وضعیت دسترسی */
    public static function coursesOf(int $userId): array
    {
        $rows = Database::selectAll(
            'SELECT c.*, ca.start_date, ca.end_date, ca.is_unlimited, ca.is_active AS access_active,
                    (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS lesson_count
             FROM course_access ca
             JOIN courses c ON c.id = ca.course_id
             WHERE ca.user_id = ?
             ORDER BY ca.is_active DESC, ca.updated_at DESC',
            [$userId]
        );

        foreach ($rows as &$row) {
            $row['state'] = self::stateOf([
                'is_active'    => $row['access_active'],
                'is_unlimited' => $row['is_unlimited'],
                'start_date'   => $row['start_date'],
                'end_date'     => $row['end_date'],
            ]);
            $row['remaining_days'] = self::remainingDays([
                'is_unlimited' => $row['is_unlimited'],
                'end_date'     => $row['end_date'],
            ]);
        }

        return $rows;
    }
}