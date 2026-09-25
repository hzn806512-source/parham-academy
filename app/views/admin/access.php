<?php
/** @var array $rows */
/** @var array $users */
/** @var array $courses */
/** @var string $search */
/** @var int $courseId */
/** @var string $state */
$stateMeta = [
    'active'  => ['فعال', 'ok'],
    'pending' => ['شروع نشده', 'warn'],
    'expired' => ['منقضی شده', 'danger'],
    'revoked' => ['قطع‌شده', 'danger'],
];
?>
<div class="page-head">
    <div>
        <h1>مدیریت دسترسی‌ها</h1>
        <p class="muted">فعال‌سازی دستی دوره برای کاربران — مدت‌دار یا نامحدود</p>
    </div>
</div>

<section class="card form-card highlight-card">
    <h2>دادن یا بروزرسانی دسترسی</h2>
    <p class="muted">اگر برای این کاربر و دوره قبلاً رکوردی وجود داشته باشد، همان رکورد بروز می‌شود.</p>

    <form method="post" action="<?= url('/admin/access/grant') ?>" data-validate novalidate>
        <?= csrf_field() ?>

        <div class="field-row">
            <div class="field grow">
                <label for="user_id">کاربر</label>
                <select id="user_id" name="user_id" required data-searchable>
                    <option value="">— انتخاب کاربر —</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= (int) $user['id'] ?>">
                            <?= e($user['username']) ?><?= $user['full_name'] ? ' — ' . e($user['full_name']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field grow">
                <label for="course_id">دوره</label>
                <select id="course_id" name="course_id" required>
                    <option value="">— انتخاب دوره —</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= (int) $course['id'] ?>"><?= e($course['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label for="start_date">تاریخ شروع</label>
                <input id="start_date" name="start_date" type="date" dir="ltr">
                <small class="field-hint">خالی = از همین الان</small>
            </div>

            <div class="field">
                <label for="end_date">تاریخ پایان</label>
                <input id="end_date" name="end_date" type="date" dir="ltr">
                <small class="field-hint">خالی = بی‌پایان</small>
            </div>

            <div class="field">
                <label for="days">یا مدت به روز</label>
                <input id="days" name="days" type="number" dir="ltr" min="0" max="3650" value="0" placeholder="30">
                <small class="field-hint">اگر تاریخ پایان خالی باشد، از این عدد استفاده می‌شود</small>
            </div>

            <div class="field">
                <label for="is_unlimited">دسترسی نامحدود</label>
                <select id="is_unlimited" name="is_unlimited">
                    <option value="0">خیر — مدت‌دار</option>
                    <option value="1">بله — نامحدود (تاریخ پایان نادیده گرفته می‌شود)</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="note">یادداشت (اختیاری)</label>
            <input id="note" name="note" type="text" maxlength="255" placeholder="مانند: تسویه کامل — دورهٔ پاییز">
        </div>

        <button class="btn btn-primary" type="submit">فعال‌سازی دسترسی</button>
    </form>
</section>

<form class="filter-bar mt-lg" method="get" action="<?= url('/admin/access') ?>">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="جستجوی کاربر...">
    <select name="course">
        <option value="0">همهٔ دوره‌ها</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?= (int) $course['id'] ?>" <?= $courseId === (int) $course['id'] ? 'selected' : '' ?>>
                <?= e($course['title']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="state">
        <option value="">همهٔ وضعیت‌ها</option>
        <option value="active" <?= $state === 'active' ? 'selected' : '' ?>>فعال</option>
        <option value="pending" <?= $state === 'pending' ? 'selected' : '' ?>>شروع نشده</option>
        <option value="expired" <?= $state === 'expired' ? 'selected' : '' ?>>منقضی شده</option>
        <option value="revoked" <?= $state === 'revoked' ? 'selected' : '' ?>>قطع‌شده</option>
    </select>
    <button class="btn btn-primary btn-sm" type="submit">فیلتر</button>
    <a class="btn btn-ghost btn-sm" href="<?= url('/admin/access') ?>">پاک‌کردن</a>
</form>

<?php if ($rows === []): ?>
    <div class="empty-state">رکورد دسترسی‌ای با این فیلتر پیدا نشد.</div>
<?php else: ?>
    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>دوره</th>
                        <th>وضعیت</th>
                        <th>شروع</th>
                        <th>پایان</th>
                        <th>باقی‌مانده</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php [$label, $tone] = $stateMeta[$row['state']] ?? ['نامشخص', 'muted']; ?>
                        <tr>
                            <td dir="ltr"><?= e($row['username']) ?></td>
                            <td><?= e($row['course_title']) ?></td>
                            <td><span class="tag tag-<?= e($tone) ?>"><?= e($label) ?></span></td>
                            <td><?= e(jalali_date($row['start_date'])) ?></td>
                            <td>
                                <?php if ((int) $row['is_unlimited'] === 1): ?>
                                    <span class="tag tag-ok">نامحدود</span>
                                <?php else: ?>
                                    <?= e(jalali_date($row['end_date'])) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['remaining_days'] === null ? '—' : e(fa_digits((string) $row['remaining_days'])) . ' روز' ?></td>
                            <td class="row-actions">
                                <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/extend') ?>" class="inline-form">
                                    <?= csrf_field() ?>
                                    <input class="mini-input" name="days" type="number" dir="ltr" min="1" max="3650" value="30">
                                    <button class="btn btn-outline btn-xs" type="submit">تمدید</button>
                                </form>

                                <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/unlimited') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-ghost btn-xs" type="submit">
                                        <?= (int) $row['is_unlimited'] === 1 ? 'محدود کردن' : 'نامحدود کردن' ?>
                                    </button>
                                </form>

                                <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/toggle') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-ghost btn-xs" type="submit">
                                        <?= (int) $row['is_active'] === 1 ? 'قطع دسترسی' : 'وصل دسترسی' ?>
                                    </button>
                                </form>

                                <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/delete') ?>"
                                      data-confirm="این رکورد دسترسی کاملاً حذف شود؟">
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