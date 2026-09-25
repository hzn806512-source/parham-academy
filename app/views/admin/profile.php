<?php /** @var array $admin */ ?>
<div class="page-head">
    <div>
        <h1>تنطیمات حساب مدیر</h1>
        <p class="muted">تغییر نام کاربری یا رمز عبور — وارد کردن رمز فعلی الزامی است</p>
    </div>
</div>

<div class="grid grid-2">
    <section class="card form-card highlight-card">
        <h2>ویرایش مشخصات ورود</h2>

        <form method="post" action="<?= url('/admin/profile') ?>" data-validate novalidate>
            <?= csrf_field() ?>

            <div class="field">
                <label for="current_password">رمز عبور فعلی <span class="req">*</span></label>
                <input id="current_password" name="current_password" type="password" dir="ltr" required autocomplete="current-password">
                <small class="field-hint">برای هر تغییری در این فرم، وارد کردن رمز فعلی اجباری است</small>
                <small class="field-error" data-error></small>
            </div>

            <div class="field">
                <label for="username">نام کاربری مدیر</label>
                <input id="username" name="username" type="text" dir="ltr" required
                       minlength="4" maxlength="30" pattern="[A-Za-z0-9_.]{4,30}"
                       value="<?= e($admin['username']) ?>">
                <small class="field-hint">توصیه می‌شود نام پیش‌فرض <b dir="ltr">admin</b> را به نامی اختصاصی تغییر دهید</small>
            </div>

            <div class="field">
                <label for="full_name">نام نمایشی</label>
                <input id="full_name" name="full_name" type="text" maxlength="120" value="<?= e($admin['full_name']) ?>">
            </div>

            <hr class="sep">

            <div class="field">
                <label for="new_password">رمز عبور جدید (اختیاری)</label>
                <input id="new_password" name="new_password" type="password" dir="ltr" minlength="8"
                       autocomplete="new-password" data-strength placeholder="خالی بگذارید تا رمز تغییر نکند">
                <div class="strength" data-strength-bar aria-hidden="true"><span></span></div>
                <small class="field-hint">حداقل ۸ کاراکتر، شامل حرف و عدد</small>
            </div>

            <div class="field">
                <label for="new_password_confirm">تکرار رمز جدید</label>
                <input id="new_password_confirm" name="new_password_confirm" type="password" dir="ltr"
                       minlength="8" autocomplete="new-password" data-match="new_password">
                <small class="field-error" data-error></small>
            </div>

            <button class="btn btn-primary" type="submit">ذخیرهٔ تغییرات</button>
        </form>
    </section>

    <section class="card">
        <h2>وضعیت امنیتی حساب</h2>

        <ul class="facts">
            <li><span>نام کاربری فعلی</span><strong dir="ltr"><?= e($admin['username']) ?></strong></li>
            <li><span>آخرین ورود</span><strong><?= e(jalali_date($admin['last_login_at'], true)) ?></strong></li>
            <li><span>تاریخ ساخت حساب</span><strong><?= e(jalali_date($admin['created_at'])) ?></strong></li>
        </ul>

        <?php if (str_starts_with((string) $admin['password_hash'], 'PLAINTEXT:')): ?>
            <div class="note note-danger">
                رمز این حساب هنوز پیش‌فرض و رمزنگاری‌نشده است. لطفاً همین الان رمز جدیدی تنطیم کنید.
            </div>
        <?php else: ?>
            <div class="note note-ok">
                رمز این حساب با bcrypt رمزنگاری شده و وضعیت مناسبی دارد.
            </div>
        <?php endif; ?>

        <ul class="check-list">
            <li>برای هر تغییر، رمز فعلی سمت سرور بازبینی می‌شود.</li>
            <li>پس از تغییر رمز، شناسهٔ نشست بازسازی می‌شود.</li>
            <li>همهٔ تغییرات در جدول activity_log ثبت می‌شود.</li>
        </ul>
    </section>
</div>