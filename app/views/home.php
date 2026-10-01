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
<style>
<style>
.hero-art {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-art-frame {
    position: relative;
    width:550px;
    height: 300px;
    aspect-ratio: 16 / 9; 
    max-width: 690px;          /* بزرگ‌تر شدن عکس به اندازه کاملاً متعادل */
    margin-top: 100px;          /* پایین آمدن عکس (نه خیلی زیاد، نه کم) */
    margin-right: -15px;
    aspect-ratio: 16 / 9;
    border-radius: 40px;
    overflow: hidden;
    border: 1px solid #e3ae43;
    box-shadow: 0 10px 25px #dcaa4687;
    background: #0d0d10;
}

/* حل قطعی ریسپانسیو در تبلت و موبایل */
@media (max-width: 768px) {
    .hero-art-frame {
        width:456px;
    height: 300px;
        margin-top: 90px;      /* کم کردن فاصله خالی در موبایل */
        margin-right: -20px;       /* جلوگیری قطعی از اسکرول افقی در گوشی */
        border-radius: 24px;   /* گوشه‌های نرم‌تر برای کادر کوچک گوشی */
    }
}

.hero-art-frame img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    object-position: center;
}

/* کپسول شیشه‌ای و بسیار ظریف - بدون اشغال فضای عکس */
.hero-art-badge {
    position: absolute;
    bottom: 14px !important;
    left: 14px !important;         /* سمت چپ: روی قفسه‌ها، نه روی آرایشگر */
    top: auto !important;          /* رفع قطعی کش آمدن عمودی */
    right: auto !important;
    height: auto !important;       /* دقیقاً اندازه محتوا بدون فضای خالی */
    width: auto !important;
    max-width: calc(100% - 28px);
    
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: rgba(12, 12, 15, 0.85);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(212, 175, 55, 0.35);
    border-radius: 50px;          /* فرم کپسولی مدرن */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
    z-index: 5;
}

.hero-art-badge .badge-icon {
    width: 22px;
    height: 22px;
    min-width: 22px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.15);
    color: #e5b95f;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-art-badge .badge-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #ffffff;
    white-space: nowrap;
}

.hero-art-badge .badge-tag {
    font-size: 0.7rem;
    color: #d4af37;
    background: rgba(212, 175, 55, 0.12);
    padding: 2px 7px;
    border-radius: 12px;
    white-space: nowrap;
}

.hero-inner.has-art {
    align-items: center;
}

@media (max-width: 576px) {
    .hero-art-badge {
        bottom: 10px !important;
        left: 10px !important;
        padding: 5px 10px;
    }
    .hero-art-badge .badge-title {
        font-size: 0.72rem;
    }
    .hero-art-badge .badge-tag {
        font-size: 0.65rem;
    }
}
</style>
</style>
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
    <div class="hero-art-frame">
        <img
            src="https://s7.uplod.ir/i/01230/3j8jmqsnzjn9.png"
            alt="آموزش تخصصی آرایشگری در آکادمی پرهام"
            loading="lazy"
        >

        <!-- کپسول ظریف و جمع‌وجور روی فضای خالی عکس -->
        <div class="hero-art-badge">
            <div class="badge-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <span class="badge-title">آموزش تخصصی آرایشگری و گریم</span>
            <span class="badge-tag">مدرک بین‌المللی</span>
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
                <img data-fallback src="https://s7.uplod.ir/i/01230/no6pqlp84b34.png" alt="فضای مجهز آموزش آرایشگری در آکادمی پرهام" loading="lazy">
                <!-- <div class="gallery-item-placeholder" data-fallback-placeholder>آکادمی پرهام</div> -->
                <span class="gallery-item-label">فضای مجهز آموزش</span>
            </div>
            <div class="gallery-item reveal">
                <img data-fallback src="https://s7.uplod.ir/i/01230/ycn2w22xra0q.png" alt="سالن اختصاصی آموزش استایل و گریم مو" loading="lazy">
                <!-- <div class="gallery-item-placeholder" data-fallback-placeholder>آکادمی پرهام</div> -->
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
