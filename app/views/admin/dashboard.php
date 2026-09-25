<?php
/** @var array $stats */
/** @var array $expiringSoon */
/** @var array $recentUsers */
/** @var array $recentActivity */
?>
<div class="page-head">
    <div>
        <h1>داشبورد مدیریت</h1>
        <p class="muted">وضعیت کلی آکادمی در یک نگاه</p>
    </div>
    <div class="head-actions">
        <a class="btn btn-primary btn-sm" href="<?= url('/admin/access') ?>">دادن دسترسی</a>
        <a class="btn btn-outline btn-sm" href="<?= url('/admin/courses/create') ?>">دورهٔ جدید</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card"><span>کاربران</span><strong><?= e(fa_digits((string) $stats['users'])) ?></strong></div>
    <div class="stat-card"><span>دوره‌ها</span><strong><?= e(fa_digits((string) $stats['courses'])) ?></strong></div>
    <div class="stat-card"><span>دروس ویدیویی</span><strong><?= e(fa_digits((string) $stats['lessons'])) ?></strong></div>
    <div class="stat-card stat-ok"><span>دسترسی فعال</span><strong><?= e(fa_digits((string) $stats['accessActive'])) ?></strong></div>
    <div class="stat-card stat-danger"><span>دسترسی منقضی</span><strong><?= e(fa_digits((string) $stats['accessExpired'])) ?></strong></div>
    <div class="stat-card stat-warn"><span>قطع‌شده توسط مدیر</span><strong><?= e(fa_digits((string) $stats['accessRevoked'])) ?></strong></div>
    <div class="stat-card"><span>دسترسی نامحدود</span><strong><?= e(fa_digits((string) $stats['unlimited'])) ?></strong></div>
    <div class="stat-card"><span>حجم محتوا</span><strong><?= e(duration_label((int) $stats['minutes'])) ?></strong></div>
</div>

<div class="grid grid-2 mt-lg">
    <section class="card">
        <h2>دسترسی‌های نزدیک به انقضا (۷ روز آینده)</h2>

        <?php if ($expiringSoon === []): ?>
            <div class="empty-state small">موردی برای یادآوری وجود ندارد.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>کاربر</th><th>دوره</th><th>تاریخ پایان</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($expiringSoon, 0, 2) as $row): ?>
                            <tr>
                                <td dir="ltr"><?= e($row['username']) ?></td>
                                <td><?= e($row['course_title']) ?></td>
                                <td><?= e(jalali_date($row['end_date'])) ?></td>
                                <td>
                                    <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/extend') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="days" value="30">
                                        <button class="btn btn-outline btn-xs" type="submit">تمدید ۳۰ روز</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($expiringSoon) > 2): ?>
                <button class="btn btn-outline btn-sm mt-md" type="button" data-modal-open="#modal-expiring">
                    بیشتر (<?= e(fa_digits((string) count($expiringSoon))) ?>)
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>تازه‌ترین کاربران</h2>

        <?php if ($recentUsers === []): ?>
            <div class="empty-state small">هنوز کاربری ثبت‌نام نکرده است.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>نام کاربری</th><th>وضعیت</th><th>ثبت‌نام</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recentUsers, 0, 2) as $user): ?>
                            <tr>
                                <td dir="ltr"><?= e($user['username']) ?></td>
                                <td>
                                    <?php if ((int) $user['is_active'] === 1): ?>
                                        <span class="tag tag-ok">فعال</span>
                                    <?php else: ?>
                                        <span class="tag tag-danger">غیرفعال</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e(jalali_date($user['created_at'])) ?></td>
                                <td><a class="btn btn-ghost btn-xs" href="<?= url('/admin/users/' . (int) $user['id'] . '/edit') ?>">ویرایش</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($recentUsers) > 2): ?>
                <button class="btn btn-outline btn-sm mt-md" type="button" data-modal-open="#modal-users">
                    بیشتر (<?= e(fa_digits((string) count($recentUsers))) ?>)
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<section class="card mt-lg">
    <h2>رویدادهای اخیر</h2>

    <?php if ($recentActivity === []): ?>
        <div class="empty-state small">رویدادی ثبت نشده است.</div>
    <?php else: ?>
        <ul class="timeline" data-reveal-list data-reveal-step="4">
            <?php foreach ($recentActivity as $i => $item): ?>
                <li<?= $i >= 4 ? ' hidden' : '' ?>>
                    <span class="timeline-dot"></span>
                    <div>
                        <strong><?= e($item['description'] ?: $item['action']) ?></strong>
                        <small class="muted">
                            <?= e($item['username'] ?? '—') ?> — <?= e(jalali_date($item['created_at'], true)) ?>
                        </small>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- <?php if (count($recentActivity) > 4): ?>
            <button class="btn btn-outline btn-sm mt-md" type="button" data-reveal-more>نمایش بیشتر</button>
        <?php endif; ?> -->
    <?php endif; ?>
</section>

<?php if (count($expiringSoon) > 2): ?>
<div class="modal-overlay" id="modal-expiring" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-head">
            <h3>دسترسی‌های نزدیک به انقضا (۷ روز آینده)</h3>
            <button class="modal-close" type="button" data-modal-close aria-label="بستن">×</button>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>کاربر</th><th>دوره</th><th>تاریخ پایان</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($expiringSoon as $row): ?>
                        <tr>
                            <td dir="ltr"><?= e($row['username']) ?></td>
                            <td><?= e($row['course_title']) ?></td>
                            <td><?= e(jalali_date($row['end_date'])) ?></td>
                            <td>
                                <form method="post" action="<?= url('/admin/access/' . (int) $row['id'] . '/extend') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="days" value="30">
                                    <button class="btn btn-outline btn-xs" type="submit">تمدید ۳۰ روز</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (count($recentUsers) > 2): ?>
<div class="modal-overlay" id="modal-users" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-head">
            <h3>تازه‌ترین کاربران</h3>
            <button class="modal-close" type="button" data-modal-close aria-label="بستن">×</button>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>نام کاربری</th><th>وضعیت</th><th>ثبت‌نام</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td dir="ltr"><?= e($user['username']) ?></td>
                            <td>
                                <?php if ((int) $user['is_active'] === 1): ?>
                                    <span class="tag tag-ok">فعال</span>
                                <?php else: ?>
                                    <span class="tag tag-danger">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e(jalali_date($user['created_at'])) ?></td>
                            <td><a class="btn btn-ghost btn-xs" href="<?= url('/admin/users/' . (int) $user['id'] . '/edit') ?>">ویرایش</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
