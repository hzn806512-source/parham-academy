<?php
/** @var array $users */
/** @var string $search */
/** @var string $role */
/** @var int $total */
/** @var int $page */
/** @var int $pages */
?>
<div class="page-head">
    <div>
        <h1>مدیریت کاربران</h1>
        <p class="muted">در مجموع <?= e(fa_digits((string) $total)) ?> حساب</p>
    </div>
    <a class="btn btn-primary btn-sm" href="<?= url('/admin/users/create') ?>">کاربر جدید</a>
</div>

<form class="filter-bar" method="get" action="<?= url('/admin/users') ?>">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="جستجو: نام کاربری، نام، یادداشت...">
    <select name="role">
        <option value="">همهٔ نقش‌ها</option>
        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>کاربر عادی</option>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>مدیر</option>
    </select>
    <button class="btn btn-primary btn-sm" type="submit">فیلتر</button>
    <a class="btn btn-ghost btn-sm" href="<?= url('/admin/users') ?>">پاک‌کردن</a>
</form>

<?php if ($users === []): ?>
    <div class="empty-state">کاربری با این مشخصات پیدا نشد.</div>
<?php else: ?>
    <div class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>نام کاربری</th>
                        <th>نام</th>
                        <th>نقش</th>
                        <th>وضعیت</th>
                        <th>دسترسی فعال</th>
                        <th>آخرین ورود</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td dir="ltr"><?= e($user['username']) ?></td>
                            <td><?= e($user['full_name'] ?: '—') ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="tag tag-danger">مدیر</span>
                                <?php else: ?>
                                    <span class="tag tag-muted">کاربر</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ((int) $user['is_active'] === 1): ?>
                                    <span class="tag tag-ok">فعال</span>
                                <?php else: ?>
                                    <span class="tag tag-danger">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= e(fa_digits((string) $user['active_access_count'])) ?>
                                از <?= e(fa_digits((string) $user['access_count'])) ?>
                            </td>
                            <td><?= e(jalali_date($user['last_login_at'])) ?></td>
                            <td class="row-actions">
                                <a class="btn btn-outline btn-xs" href="<?= url('/admin/users/' . (int) $user['id'] . '/edit') ?>">ویرایش</a>
                                <form method="post" action="<?= url('/admin/users/' . (int) $user['id'] . '/delete') ?>"
                                      data-confirm="این کاربر و همهٔ دسترسی‌هایش حذف شود؟">
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

    <?php if ($pages > 1): ?>
        <nav class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a class="page-link <?= $i === $page ? 'is-active' : '' ?>"
                   href="<?= url('/admin/users') ?>?q=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&page=<?= $i ?>">
                    <?= e(fa_digits((string) $i)) ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>