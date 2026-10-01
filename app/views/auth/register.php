<?php
$oldUsername = (string) ($_SESSION['_old_username'] ?? '');
unset($_SESSION['_old_username']);
?>
<section class="section auth-section">
    <div class="container auth-wrap">
        <div class="card auth-card reveal">
            <h1>ساخت حساب کاربری</h1>
            <p class="muted">ثبت‌نام سریع با شماره موبایل و رمز عبور.</p>

            <form method="post" action="<?= url('/register') ?>" data-validate novalidate>
                <?= csrf_field() ?>

                <div class="field">
                    <label for="username">نام کاربری</label>
                    <input id="username" name="username" type="text" value="<?= e($oldUsername) ?>"
                           dir="ltr" autocomplete="username" required minlength="4" maxlength="30"
                           pattern="[A-Za-z0-9_.]{4,30}">
                    <small class="field-hint">حروف لاتین، اعداد، نقطه یا زیرخط — ۴ تا ۳۰ کاراکتر</small>
                    <small class="field-error" data-error></small>
                </div>

                                <div class="field">
                    <label for="phone">شماره موبایل</label>
                    <input id="phone" name="phone" type="text" inputmode="numeric" value="<?= e($_SESSION['_old_phone'] ?? '') ?>"
                           dir="ltr" autocomplete="tel" required maxlength="11" placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                           pattern="09[0-9]{9}">
                    <small class="field-hint">شماره موبایل ۱۱ رقمی معتبر با ۰۹</small>
                    <small class="field-error" data-error></small>
                </div>
                <?php unset($_SESSION['_old_phone']); ?>
<div class="field">
                    <label for="password">رمز عبور</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" dir="ltr"
                               autocomplete="new-password" required minlength="8" data-strength>
                        <button class="password-toggle" type="button" data-password-toggle aria-label="نمایش رمز">◉</button>
                    </div>
                    <div class="strength" data-strength-bar aria-hidden="true"><span></span></div>
                    <small class="field-hint">حداقل ۸ کاراکتر، شامل حداقل یک حرف و یک عدد</small>
                    <small class="field-error" data-error></small>
                </div>

                <div class="field">
                    <label for="password_confirm">تکرار رمز عبور</label>
                    <input id="password_confirm" name="password_confirm" type="password" dir="ltr"
                           autocomplete="new-password" required minlength="8" data-match="password">
                    <small class="field-error" data-error></small>
                </div>

                <button class="btn btn-primary btn-block" type="submit">ثبت‌نام</button>
            </form>

            <div class="auth-foot">
                <span>قبلاً ثبت‌نام کرده‌اید؟</span>
                <a href="<?= url('/login') ?>">ورود</a>
            </div>
        </div>

        <div class="auth-aside reveal">
            <h2>پس از ثبت‌نام چه می‌شود؟</h2>
            <ol class="mini-steps">
                <li>نام کاربری خود را به مدیر آکادمی اعلام می‌کنید.</li>
                <li>مدیر، دورهٔ مورد نطر را روی حساب شما فعال می‌کند.</li>
                <li>در پنل، دروس را آنلاین تماشا می‌کنید.</li>
            </ol>
        </div>
    </div>
</section>