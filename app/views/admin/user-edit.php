<?php
/** @var array|null $user */
/** @var array $courses */
/** @var array $access */
$isNew  = $user === null;
$action = $isNew
    ? url('/admin/users/create')
    : url('/admin/users/' . (int) $user['id'] . '/edit');
?>
<div class="page-head">
    <div>
        <h1><?= $isNew ? 'ساخت کاربر جدید' : 'ویرایش کاربر' ?></h1>
        <?php if (!$isNew): ?>
            <p class="muted">نام کاربری: <b dir="ltr"><?= e($user['username']) ?></b></p>
        <?php endif; ?>
    </div>
    <a class="btn btn-ghost btn-sm" href="<?= url('/admin/users') ?>">بازگشت</a>
</div>

<div class="grid grid-2">
    <section class="card form-card">
        <h2>اطلاعات حساب</h2>

        <form method="post" action="<?= e($action) ?>" data-validate novalidate>
            <?= csrf_field() ?>

            <?php if ($isNew): ?>
                <div class="field">
                    <label for="username">نام کاربری</label>
                    <input id="username" name="username" type="text" dir="ltr" required
                           minlength="4" maxlength="30" pattern="[A-Za-z0-9_.]{4,30}">
                    <small class="field-hint">حروف لاتین، اعداد، نقطه یا زیرخط</small>
                </div>

                <div class="field">
                    <label for="password">رمز عبور</label>
                    <input id="password" name="password" type="text" dir="ltr" required minlength="8">
                    <small class="field-hint">حداقل ۸ کاراکتر شامل حرف و عدد — بعد از ساخت، آن را به کاربر اعلام کنید</small>
                </div>
            <?php else: ?>
                <div class="field">
                    <label for="new_password">رمز جدید (اختیاری)</label>
                    <input id="new_password" name="new_password" type="text" dir="ltr" minlength="8"
                           placeholder="خالی بگذارید تا رمز تغییر نکند">
                    <small class="field-hint">برای زمانی که کاربر رمزش را فراموش کرده است</small>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="full_name">نام و نام خانوادگی</label>
                <input id="full_name" name="full_name" type="text" maxlength="120"
                       value="<?= e($user['full_name'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="note">یادداشت داخلی مدیر</label>
                <input id="note" name="note" type="text" maxlength="255"
                       value="<?= e($user['note'] ?? '') ?>" placeholder="مانند: تسویه نقدی — معرفی از اینستاگرام">
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="role">نقش</label>
                    <select id="role" name="role">
                        <option value="user" <?= ($user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>کاربر عادی</option>
                        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>مدیر</option>
                    </select>
                </div>

                <div class="field">
                    <label for="is_active">وضعیت حساب</label>
                    <select id="is_active" name="is_active">
                        <option value="1" <?= (int) ($user['is_active'] ?? 1) === 1 ? 'selected' : '' ?>>فعال</option>
                        <option value="0" <?= (int) ($user['is_active'] ?? 1) === 0 ? 'selected' : '' ?>>غیرفعال (امکان ورود ندارد)</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">
                <?= $isNew ? 'ساخت کاربر' : 'ذخیرهٔ تغییرات' ?>
            </button>
        </form>
    </section>

    <section class="card">
        <h2>دوره‌های این کاربر</h2>

        <?php if ($isNew): ?>
            <div class="empty-state small">پس از ساخت حساب، می‌توانید دسترسی دوره بدهید.</div>
        <?php elseif ($access === []): ?>
            <div class="empty-state small">
                هنوز دوره‌ای برای این کاربر فعال نشده است.
                <a class="btn btn-primary btn-sm" href="<?= url('/admin/access') ?>">دادن دسترسی</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>دوره</th><th>وضعیت</th><th>پایان</th><th>باقی‌مانده</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($access as $row): ?>
                            <tr>
                                <td><?= e($row['title']) ?></td>
                                <td>
                                    <?php if ($row['state'] === 'active'): ?>
                                        <span class="tag tag-ok">فعال</span>
                                    <?php elseif ($row['state'] === 'pending'): ?>
                                        <span class="tag tag-warn">شروع نشده</span>
                                    <?php else: ?>
                                        <span class="tag tag-danger">منقضی/غیرفعال</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= (int) $row['is_unlimited'] === 1 ? 'نامحدود' : e(jalali_date($row['end_date'])) ?>
                                </td>
                                <td>
                                    <?= $row['remaining_days'] === null ? '—' : e(fa_digits((string) $row['remaining_days'])) . ' روز' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= url('/admin/access') ?>?q=<?= urlencode((string) $user['username']) ?>">
                مدیریت دسترسی‌های این کاربر
            </a>
        <?php endif; ?>
    </section>
</div>