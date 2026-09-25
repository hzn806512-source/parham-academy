<section class="section guide-page">
    <div class="container">
        <div class="page-head">
            <div>
                <h1>راهنمای استفاده از سایت</h1>
                <p class="muted">همهٔ مراحل در چهار قدم ساده — روی هر قدم بزنید تا جزئیات باز شود.</p>
            </div>
        </div>

        <div class="guide-progress" data-guide-progress aria-hidden="true">
            <span class="guide-progress-bar"></span>
        </div>

        <div class="guide-steps" data-guide>
            <article class="guide-step reveal is-open" data-guide-step>
                <button class="guide-head" type="button" data-guide-toggle>
                    <span class="guide-num">۱</span>
                    <span class="guide-title">ساخت حساب کاربری</span>
                    <span class="guide-arrow">‹</span>
                </button>
                <div class="guide-body">
                    <p>از منوی بالای سایت روی <b>ثبت‌نام</b> بزنید. فقط سه مورد لازم است:</p>
                    <ul class="check-list">
                        <li>نام کاربری (حروف لاتین یا اعداد، حداقل ۴ کاراکتر)</li>
                        <li>رمز عبور (حداقل ۸ کاراکتر، شامل حرف و عدد)</li>
                        <li>تکرار رمز عبور</li>
                    </ul>
                    <p class="muted">شماره تلفن و پیامک لازم نیست. پس از ثبت‌نام، به‌صورت خودکار وارد پنل می‌شوید.</p>
                    <a class="btn btn-primary btn-sm" href="<?= url('/register') ?>">رفتن به صفحهٔ ثبت‌نام</a>
                </div>
            </article>

            <article class="guide-step reveal" data-guide-step>
                <button class="guide-head" type="button" data-guide-toggle>
                    <span class="guide-num">۲</span>
                    <span class="guide-title">فعال‌سازی دوره توسط مدیر</span>
                    <span class="guide-arrow">‹</span>
                </button>
                <div class="guide-body">
                    <p>در این سایت <b>پرداخت آنلاین وجود ندارد</b>. پس از هماهنگی با مدیر آکادمی، دوره روی همین نام کاربری شما فعال می‌شود.</p>
                    <div class="guide-anim">
                        <span class="pulse-dot"></span>
                        <span>نام کاربری خود را دقیق به مدیر اعلام کنید.</span>
                    </div>
                    <p>دسترسی می‌تواند <b>مدت‌دار</b> (مثلاً ۳۰ روزه) یا <b>نامحدود</b> باشد.</p>
                </div>
            </article>

            <article class="guide-step reveal" data-guide-step>
                <button class="guide-head" type="button" data-guide-toggle>
                    <span class="guide-num">۳</span>
                    <span class="guide-title">تماشای ویدیوها در پنل</span>
                    <span class="guide-arrow">‹</span>
                </button>
                <div class="guide-body">
                    <p>پس از ورود، به <b>پنل من</b> بروید. هر دورهٔ فعال، همراه با تعداد روز باقی‌مانده نمایش داده می‌شود.</p>
                    <ul class="check-list">
                        <li>روی دوره بزنید تا فهرست دروس باز شود.</li>
                        <li>روی درس مورد نطر بزنید؛ پخش بلافاصله شروع می‌شود.</li>
                        <li>پخش فقط آنلاین است و لینک دانلود وجود ندارد.</li>
                    </ul>
                    <p class="muted">برای تماشای روان، اتصال اینترنت پایدار توصیه می‌شود.</p>
                </div>
            </article>

            <article class="guide-step reveal" data-guide-step>
                <button class="guide-head" type="button" data-guide-toggle>
                    <span class="guide-num">۴</span>
                    <span class="guide-title">مدیریت حساب و رمز عبور</span>
                    <span class="guide-arrow">‹</span>
                </button>
                <div class="guide-body">
                    <p>در مسیر <b>پنل ← پروفایل</b> می‌توانید نام خود را وارد کنید و رمز عبور را تغییر دهید (رمز فعلی + رمز جدید + تکرار).</p>
                    <div class="guide-anim">
                        <span class="pulse-dot"></span>
                        <span>رمز خود را در اختیار کسی قرار ندهید.</span>
                    </div>
                    <p>اگر پیام <b>«دسترسی شما منقضی شده است»</b> را دیدید، کافی است برای تمدید با مدیر آکادمی تماس بگیرید؛ دورهٔ قبلی شما از بین نمی‌رود.</p>
                </div>
            </article>
        </div>

        <div class="faq">
            <h2>سوال‌های پرتکرار</h2>

            <details class="faq-item">
                <summary>رمز عبورم را فراموش کرده‌ام؛ چه کنم؟</summary>
                <p>با مدیر آکادمی تماس بگیرید؛ رمز جدید برای شما تنطیم می‌شود و دسترسی‌هایتان دست نمی‌خورد.</p>
            </details>

            <details class="faq-item">
                <summary>می‌توانم ویدیوها را دانلود کنم؟</summary>
                <p>خیر. برای حفاطت از محتوا، پخش فقط آنلاین و مخصوص حساب شماست.</p>
            </details>

            <details class="faq-item">
                <summary>با یک حساب روی چند دستگاه می‌توانم وارد شوم؟</summary>
                <p>خیر فقط با یک دستگاه میتوانید به حسابتون وارد بشین ولی اگه از این حساب موجود در این دستگاه خارج شوید میتوانید که روی یه دستگاه دیگه هم به این حسابتون وارد شوید</p>
            </details>

            <details class="faq-item">
                <summary>دوره‌ای خریده‌ام ولی در پنل نمی‌بینم.</summary>
                <p>بعد اینکه ادمین سایت بهتون گفت که دسترسی رو براتون باز کردم سایت رو در قسمت داشبورد کاربری رفرشت کنید </p>
            </details>
        </div>
    </div>
</section>