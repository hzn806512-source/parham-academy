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
            <?php
            $watchUser = Auth::user() ?? [];
            $hasAccess = !empty($access) && ($access['is_active'] ?? 1) == 1 && (empty($access['end_date']) || strtotime($access['end_date']) >= strtotime('today'));
            $isFreePreview = !empty($lesson['is_free_preview']) && !$hasAccess;
            ?>

            <!-- واترمارک متحرک -->
            <div id="video-watermark" class="video-watermark" aria-hidden="true">
                <?= e($watchUser['username'] ?? '') ?> — <?= e($watchUser['phone'] ?? 'بدون شماره') ?>
            </div>

            <!-- پیام اتمام ۱۵ ثانیه رایگان -->
            <div id="preview-lock-overlay" class="player-overlay" hidden>
                <div style="text-align: center; padding: 20px;">
                    <h3 style="color:#d4af37; margin-bottom: 10px;">مهلت پیش‌نمایش رایگان (۱۵ ثانیه) به پایان رسید</h3>
                    <p style="color:#ddd; margin-bottom: 20px;">برای مشاهدهٔ کامل این درس، دوره را تهیه کنید.</p>
                    <a class="btn btn-primary" href="<?= url('/course/' . $lesson['course_slug']) ?>">مشاهده و خرید دوره</a>
                </div>
            </div>
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
<style>
.player-frame {
    position: relative;
    overflow: hidden;
}
.player-frame:fullscreen, .player-frame:-webkit-full-screen {
    width: 100vw;
    height: 100vh;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.video-watermark {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 2147483647;
    pointer-events: none;
    user-select: none;
    color: rgba(255, 255, 255, 0.28);
    font-size: 0.85rem;
    font-weight: 600;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    transition: top 1.2s ease, left 1.2s ease;
    direction: ltr;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var video = document.getElementById('lesson-player');
    var frame = document.querySelector('.player-frame');
    var watermark = document.getElementById('video-watermark');
    var previewLock = document.getElementById('preview-lock-overlay');
    var isPreview = <?= !empty($isFreePreview) ? 'true' : 'false' ?>;

    function moveWatermark() {
        if (!frame || !watermark) return;
        var maxX = frame.clientWidth - watermark.clientWidth - 25;
        var maxY = frame.clientHeight - watermark.clientHeight - 40;
        if (maxX > 20 && maxY > 20) {
            var randX = Math.floor(Math.random() * (maxX - 15)) + 15;
            var randY = Math.floor(Math.random() * (maxY - 15)) + 15;
            watermark.style.left = randX + 'px';
            watermark.style.top = randY + 'px';
        }
    }
    setInterval(moveWatermark, 4000);

    if (video && frame) {
        video.requestFullscreen = function() {
            if (frame.requestFullscreen) return frame.requestFullscreen();
            if (frame.webkitRequestFullscreen) return frame.webkitRequestFullscreen();
        };
        video.webkitRequestFullscreen = video.requestFullscreen;
    }

    if (isPreview && video) {
        video.addEventListener('timeupdate', function() {
            if (video.currentTime >= 15) {
                video.pause();
                video.currentTime = 15;
                if (document.fullscreenElement) {
                    document.exitFullscreen().catch(function(){});
                }
                if (previewLock) {
                    previewLock.removeAttribute('hidden');
                }
            }
        });
        video.addEventListener('seeking', function() {
            if (video.currentTime >= 15) {
                video.currentTime = 0;
                video.pause();
            }
        });
    }
});
</script>