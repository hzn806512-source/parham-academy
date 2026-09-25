<section class="section narrow center">
    <div class="error-box reveal">
        <div class="error-code"><?= e(fa_digits((string) ($code ?? 500))) ?></div>
        <h1><?= e($title ?? 'خطا') ?></h1>
        <p class="muted"><?= e($message ?? '') ?></p>
        <a class="btn btn-primary" href="<?= url('/') ?>">بازگشت به صفحهٔ اصلی</a>
    </div>
</section>