<?php
/**
 * Admin help page — /admin/help
 * Data: $title
 */
?>
<div class="page-head">
    <div>
        <h1>راهنمای مدیریت</h1>
        <p class="muted">هر چیزی که برای ادارهٔ آکادمی لازم دارید، قدم به قدم.</p>
    </div>
    <div class="head-actions">
        <a class="btn btn-outline btn-sm" href="<?= e(url('/admin')) ?>">بازگشت به داشبورد</a>
    </div>
</div>

<div class="card note note-danger">
    <b>مهم‌ترین کار اول:</b>
    اگر هنوز رمز پیش‌فرض <code dir="ltr">admin1234</code> را عوض نکرده‌اید، الان از طریق
    <a href="<?= e(url('/admin/profile')) ?>">پروفایل مدیر</a> آن را تغییر دهید.
</div>

<div class="guide-progress" data-guide-progress><span class="guide-progress-bar"></span></div>

<div class="guide-steps" data-guide>

    <div class="guide-step is-open" data-guide-step>
        <button type="button" class="guide-head" data-guide-toggle>
            <span class="guide-num">۱</span>
            <span class="guide-title">ساخت دوره و دروس</span>
            <span class="guide-arrow">&#8250;</span>
        </button>
        <div class="guide-body">
            <ol class="mini-steps">
                <li>منوی <b>دوره‌ها</b> و سپس <b>دورهٔ جدید</b>.</li>
                <li>عنوان، توضیح کوتاه، توضیح کامل، قیمت نمایشی و تصویر کاور را پر کنید.</li>
                <li>وضعیت را در ابتدا روی <b>پیش‌نویس</b> بگذارید.</li>
                <li>پس از ذخیره، روی <b>دروس</b> بزنید و ویدیوها را اضافه کنید.</li>
                <li>در پایان وضعیت را به <b>منتشرشده</b> تغییر دهید.</li>
            </ol>
            <div class="guide-anim">
                <span class="pulse-dot"></span>
                <span>فایل‌های سنگین را با FTP در پوشهٔ <code dir="ltr">storage/videos</code> بگذارید و فقط نام فایل را در فرم درس بنویسید.</span>
            </div>
        </div>
    </div>

    <div class="guide-step" data-guide-step>
        <button type="button" class="guide-head" data-guide-toggle>
            <span class="guide-num">۲</span>
            <span class="guide-title">کاربران</span>
            <span class="guide-arrow">&#8250;</span>
        </button>
        <div class="guide-body">
            <ul class="check-list">
                <li>جستجو بر اساس نام کاربری یا نام و نام خانوادگی</li>
                <li>ساخت دستی کاربر برای هنرجویی که خودش ثبت‌نام نکرده</li>
                <li>تنطیم رمز جدید وقتی کاربر رمزش را فراموش کرده است</li>
                <li>غیرفعال کردن حساب به جای حذف آن</li>
            </ul>
        </div>
    </div>

    <div class="guide-step" data-guide-step>
        <button type="button" class="guide-head" data-guide-toggle>
            <span class="guide-num">۳</span>
            <span class="guide-title">فعال‌سازی دسترسی</span>
            <span class="guide-arrow">&#8250;</span>
        </button>
        <div class="guide-body">
            <ol class="mini-steps">
                <li>منوی <b>دسترسی‌ها</b>.</li>
                <li>کاربر و دوره را انتخاب کنید.</li>
                <li>یا <b>دسترسی نامحدود</b> را تیک بزنید، یا تاریخ شروع/پایان یا مدت روزانه را وارد کنید.</li>
                <li>دکمهٔ <b>فعال‌سازی دسترسی</b> را بزنید.</li>
            </ol>
            <p class="tiny muted">اگر دسترسی قبلی وجود داشته باشد، همان رکورد به‌روز می‌شود و رکورد تکراری ساخته نمی‌شود.</p>
        </div>
    </div>

    <div class="guide-step" data-guide-step>
        <button type="button" class="guide-head" data-guide-toggle>
            <span class="guide-num">۴</span>
            <span class="guide-title">انقضا، تمدید و لغو</span>
            <span class="guide-arrow">&#8250;</span>
        </button>
        <div class="guide-body">
            <ul class="check-list">
                <li>دسترسی مدت‌دار در تاریخ پایان، خودبه‌خود بسته می‌شود</li>
                <li>دکمهٔ <b>تمدید</b> روزهای دلخواه را به تاریخ پایان اضافه می‌کند</li>
                <li>دکمهٔ <b>لغو</b> دسترسی را فوری خاموش می‌کند، بدون پاک شدن تاریخ‌ها</li>
                <li>داشبورد، دسترسی‌های نزدیک به انقضا را به شما یادآوری می‌کند</li>
            </ul>
        </div>
    </div>

    <div class="guide-step" data-guide-step>
        <button type="button" class="guide-head" data-guide-toggle>
            <span class="guide-num">۵</span>
            <span class="guide-title">امنیت حساب مدیر</span>
            <span class="guide-arrow">&#8250;</span>
        </button>
        <div class="guide-body">
            <ul class="check-list">
                <li>تغییر نام کاربری یا رمز، فقط با ورود رمز فعلی ممکن است</li>
                <li>پس از تغییر رمز، نشست برای امنیت بازسازی می‌شود</li>
                <li>دسترسی پنل مدیریت را با هیچ‌کس به اشتراک نگذارید</li>
                <li>از دیتابیس و پوشهٔ ویدیوها نسخهٔ پشتیبان دوره‌ای بگیرید</li>
            </ul>
        </div>
    </div>

</div>

<div class="card mt-lg">
    <h3>مشکلات رایج</h3>
    <details class="faq-item">
        <summary>کاربر می‌گوید دوره را در پنل نمی‌بیند</summary>
        <p>مطمئن شوید دسترسی روی همان نام کاربری ثبت شده، وضعیت دسترسی «فعال» است و دوره هم «منتشرشده» است.</p>
    </details>
    <details class="faq-item">
        <summary>ویدیو پخش نمی‌شود</summary>
        <p>نام فایل درس باید دقیقاً با نام فایل موجود در <code dir="ltr">storage/videos</code> یکسان باشد (حروف بزرگ و کوچک مهم است).</p>
    </details>
    <details class="faq-item">
        <summary>آپلود فایل بزرگ خطا می‌دهد</summary>
        <p>مقادیر <code dir="ltr">upload_max_filesize</code> و <code dir="ltr">post_max_size</code> را در php.ini افزایش دهید یا فایل را با FTP منتقل کنید.</p>
    </details>
    <details class="faq-item">
        <summary>کاور دوره نمایش داده نمی‌شود</summary>
        <p>دسترسی نوشتن پوشهٔ <code dir="ltr">public/uploads/courses</code> را بررسی کنید.</p>
    </details>
</div>