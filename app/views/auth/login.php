<section class="section auth-section">
    <div class="container auth-wrap">
        <div class="card auth-card reveal">
            <h1>ورود به حساب کاربری</h1>
            <p class="muted">نام کاربری و رمز عبور خود را وارد کنید.</p>

            <form method="post" action="<?= url('/login') ?>" data-validate novalidate>
                <?= csrf_field() ?>

                <div class="field">
                    <label for="username">نام کاربری</label>
                    <input id="username" name="username" type="text" autocomplete="username"
                           dir="ltr" required minlength="4" maxlength="30">
                    <small class="field-error" data-error></small>
                </div>

                <div class="field">
                    <label for="password">رمز عبور</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" autocomplete="current-password"
                               dir="ltr" required minlength="6">
                        <button class="password-toggle" type="button" data-password-toggle aria-label="نمایش رمز">◉</button>
                    </div>
                    <small class="field-error" data-error></small>
                </div>

                <button class="btn btn-primary btn-block" type="submit">ورود</button>
            </form>

            <div class="auth-foot">
                <span>حساب کاربری ندارید؟</span>
                <a href="<?= url('/register') ?>">ثبت‌نام کنید</a>
            </div>

            <p class="tiny muted">
                رمز خود را فراموش کرده‌اید؟ برای بازیابی با مدیر آکادمی تماس بگیرید.
            </p>
        </div>

        <div class="auth-aside reveal">
            <h2>پخش امن، مخصوص حساب شما</h2>
            <ul class="check-list">
                <li>دوره‌های فعال شما در پنل نمایش داده می‌شوند.</li>
                <li>تعداد روزهای باقی‌مانده را می‌بینید.</li>
                <li>پخش ویدیو فقط آنلاین و بدون لینک دانلود است.</li>
            </ul>
        </div>
    </div>
</section>