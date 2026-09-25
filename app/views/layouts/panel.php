<?php
/** @var string $content */
$panelUser = Auth::user();
$path      = current_path();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <script>try{document.documentElement.setAttribute('data-theme', localStorage.getItem('pa_theme') || 'dark');}catch(e){}</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0c0c0e">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title ?? 'پنل کاربری') ?> | <?= e(Config::get('app.name')) ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="app panel-app" data-auth-user="<?= e($panelUser['username'] ?? '') ?>" data-auth-token="<?= e($panelUser ? Auth::userRememberToken() : '') ?>">

<header class="app-bar">
    <div class="app-bar-inner">
        <button class="nav-toggle" type="button" data-sidebar-toggle aria-label="منو" aria-expanded="false">
            <svg class="nav-toggle-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path class="bar bar-1" d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path class="bar bar-2" d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path class="bar bar-3" d="M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <a class="brand brand-sm" href="<?= url('/panel') ?>">
            <span class="brand-mark">P</span>
            <strong>پنل کاربری</strong>
        </a>
        <button class="theme-toggle" type="button" data-theme-toggle aria-label="تغییر حالت روشن و تاریک" aria-pressed="false">
            <span class="icon-moon" aria-hidden="true">☾</span>
            <span class="icon-sun" aria-hidden="true">☀</span>
        </button>
        <div class="app-bar-user">
            <span class="chip"><?= e($panelUser['username'] ?? '') ?></span>
            <form class="inline-form" method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-ghost btn-sm" type="submit">خروج</button>
            </form>
        </div>
    </div>
</header>

<div class="app-shell">
    <aside class="sidebar" data-sidebar>
        <nav class="sidebar-nav">
            <a class="sidebar-link <?= $path === '/' ? 'is-active' : '' ?>" href="<?= url('/') ?>">
                <span class="sidebar-icon">⌂</span> صفحهٔ اصلی
            </a>
            <a class="sidebar-link <?= $path === '/panel' ? 'is-active' : '' ?>" href="<?= url('/panel') ?>">
                <span class="sidebar-icon">▣</span> داشبورد
            </a>
            <a class="sidebar-link <?= $path === '/panel/profile' ? 'is-active' : '' ?>" href="<?= url('/panel/profile') ?>">
                <span class="sidebar-icon">◈</span> پروفایل و رمز
            </a>
            <a class="sidebar-link" href="<?= url('/courses') ?>">
                <span class="sidebar-icon">◇</span> همهٔ دوره‌ها
            </a>
            <a class="sidebar-link" href="<?= url('/guide') ?>">
                <span class="sidebar-icon">○</span> راهنما
            </a>
            <?php if (($panelUser['role'] ?? '') === 'admin'): ?>
                <a class="sidebar-link" href="<?= url('/admin') ?>">
                    <span class="sidebar-icon">✦</span> پنل مدیریت
                </a>
            <?php endif; ?>
            <form class="sidebar-logout-form" method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button class="sidebar-link sidebar-link-btn" type="submit">
                    <span class="sidebar-icon">⏻</span> خروج
                </button>
            </form>
        </nav>

        <div class="sidebar-note">
            برای فعال‌سازی یا تمدید دوره با مدیر آکادمی در تماس باشید.
        </div>
    </aside>

    <main class="app-content">
        <?php $messages = flashes(); ?>
        <?php foreach ($messages as $message): ?>
            <div class="flash flash-<?= e($message['type']) ?>" data-flash>
                <span><?= e($message['message']) ?></span>
                <button class="flash-close" type="button" data-flash-close aria-label="بستن">×</button>
            </div>
        <?php endforeach; ?>

        <?= $content ?>
    </main>
</div>

<div class="sidebar-backdrop" data-sidebar-backdrop></div>
<script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>