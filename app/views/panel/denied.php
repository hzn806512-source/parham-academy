<?php
/** @var array $lesson */
/** @var array $access */
?>
<div class="page-head">
    <div>
        <h1>دسترسی به این درس فعال نیست</h1>
        <p class="muted"><?= e($lesson['course_title']) ?> — <?= e($lesson['title']) ?></p>
    </div>
</div>

<section class="card denied-card reveal">
    <div class="denied-icon">⛔</div>
    <h2><?= e((string) $access['label']) ?></h2>
    <p><?= e((string) $access['message']) ?></p>

    <div class="contact-box">
        <span>برای تمدید یا فعال‌سازی:</span>
        <ul>
            <li>تلگرام: <?= e(Config::get('app.contact.telegram')) ?></li>
            <li>اینستاگرام: <?= e(Config::get('app.contact.instagram')) ?></li>
            <li>تلفن: <?= e(fa_digits((string) Config::get('app.contact.phone'))) ?></li>
        </ul>
    </div>

    <div class="denied-actions">
        <a class="btn btn-primary" href="<?= url('/panel') ?>">بازگشت به پنل</a>
        <a class="btn btn-ghost" href="<?= url('/course/' . $lesson['course_slug']) ?>">مشاهدهٔ دوره</a>
    </div>
</section>