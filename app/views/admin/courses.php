<?php
/** @var array $courses */
/** @var string $search */
$statusMeta = [
    'published' => ['منتشرشده', 'ok'],
    'draft'     => ['پیش‌نویس', 'warn'],
    'archived'  => ['بایگانی', 'muted'],
];
?>
<div class="page-head">
    <div>
        <h1>مدیریت دوره‌ها</h1>
        <p class="muted">قیمت فقط جنبهٔ نمایشی دارد و هیچ پرداختی در سایت انجام نمی‌شود.</p>
    </div>
    <a class="btn btn-primary btn-sm" href="<?= url('/admin/courses/create') ?>">دورهٔ جدید</a>
</div>

<form class="filter-bar" method="get" action="<?= url('/admin/courses') ?>">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="جستجوی عنوان یا نشانی دوره...">
    <button class="btn btn-primary btn-sm" type="submit">جستجو</button>
    <a class="btn btn-ghost btn-sm" href="<?= url('/admin/courses') ?>">پاک‌کردن</a>
</form>

<?php if ($courses === []): ?>
    <div class="empty-state">دوره‌ای موجود نیست. اولین دوره را بسازید.</div>
<?php else: ?>
    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>وضعیت</th>
                        <th>دروس</th>
                        <th>مدت</th>
                        <th>قیمت نمایشی</th>
                        <th>دسترسی‌ها</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <?php [$statusLabel, $statusTone] = $statusMeta[$course['status']] ?? $statusMeta['draft']; ?>
                        <tr>
                            <td>
                                <strong><?= e($course['title']) ?></strong>
                                <small class="muted" dir="ltr">/<?= e($course['slug']) ?></small>
                            </td>
                            <td><span class="tag tag-<?= e($statusTone) ?>"><?= e($statusLabel) ?></span></td>
                            <td><?= e(fa_digits((string) $course['lesson_count'])) ?></td>
                            <td><?= e(duration_label((int) $course['total_minutes'])) ?></td>
                            <td><?= e(money($course['price_display'])) ?></td>
                            <td><?= e(fa_digits((string) $course['access_count'])) ?></td>
                            <td class="row-actions">
                                <a class="btn btn-outline btn-xs" href="<?= url('/admin/courses/' . (int) $course['id'] . '/edit') ?>">ویرایش</a>
                                <a class="btn btn-ghost btn-xs" href="<?= url('/admin/courses/' . (int) $course['id'] . '/lessons') ?>">دروس</a>
                                <form method="post" action="<?= url('/admin/courses/' . (int) $course['id'] . '/delete') ?>"
                                      data-confirm="دوره، دروس و دسترسی‌های مربوط حذف می‌شود. مطمئنید؟">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-danger btn-xs" type="submit">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>