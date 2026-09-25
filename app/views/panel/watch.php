<?php
/** @var array $lesson */
/** @var array $playlist */
/** @var array $access */
?>
<div class="page-head">
    <div>
        <nav class="breadcrumb">
            <a href="<?= url('/panel') ?>">پنل</a> <span>/</span>
            <a href="<?= url('/course/' . $lesson['course_slug']) ?>"><?= e($lesson['course_title']) ?></a>
        </nav>
        <h1><?= e($lesson['title']) ?></h1>
    </div>
    <span class="chip"><?= e(duration_label((int) $lesson['duration_minutes'])) ?></span>
</div>

<div class="watch-layout">
    <section class="card player-card">
        <div class="player-frame">
            <video
                id="lesson-player"
                class="player"
                controls
                controlslist="nodownload noplaybackrate"
                disablepictureinpicture
                preload="metadata"
                playsinline
                oncontextmenu="return false;"
                data-player
                data-ticket-url="<?= url('/panel/stream-ticket/' . (int) $lesson['id']) ?>">
                مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
            </video>

            <div class="player-overlay" data-player-error hidden>
                <p>پخش متوقف شد. ممکن است مدت اعتبار پخش تمام شده یا دسترسی شما تغییر کرده باشد.</p>
                <button class="btn btn-primary btn-sm" type="button" data-player-reload>دریافت دوبارهٔ پخش</button>
            </div>
        </div>

        <?php if (!empty($lesson['description'])): ?>
            <div class="prose">
                <p><?= nl2br(e((string) $lesson['description'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="note">
            این لینک پخش، مخصوص حساب و نشست فعلی شما است و پس از مدتی باطل می‌شود؛ به اشتراک‌گذاری آن نیازی نیست و برای دیگران کار نمی‌کند.
        </div>
    </section>

    <aside class="card playlist-card">
        <h3>دروس این دوره</h3>
        <ol class="playlist">
            <?php foreach ($playlist as $item): ?>
                <?php $isCurrent = (int) $item['id'] === (int) $lesson['id']; ?>
                <li class="playlist-item <?= $isCurrent ? 'is-current' : '' ?>">
                    <a href="<?= url('/watch/' . (int) $item['id']) ?>">
                        <span class="playlist-title"><?= e($item['title']) ?></span>
                        <span class="playlist-time"><?= e(duration_label((int) $item['duration_minutes'])) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>
    </aside>
</div>