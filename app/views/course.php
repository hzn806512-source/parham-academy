<?php
/** @var array $course */
/** @var array $lessons */
/** @var array $access */
/** @var array|null $user */
$levels = ['beginner' => 'مقدماتی', 'intermediate' => 'متوسط', 'advanced' => 'پیشرفته'];
$granted = (bool) $access['granted'];
$totalMinutes = 0;
foreach ($lessons as $lesson) {
    $totalMinutes += (int) $lesson['duration_minutes'];
}
?>
<section class="section">
    <div class="container course-detail">

        <div class="course-main">
            <nav class="breadcrumb">
                <a href="<?= url('/') ?>">خانه</a> <span>/</span>
                <a href="<?= url('/courses') ?>">دوره‌ها</a> <span>/</span>
                <strong><?= e($course['title']) ?></strong>
            </nav>

            <h1><?= e($course['title']) ?></h1>

            <div class="chips">
                <span class="chip"><?= e($levels[$course['level']] ?? 'مقدماتی') ?></span>
                <span class="chip"><?= e(fa_digits((string) count($lessons))) ?> درس</span>
                <span class="chip"><?= e(duration_label($totalMinutes)) ?></span>
            </div>

            <?php if (!empty($course['cover_image'])): ?>
                <div class="course-hero-image">
                    <img src="<?= cover_url((string) ($course['cover_image'] ?? '')) ?>"
                         alt="<?= e($course['title']) ?>">
                </div>
            <?php endif; ?>

            <?php if (!empty($course['description'])): ?>
                <div class="prose">
                    <?php foreach (preg_split('/\r?\n\r?\n/', (string) $course['description']) as $paragraph): ?>
                        <?php if (trim($paragraph) !== ''): ?>
                            <p><?= nl2br(e(trim($paragraph))) ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h2 class="mt-lg">سرفصل دروس</h2>

            <?php if ($lessons === []): ?>
                <div class="empty-state">دروس این دوره به‌زودی منتشر می‌شود.</div>
            <?php else: ?>
                <ul class="lesson-list">
                    <?php foreach ($lessons as $index => $lesson): ?>
                        <?php
                        $isFree    = (int) $lesson['is_free_preview'] === 1;
                        $canWatch  = $granted || ($isFree && $user !== null);
                        ?>
                        <li class="lesson-item">
                            <span class="lesson-index"><?= e(fa_digits((string) ($index + 1))) ?></span>
                            <div class="lesson-body">
                                <strong><?= e($lesson['title']) ?></strong>
                                <?php if (!empty($lesson['description'])): ?>
                                    <small class="muted"><?= e(excerpt($lesson['description'], 90)) ?></small>
                                <?php endif; ?>
                            </div>
                            <span class="lesson-time"><?= e(duration_label((int) $lesson['duration_minutes'])) ?></span>

                            <?php if ($canWatch): ?>
                                <a class="btn btn-primary btn-sm" href="<?= url('/watch/' . (int) $lesson['id']) ?>">تماشا</a>
                            <?php elseif ($isFree): ?>
                                <a class="btn btn-outline btn-sm" href="<?= url('/login') ?>">پیش‌نمایش رایگان</a>
                            <?php else: ?>
                                <span class="lock" title="قفل است">● قفل</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <aside class="course-side">
            <div class="card side-card">
                <div class="side-price"><?= e(money($course['price_display'])) ?></div>

                <div class="access-box access-<?= e((string) $access['state']) ?>">
                    <strong><?= e((string) $access['label']) ?></strong>
                    <p><?= e((string) $access['message']) ?></p>
                </div>

                <?php if ($user === null): ?>
                    <a class="btn btn-primary btn-block" href="<?= url('/login') ?>">ورود به حساب</a>
                    <a class="btn btn-ghost btn-block" href="<?= url('/register') ?>">ثبت‌نام</a>
                <?php elseif ($granted): ?>
                    <a class="btn btn-primary btn-block" href="<?= url('/panel') ?>">رفتن به پنل و تماشا</a>
                <?php else: ?>
                    <div class="contact-box">
                        <span>برای فعال‌سازی تماس بگیرید:</span>
                        <ul>
                            <li>تلگرام: <?= e(Config::get('app.contact.telegram')) ?></li>
                            <li>اینستاگرام: <?= e(Config::get('app.contact.instagram')) ?></li>
                            <li>تلفن: <?= e(fa_digits((string) Config::get('app.contact.phone'))) ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <ul class="side-facts">
                    <li><span>سطح</span><strong><?= e($levels[$course['level']] ?? 'مقدماتی') ?></strong></li>
                    <li><span>تعداد درس</span><strong><?= e(fa_digits((string) count($lessons))) ?></strong></li>
                    <li><span>مدت محتوا</span><strong><?= e(duration_label($totalMinutes)) ?></strong></li>
                    <li><span>نوع دسترسی</span><strong>آنلاین — بدون دانلود</strong></li>
                </ul>
            </div>
        </aside>
    </div>
</section>