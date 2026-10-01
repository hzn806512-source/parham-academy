<?php
$otpCode = (string) ($_SESSION['_pending_reg']['otp'] ?? '');
$phone   = (string) ($_SESSION['_pending_reg']['phone'] ?? '');
?>
<section class="section auth-section">
    <div class="container auth-wrap">
        <div class="card auth-card reveal">
            <h1>تایید شماره موبایل</h1>
            <p class="muted">کد تایید ۵ رقمی ارسال‌شده به شماره <strong><?= e($phone) ?></strong> را وارد کنید.</p>

            <form method="post" action="<?= url('/register/verify') ?>" data-validate novalidate>
                <?= csrf_field() ?>

                <div class="field">
                    <label for="otp">کد تایید ۵ رقمی</label>
                    <input id="otp" name="otp" type="text" dir="ltr" inputmode="numeric"
                           required maxlength="5" pattern="[0-9]{5}" placeholder="-----"
                           style="letter-spacing: 8px; font-size: 1.4rem; text-align: center;" autofocus>
                    <small class="field-error" data-error></small>
                </div>

                <button class="btn btn-primary btn-block" type="submit">تایید و ساخت حساب</button>
            </form>

            <div class="auth-foot">
                <a href="<?= url('/register') ?>">ویرایش اطلاعات و شماره</a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var code = "<?= e($otpCode) ?>";
    if (code) {
        alert("پیامک آزمایشی آکادمی پرهام:\nکد تایید هویت شما: " + code);
    }
});
</script>