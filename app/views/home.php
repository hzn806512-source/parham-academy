<?php
/** @var array $featured */
/** @var array $stats */
$homeUser = Auth::user();
?>
<!-- SEO Schema Markup -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "آکادمی پرهام",
  "alternateName": "Parham Academy",
  "url": "https://parham-academy.ir",
  "logo": "https://parham-academy.ir/public/assets/images/home/hero.jpg",
  "description": "مرجع آموزش تخصصی آرایشگری، استایل مو و گریم حرفه‌ای با مدرک معتبر و دوره‌های ویدیویی پروژه‌محور.",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "تهران",
    "addressCountry": "IR"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+98-21-22334455",
    "contactType": "customer support"
  },
  "sameAs": [
    "https://www.instagram.com/parham.academy",
    "https://t.me/parham_academy"
  ]
}
</script>

<section class="hero">
    <div class="hero-glow" aria-hidden="true"></div>
    <div class="container hero-inner has-art">
        <div class="hero-copy reveal">
            <span class="kicker">آکادمی پرهام — مرجع تخصصی آموزش آرایشگری</span>
            <h1>حرفه‌ای شدن در آرایشگری، <span class="gold-text">قدم به قدم و عملی</span></h1>
            <p>
                دوره‌های آکادمی پرهام کاملاً ویدیویی و پروژه‌محور است. آموزش تخصصی کوتاهی مو، گریم داماد، استایل مو و اصلاح صورت با مجوز رسمی و دسترسی اختصاصی روی حساب کاربری شما.
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary btn-lg" href="<?= url('/courses') ?>">مشاهدهٔ دوره‌های آموزشی</a>
                <?php if ($homeUser === null): ?>
                    <a class="btn btn-outline btn-lg" href="<?= url('/register') ?>">ساخت حساب کاربری رایگان</a>
                <?php else: ?>
                    <a class="btn btn-outline btn-lg" href="<?= url('/panel') ?>">ورود به پنل کاربری</a>
                <?php endif; ?>
            </div>

            <div class="hero-stats">
                <div><strong><?= e(fa_digits((string) $stats['courses'])) ?></strong><span>دورهٔ تخصصی</span></div>
                <div><strong><?= e(fa_digits((string) $stats['lessons'])) ?></strong><span>درس ویدیویی</span></div>
                <div><strong><?= e(duration_label((int) $stats['minutes'])) ?></strong><span>محتوای آموزشی</span></div>
                <div><strong><?= e(fa_digits((string) $stats['students'])) ?></strong><span>هنرجوی فعال</span></div>
            </div>
        </div>

        <div class="hero-art reveal">
            <div class="hero-art-badge">آموزش حرفه‌ای آرایشگری و گریم با مدرک معتبر</div>
            <div class="hero-art-frame">
                <img data-fallback src="<?= asset('images/home/hero.jpg') ?>" alt="آموزش تخصصی آرایشگری در آکادمی پرهام" loading="lazy">
                <div class="hero-art-placeholder" data-fallback-placeholder>آکادمی پرهام</div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>دوره‌های ویژه و پرطرفدار آرایشگری</h2>
            <a class="link-more" href="<?= url('/courses') ?>">همهٔ دوره‌ها</a>
        </div>

        <?php if ($featured === []): ?>
            <div class="empty-state">هنوز دوره‌ای منتشر نشده است.</div>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($featured as $course): ?>
                    <article class="card course-card reveal">
                        <a class="course-cover" href="<?= url('/course/' . $course['slug']) ?>">
                            <?php if (!empty($course['cover_image'])): ?>
                                <img src="<?= cover_url((string) ($course['cover_image'] ?? '')) ?>"
                                     alt="<?= e($course['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <span class="cover-placeholder">آکادمی پرهام</span>
                            <?php endif; ?>
                        </a>
                        <div class="card-body">
                            <h3><a href="<?= url('/course/' . $course['slug']) ?>"><?= e($course['title']) ?></a></h3>
                            <p class="muted"><?= e(excerpt($course['short_description'], 110)) ?></p>
                            <div class="card-meta">
                                <span><?= e(fa_digits((string) $course['lesson_count'])) ?> درس ویدیویی</span>
                                <span class="price"><?= e(money($course['price_display'])) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2>محیط آکادمی و سالن‌های تخصصی آموزش</h2>
        </div>
        <div class="gallery">
            <div class="gallery-item reveal">
                <img data-fallback src="<?= asset('images/home/gallery-1.jpg') ?>" alt="فضای مجهز آموزش آرایشگری در آکادمی پرهام" loading="lazy">
                <div class="gallery-item-placeholder" data-fallback-placeholder>آکادمی پرهام</div>
                <span class="gallery-item-label">فضای مجهز آموزش</span>
            </div>
            <div class="gallery-item reveal">
                <img data-fallback src="<?= asset('images/home/gallery-2.jpg') ?>" alt="سالن اختصاصی آموزش استایل و گریم مو" loading="lazy">
                <div class="gallery-item-placeholder" data-fallback-placeholder>آکادمی پرهام</div>
                <span class="gallery-item-label">سالن تخصصی گریم</span>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>مزایا و روند یادگیری در آکادمی پرهام چیست؟</h2>
        </div>

        <ol class="steps">
            <li class="step reveal">
                <span class="step-num">۱</span>
                <h4>ثبت‌نام سریع</h4>
                <p>ساخت حساب کاربری با نام کاربری و رمز عبور امن در کمتر از یک دقیقه.</p>
            </li>
            <li class="step reveal">
                <span class="step-num">۲</span>
                <h4>انتخاب دوره تخصصی</h4>
                <p>انتخاب از میان بهترین پکیج‌های آموزشی کوتاهی مو، گریم داماد و استایل مو.</p>
            </li>
            <li class="step reveal">
                <span class="step-num">۳</span>
                <h4>فعال‌سازی اختصاصی</h4>
                <p>فعال‌سازی سریع دوره‌ها روی پنل کاربری شما توسط تیم پشتیبانی آکادمی.</p>
            </li>
            <li class="step reveal">
                <span class="step-num">۴</span>
                <h4>پخش آنلاین و امن</h4>
                <p>تماشای ویدیوهای باکیفیت آموزشی بدون نیاز به دانلود و محافظت‌شده در پنل.</p>
            </li>
        </ol>

        <div class="cta-box reveal">
            <div>
                <h3>آمادهٔ ورود به بازار کار حرفه‌ای آرایشگری هستید؟</h3>
                <p class="muted">همین حالا حساب کاربری خود را بسازید و مسیر حرفه‌ای خود را شروع کنید.</p>
            </div>
            <div class="cta-actions">
                <a class="btn btn-primary" href="<?= url('/register') ?>">ثبت‌نام رایگان</a>
                <a class="btn btn-ghost" href="<?= url('/guide') ?>">راهنمای هنرجویان</a>
            </div>
        </div>
    </div>
</section>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
