<?php
/** @var array $courses */
/** @var string $search */
$stateLabels = [
    'active'  => ['دسترسی فعال', 'ok'],
    'pending' => ['شروع نشده', 'warn'],
    'expired' => ['منقضی شده', 'danger'],
    'revoked' => ['غیرفعال', 'danger'],
    'admin'   => ['دسترسی مدیر', 'ok'],
    'none'    => ['فعال نشده', 'muted'],
    'guest'   => ['ورود لازم است', 'muted'],
];
?>
<section class="section">
    <div class="container">
        <div class="page-head">
            <div>
                <h1>دوره‌های آموزشی</h1>
                <p class="muted">پکیج مورد نطر را انتخاب کنید؛ فعال‌سازی دسترسی توسط مدیر آکادمی انجام می‌شود.</p>
            </div>

            <form class="search-form" method="get" action="<?= url('/courses') ?>">
                <input type="search" name="q" value="<?= e($search) ?>" placeholder="جستجوی دوره...">
                <button class="btn btn-primary btn-sm" type="submit">جستجو</button>
            </form>
        </div>

        <?php if ($courses === []): ?>
            <div class="empty-state">دوره‌ای با این مشخصات پیدا نشد.</div>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($courses as $course): ?>
                    <?php
                    $state = (string) $course['access']['state'];
                    [$label, $tone] = $stateLabels[$state] ?? $stateLabels['none'];
                    ?>
                    <article class="card course-card reveal">
                        <a class="course-cover" href="<?= url('/course/' . $course['slug']) ?>">
                            <?php if (!empty($course['cover_image'])): ?>
                                <img src="<?= cover_url((string) ($course['cover_image'] ?? '')) ?>"
                                     alt="<?= e($course['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <span class="cover-placeholder">آکادمی پرهام</span>
                            <?php endif; ?>
                            <span class="tag tag-<?= e($tone) ?>"><?= e($label) ?></span>
                        </a>

                        <div class="card-body">
                            <h3><a href="<?= url('/course/' . $course['slug']) ?>"><?= e($course['title']) ?></a></h3>
                            <p class="muted"><?= e(excerpt($course['short_description'], 120)) ?></p>

                            <div class="card-meta">
                                <span><?= e(fa_digits((string) $course['lesson_count'])) ?> درس</span>
                                <span><?= e(duration_label((int) $course['total_minutes'])) ?></span>
                            </div>

                            <div class="card-foot">
                                <span class="price"><?= e(money($course['price_display'])) ?></span>
                                <a class="btn btn-outline btn-sm" href="<?= url('/course/' . $course['slug']) ?>">جزئیات</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>