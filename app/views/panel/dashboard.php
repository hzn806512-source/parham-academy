<?php
/** @var array $user */
/** @var array $courses */
/** @var array $summary */
$stateMeta = [
    'active'  => ['فعال', 'ok'],
    'pending' => ['شروع نشده', 'warn'],
    'expired' => ['منقضی شده', 'danger'],
    'revoked' => ['غیرفعال شده', 'danger'],
];
?>
<div class="page-head">
    <div>
        <h1>سلام <?= e($user['full_name'] ?: $user['username']) ?> 👋</h1>
        <p class="muted">نام کاربری: <b dir="ltr"><?= e($user['username']) ?></b></p>
    </div>
    <a class="btn btn-outline btn-sm" href="<?= url('/panel/profile') ?>">پروفایل و رمز</a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <span>دوره‌های من</span>
        <strong><?= e(fa_digits((string) $summary['total'])) ?></strong>
    </div>
    <div class="stat-card stat-ok">
        <span>دسترسی فعال</span>
        <strong><?= e(fa_digits((string) $summary['active'])) ?></strong>
    </div>
    <div class="stat-card stat-danger">
        <span>منقضی / غیرفعال</span>
        <strong><?= e(fa_digits((string) $summary['expired'])) ?></strong>
    </div>
</div>

<h2 class="mt-lg">دوره‌های فعال‌شده برای شما</h2>

<?php if ($courses === []): ?>
    <div class="empty-state">
        هنوز دوره‌ای برای حساب شما فعال نشده است.
        <a class="btn btn-primary btn-sm" href="<?= url('/courses') ?>">مشاهدهٔ دوره‌ها</a>
    </div>
<?php else: ?>
    <div class="grid grid-2">
        <?php foreach ($courses as $course): ?>
            <?php
            $state = (string) $course['state'];
            [$label, $tone] = $stateMeta[$state] ?? ['نامشخص', 'muted'];
            $remaining = $course['remaining_days'];
            ?>
            <article class="card access-card reveal">
                <div class="access-card-head">
                    <h3><?= e($course['title']) ?></h3>
                    <span class="tag tag-<?= e($tone) ?>"><?= e($label) ?></span>
                </div>

                <p class="muted"><?= e(excerpt($course['short_description'], 110)) ?></p>

                <ul class="facts">
                    <li><span>تعداد درس</span><strong><?= e(fa_digits((string) $course['lesson_count'])) ?></strong></li>
                    <li><span>شروع</span><strong><?= e(jalali_date($course['start_date'])) ?></strong></li>
                    <li>
                        <span>پایان</span>
                        <strong>
                            <?php if ((int) $course['is_unlimited'] === 1): ?>
                                نامحدود
                            <?php else: ?>
                                <?= e(jalali_date($course['end_date'])) ?>
                            <?php endif; ?>
                        </strong>
                    </li>
                    <?php if ($remaining !== null): ?>
                        <li>
                            <span>باقی‌مانده</span>
                            <strong><?= e(fa_digits((string) $remaining)) ?> روز</strong>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if ($state === 'active'): ?>
                    <?php
                    $lessons = Database::selectAll(
                        'SELECT id, title, duration_minutes FROM lessons
                         WHERE course_id = ? ORDER BY sort_order ASC, id ASC',
                        [(int) $course['id']]
                    );
                    ?>
                    <?php if ($lessons === []): ?>
                        <div class="note">دروس این دوره به‌زودی افزوده می‌شود.</div>
                    <?php else: ?>
                        <details class="lesson-toggle">
                            <summary>فهرست دروس (<?= e(fa_digits((string) count($lessons))) ?>)</summary>
                            <ul class="lesson-list compact">
                                <?php foreach ($lessons as $i => $lesson): ?>
                                    <li class="lesson-item">
                                        <span class="lesson-index"><?= e(fa_digits((string) ($i + 1))) ?></span>
                                        <div class="lesson-body"><strong><?= e($lesson['title']) ?></strong></div>
                                        <span class="lesson-time"><?= e(duration_label((int) $lesson['duration_minutes'])) ?></span>
                                        <a class="btn btn-primary btn-sm" href="<?= url('/watch/' . (int) $lesson['id']) ?>">تماشا</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="note note-danger">
                        <?= e(Access::EXPIRED_MESSAGE) ?>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>