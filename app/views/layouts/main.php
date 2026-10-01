
<?php
/** @var string $content */
/** @var string|null $title */
$currentUser = Auth::user();
$path        = current_path();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0c0c0e">
    <meta name="description" content="<?= e(Config::get('app.tagline')) ?>">
    <script>(function(){try{if(localStorage.getItem('pa_theme')==='light'){document.documentElement.setAttribute('data-theme','light');}}catch(e){}})();</script>
    <title><?= e($title ?? Config::get('app.name')) ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="site" data-auth-user="<?= e($currentUser['username'] ?? '') ?>" data-auth-token="<?= e($currentUser ? Auth::userRememberToken() : '') ?>">

<header class="topbar" id="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="<?= url('/') ?>">
            <span class="brand-mark">P</span>
            <span class="brand-text">
                <strong>آکادمی پرهام</strong>
                <small>آموزش تخصصی آرایشگری و گریم</small>
            </span>
        </a>

        <div class="topbar-actions">
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="تغییر پوسته (روز/شب)" aria-pressed="false">☀</button>
            <button class="nav-toggle" type="button" data-sidebar-toggle aria-label="منو" aria-expanded="false">
                <svg class="nav-toggle-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path class="bar bar-1" d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path class="bar bar-2" d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path class="bar bar-3" d="M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <nav class="nav" id="main-nav" data-nav>
            <a class="nav-link <?= $path === '/' ? 'is-active' : '' ?>" href="<?= url('/') ?>">صفحهٔ اصلی</a>
            <a class="nav-link <?= str_starts_with($path, '/course') ? 'is-active' : '' ?>" href="<?= url('/courses') ?>">دوره‌ها</a>
            <a class="nav-link <?= $path === '/guide' ? 'is-active' : '' ?>" href="<?= url('/guide') ?>">راهنما</a>

            <?php if ($currentUser === null): ?>
                <a class="nav-link" href="<?= url('/login') ?>">ورود</a>
                <a class="btn btn-primary btn-sm" href="<?= url('/register') ?>">ثبت‌نام</a>
            <?php else: ?>
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <a class="nav-link" href="<?= url('/admin') ?>">پنل مدیریت</a>
                <?php endif; ?>
                <a class="btn btn-outline btn-sm" href="<?= url('/panel') ?>">پنل من</a>
                <form class="inline-form" method="post" action="<?= url('/logout') ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-ghost btn-sm" type="submit">خروج</button>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</header>

<aside class="sidebar sidebar-site" data-sidebar>
    <nav class="sidebar-nav">
        <a class="sidebar-link <?= $path === '/' ? 'is-active' : '' ?>" href="<?= url('/') ?>">
            <span class="sidebar-icon">⌂</span> صفحهٔ اصلی
        </a>
        <a class="sidebar-link <?= str_starts_with($path, '/course') ? 'is-active' : '' ?>" href="<?= url('/courses') ?>">
            <span class="sidebar-icon">◇</span> دوره‌ها
        </a>
        <a class="sidebar-link <?= $path === '/guide' ? 'is-active' : '' ?>" href="<?= url('/guide') ?>">
            <span class="sidebar-icon">○</span> راهنما
        </a>

        <?php if ($currentUser === null): ?>
            <a class="sidebar-link <?= $path === '/login' ? 'is-active' : '' ?>" href="<?= url('/login') ?>">
                <span class="sidebar-icon">↪</span> ورود
            </a>
            <a class="sidebar-link <?= $path === '/register' ? 'is-active' : '' ?>" href="<?= url('/register') ?>">
                <span class="sidebar-icon">＋</span> ثبت‌نام
            </a>
        <?php else: ?>
            <a class="sidebar-link <?= $path === '/panel' ? 'is-active' : '' ?>" href="<?= url('/panel') ?>">
                <span class="sidebar-icon">▣</span> داشبورد
            </a>
            <a class="sidebar-link <?= $path === '/panel/profile' ? 'is-active' : '' ?>" href="<?= url('/panel/profile') ?>">
                <span class="sidebar-icon">◈</span> پروفایل و رمز
            </a>
            <?php if ($currentUser['role'] === 'admin'): ?>
                <a class="sidebar-link <?= str_starts_with($path, '/admin') ? 'is-active' : '' ?>" href="<?= url('/admin') ?>">
                    <span class="sidebar-icon">✦</span> پنل مدیریت
                </a>
            <?php endif; ?>
            <form class="sidebar-logout-form" method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button class="sidebar-link sidebar-link-btn" type="submit">
                    <span class="sidebar-icon">⏻</span> خروج
                </button>
            </form>
        <?php endif; ?>
    </nav>
</aside>

<?php $messages = flashes(); ?>
<?php if ($messages !== []): ?>
    <div class="container flash-wrap">
        <?php foreach ($messages as $message): ?>
            <div class="flash flash-<?= e($message['type']) ?>" data-flash>
                <span><?= e($message['message']) ?></span>
                <button class="flash-close" type="button" data-flash-close aria-label="بستن">×</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<main class="site-main">
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h4><?= e(Config::get('app.name')) ?></h4>
            <p class="muted"><?= e(Config::get('app.tagline')) ?></p>
        </div>
        <div>
            <h4>دسترسی سریع</h4>
            <ul class="footer-links">
                <li><a href="<?= url('/courses') ?>">دوره‌های آموزشی</a></li>
                <li><a href="<?= url('/guide') ?>">راهنمای استفاده</a></li>
                <li><a href="<?= url('/login') ?>">ورود به حساب</a></li>
            </ul>
        </div>
        <div>
            <h4>ارتباط با ما</h4>
            <ul class="footer-links">
                <li>تلگرام: <?= e(Config::get('app.contact.telegram')) ?></li>
                <li>اینستاگرام: <?= e(Config::get('app.contact.instagram')) ?></li>
                <li>تلفن: <?= e(fa_digits((string) Config::get('app.contact.phone'))) ?></li>
            </ul>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>تمامی حقوق محفوط است — <?= e(fa_digits(date('Y'))) ?></span>
        <span>پخش ویدیوها فقط به‌صورت آنلاین و مخصوص حساب شماست.</span>
    </div>
</footer>

<div class="sidebar-backdrop" data-sidebar-backdrop></div>
<script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>