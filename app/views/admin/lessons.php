<?php
/** @var array $course */
/** @var array $lessons */
?>
<div class="page-head">
    <div>
        <h1>دروس دوره</h1>
        <p class="muted"><?= e($course['title']) ?></p>
    </div>
    <div class="head-actions">
        <a class="btn btn-outline btn-sm" href="<?= url('/admin/courses/' . (int) $course['id'] . '/edit') ?>">ویرایش دوره</a>
        <a class="btn btn-ghost btn-sm" href="<?= url('/admin/courses') ?>">بازگشت</a>
    </div>
</div>

<section class="card form-card">
    <h2>افزودن درس جدید</h2>

    <form method="post" action="<?= url('/admin/courses/' . (int) $course['id'] . '/lessons') ?>"
          enctype="multipart/form-data" data-validate novalidate>
        <?= csrf_field() ?>

        <div class="field">
            <label for="title">عنوان درس</label>
            <input id="title" name="title" type="text" required maxlength="180"
                   placeholder="درس ۱ — معرفی دوره و ابزارها">
        </div>

        <div class="field">
            <label for="description">توضیح درس (اختیاری)</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>

        <div class="field-row">
            <div class="field grow">
                <label for="video_file">آدرس اینترنتی ویدیو (URL مستقیم) یا نام فایل</label>
                <input id="video_file" name="video_file" type="text" dir="ltr" placeholder="https://dl.site.com/video.mp4 یا lesson-01.mp4">
                <small class="field-hint">لینک مستقیم دانلود سرور / کلود یا نام فایل در storage/videos (پیشنهاد سرعت بالا و بدون مصرف رم هاست)</small>
            </div>

            <div class="field grow">
                <label for="video">یا آپلود فایل ویدیو از سیستم</label>
                <input id="video" name="video" type="file" accept="video/mp4,video/webm,video/quicktime">
                <small class="field-hint">آپلود سنتی از طریق مرورگر (پیشنهاد: MP4)</small>
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label for="duration_minutes">مدت (دقیقه)</label>
                <input id="duration_minutes" name="duration_minutes" type="number" dir="ltr" min="0" max="65535" value="0">
            </div>

            <div class="field">
                <label for="sort_order">ترتیب</label>
                <input id="sort_order" name="sort_order" type="number" dir="ltr" value="0">
                <small class="field-hint">۰ = انتهای فهرست</small>
            </div>

            <div class="field">
                <label for="is_free_preview">پیش‌نمایش رایگان</label>
                <select id="is_free_preview" name="is_free_preview">
                    <option value="0">خیر</option>
                    <option value="1">بله — برای همهٔ کاربران واردشده</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">افزودن درس</button>
    </form>
</section>

<h2 class="mt-lg">فهرست دروس (<?= e(fa_digits((string) count($lessons))) ?>)</h2>

<?php if ($lessons === []): ?>
    <div class="empty-state">هنوز درسی افزوده نشده است.</div>
<?php else: ?>
    <?php foreach ($lessons as $lesson): ?>
        <section class="card lesson-edit-card">
            <form method="post" action="<?= url('/admin/lessons/' . (int) $lesson['id'] . '/update') ?>"
                  enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="field-row">
                    <div class="field grow">
                        <label>عنوان</label>
                        <input name="title" type="text" required maxlength="180" value="<?= e($lesson['title']) ?>">
                    </div>

                    <div class="field">
                        <label>مدت (دقیقه)</label>
                        <input name="duration_minutes" type="number" dir="ltr" min="0" max="65535"
                               value="<?= e((string) (int) $lesson['duration_minutes']) ?>">
                    </div>

                    <div class="field">
                        <label>ترتیب</label>
                        <input name="sort_order" type="number" dir="ltr" value="<?= e((string) (int) $lesson['sort_order']) ?>">
                    </div>

                    <div class="field">
                        <label>پیش‌نمایش رایگان</label>
                        <select name="is_free_preview">
                            <option value="0" <?= (int) $lesson['is_free_preview'] === 0 ? 'selected' : '' ?>>خیر</option>
                            <option value="1" <?= (int) $lesson['is_free_preview'] === 1 ? 'selected' : '' ?>>بله</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>توضیح</label>
                    <textarea name="description" rows="2"><?= e($lesson['description'] ?? '') ?></textarea>
                </div>

                <div class="field-row">
                    <div class="field grow">
                        <label>آدرس اینترنتی ویدیو (URL) یا نام فایل</label>
                        <input name="video_file" type="text" dir="ltr" value="<?= e($lesson['video_file']) ?>" placeholder="https://... یا lesson.mp4">
                    </div>

                    <div class="field grow">
                        <label>جایگزینی فایل با آپلود مستقیم</label>
                        <input name="video" type="file" accept="video/mp4,video/webm,video/quicktime">
                    </div>
                </div>

                <div class="card-actions">
                    <button class="btn btn-primary btn-sm" type="submit">ذخیره</button>
                </div>
            </form>

            <form method="post" action="<?= url('/admin/lessons/' . (int) $lesson['id'] . '/delete') ?>"
                  class="lesson-delete" data-confirm="این درس حذف شود؟">
                <?= csrf_field() ?>
                <label class="checkbox">
                    <input type="checkbox" name="delete_file" value="1">
                    <span>فایل ویدیو از سرور هم حذف شود</span>
                </label>
                <button class="btn btn-danger btn-xs" type="submit">حذف درس</button>
            </form>
        </section>
    <?php endforeach; ?>
<?php endif; ?>