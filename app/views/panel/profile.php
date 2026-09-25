<?php /** @var array $user */ ?>
<div class="page-head">
    <div>
        <h1>پروفایل من</h1>
        <p class="muted">اطلاعات حساب و تغییر رمز عبور</p>
    </div>
</div>

<div class="grid grid-2">
    <section class="card form-card reveal">
        <h2>اطلاعات حساب</h2>

        <ul class="facts">
            <li><span>نام کاربری</span><strong dir="ltr"><?= e($user['username']) ?></strong></li>
            <li><span>تاریخ عضویت</span><strong><?= e(jalali_date($user['created_at'])) ?></strong></li>
            <li><span>آخرین ورود</span><strong><?= e(jalali_date($user['last_login_at'], true)) ?></strong></li>
        </ul>

        <form method="post" action="<?= url('/panel/profile') ?>" data-validate novalidate>
            <?= csrf_field() ?>

            <div class="field">
                <label for="full_name">نام و نام خانوادگی</label>
                <input id="full_name" name="full_name" type="text" maxlength="120"
                       value="<?= e($user['full_name']) ?>" placeholder="مانند: مریم محمدی">
            </div>

            <button class="btn btn-primary" type="submit">ذخیرهٔ اطلاعات</button>
        </form>
    </section>

    <section class="card form-card reveal">
        <h2>تغییر رمز عبور</h2>
        <p class="muted">برای امنیت بیشتر، وارد کردن رمز فعلی الزامی است.</p>

        <form method="post" action="<?= url('/panel/password') ?>" data-validate novalidate>
            <?= csrf_field() ?>

            <div class="field">
                <label for="current_password">رمز عبور فعلی</label>
                <input id="current_password" name="current_password" type="password" dir="ltr" required>
                <small class="field-error" data-error></small>
            </div>

            <div class="field">
                <label for="new_password">رمز عبور جدید</label>
                <input id="new_password" name="new_password" type="password" dir="ltr"
                       required minlength="8" data-strength>
                <div class="strength" data-strength-bar aria-hidden="true"><span></span></div>
                <small class="field-hint">حداقل ۸ کاراکتر، شامل حرف و عدد</small>
                <small class="field-error" data-error></small>
            </div>

            <div class="field">
                <label for="new_password_confirm">تکرار رمز جدید</label>
                <input id="new_password_confirm" name="new_password_confirm" type="password" dir="ltr"
                       required minlength="8" data-match="new_password">
                <small class="field-error" data-error></small>
            </div>

            <button class="btn btn-primary" type="submit">تغییر رمز عبور</button>
        </form>
    </section>
</div>