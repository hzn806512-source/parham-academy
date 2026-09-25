<?php
/** @var string $content */
$adminUser = Auth::user();
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
    <title><?= e($title ?? 'پنل مدیریت') ?> | <?= e(Config::get('app.name')) ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="app admin-app" data-auth-user="<?= e($adminUser['username'] ?? '') ?>" data-auth-token="<?= e($adminUser ? Auth::userRememberToken() : '') ?>">

<header class="app-bar admin-bar">
    <div class="app-bar-inner">
        <button class="nav-toggle" type="button" data-sidebar-toggle aria-label="منو" aria-expanded="false">
            <svg class="nav-toggle-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path class="bar bar-1" d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path class="bar bar-2" d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path class="bar bar-3" d="M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <a class="brand brand-sm" href="<?= url('/admin') ?>">
            <span class="brand-mark">P</span>
            <strong>پنل مدیریت</strong>
        </a>
        <button class="theme-toggle" type="button" data-theme-toggle aria-label="تغییر حالت روشن و تاریک" aria-pressed="false">
            <span class="icon-moon" aria-hidden="true">☾</span>
            <span class="icon-sun" aria-hidden="true">☀</span>
        </button>
        <div class="app-bar-user">
            <span class="chip chip-danger">مدیر: <?= e($adminUser['username'] ?? '') ?></span>
            <a class="btn btn-ghost btn-sm" href="<?= url('/') ?>">مشاهدهٔ سایت</a>
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
            <a class="sidebar-link <?= $path === '/admin' ? 'is-active' : '' ?>" href="<?= url('/admin') ?>">
                <span class="sidebar-icon">▣</span> داشبورد
            </a>
            <a class="sidebar-link <?= str_starts_with($path, '/admin/users') ? 'is-active' : '' ?>" href="<?= url('/admin/users') ?>">
                <span class="sidebar-icon">◈</span> کاربران
            </a>
            <a class="sidebar-link <?= str_starts_with($path, '/admin/courses') || str_starts_with($path, '/admin/lessons') ? 'is-active' : '' ?>" href="<?= url('/admin/courses') ?>">
                <span class="sidebar-icon">◇</span> دوره‌ها و دروس
            </a>
            <a class="sidebar-link <?= str_starts_with($path, '/admin/access') ? 'is-active' : '' ?>" href="<?= url('/admin/access') ?>">
                <span class="sidebar-icon">✦</span> دسترسی‌ها
            </a>
            <a class="sidebar-link <?= $path === '/admin/profile' ? 'is-active' : '' ?>" href="<?= url('/admin/profile') ?>">
                <span class="sidebar-icon">◐</span> تنطیمات حساب
            </a>
            <a class="sidebar-link <?= $path === '/admin/help' ? 'is-active' : '' ?>" href="<?= url('/admin/help') ?>">
                <span class="sidebar-icon">○</span> راهنمای مدیر
            </a>
        </nav>

        <div class="sidebar-note sidebar-note-danger">
            هر تغییر دسترسی، بلافاصله و بدون نیاز به کار دیگری اعمال می‌شود.
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