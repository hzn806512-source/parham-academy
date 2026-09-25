<?php
/** @var array|null $course */
$isNew  = $course === null;
$action = $isNew
    ? url('/admin/courses/create')
    : url('/admin/courses/' . (int) $course['id'] . '/edit');

$existingCover   = $course['cover_image'] ?? '';
$existingIsUrl   = $existingCover !== '' && is_url((string) $existingCover);
$defaultCoverTab = ($existingCover !== '' && !$existingIsUrl) ? 'upload' : 'url';
?>

<style>
    /* ==========================================================================
       پالت رنگی لوکس طلایی و سفید (Light Mode)
       ========================================================================== */
    .course-form {
        --cf-gold: #c59b27;
        --cf-gold-light: #e0b84c;
        --cf-gold-gradient: linear-gradient(135deg, #c59b27 0%, #e2be58 100%);
        --cf-gold-glow: rgba(197, 155, 39, 0.22);
        
        --cf-bg-card: #ffffff;
        --cf-border: #ede8dc;
        --cf-title: #1c1917;
        --cf-label: #292524;
        --cf-hint: #78716c;
        
        --cf-switch-bg: #f5f2eb;
        --cf-switch-text: #78716c;
        --cf-switch-active-bg: #ffffff;
        --cf-switch-active-text: #b0891e;
        
        --cf-box-bg: #faf8f4;
        --cf-input-bg: #ffffff;
        --cf-input-border: #ded7c8;
        --cf-input-text: #1c1917;
        --cf-icon: #bfa882;
        --cf-danger: #e11d48;

        max-width: 900px;
        margin: 0 auto;
    }

    /* ==========================================================================
       پالت رنگی طلایی و سفید در حالت شب (Dark Luxury)
       ========================================================================== */
    @media (prefers-color-scheme: dark) {
        :root:not([data-theme="light"]) .course-form {
            --cf-gold: #e5c058;
            --cf-gold-light: #fae188;
            --cf-gold-gradient: linear-gradient(135deg, #d4af37 0%, #f3ce63 100%);
            --cf-gold-glow: rgba(229, 192, 88, 0.3);
            
            --cf-bg-card: #141416;
            --cf-border: rgba(229, 192, 88, 0.18);
            --cf-title: #ffffff;
            --cf-label: #f5f5f4;
            --cf-hint: #a8a29e;
            
            --cf-switch-bg: rgba(255, 255, 255, 0.05);
            --cf-switch-text: #d6d3d1;
            --cf-switch-active-bg: var(--cf-gold-gradient);
            --cf-switch-active-text: #121214;
            
            --cf-box-bg: rgba(229, 192, 88, 0.03);
            --cf-input-bg: rgba(255, 255, 255, 0.04);
            --cf-input-border: rgba(229, 192, 88, 0.22);
            --cf-input-text: #ffffff;
            --cf-icon: #e5c058;
            --cf-danger: #fb7185;
        }
    }

    [data-theme="dark"] .course-form,
    .dark .course-form,
    body.dark-mode .course-form {
        --cf-gold: #e5c058;
        --cf-gold-light: #fae188;
        --cf-gold-gradient: linear-gradient(135deg, #d4af37 0%, #f3ce63 100%);
        --cf-gold-glow: rgba(229, 192, 88, 0.3);
        
        --cf-bg-card: #141416;
        --cf-border: rgba(229, 192, 88, 0.18);
        --cf-title: #ffffff;
        --cf-label: #f5f5f4;
        --cf-hint: #a8a29e;
        
        --cf-switch-bg: rgba(255, 255, 255, 0.05);
        --cf-switch-text: #d6d3d1;
        --cf-switch-active-bg: var(--cf-gold-gradient);
        --cf-switch-active-text: #121214;
        
        --cf-box-bg: rgba(229, 192, 88, 0.03);
        --cf-input-bg: rgba(255, 255, 255, 0.04);
        --cf-input-border: rgba(229, 192, 88, 0.22);
        --cf-input-text: #ffffff;
        --cf-icon: #e5c058;
        --cf-danger: #fb7185;
    }

    /* ساختار فرم */
    .form-section { margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--cf-border); }
    .form-section:last-of-type { border-bottom: none; }
    
    .form-section-title { 
        font-size: 1.15rem; 
        font-weight: 700; 
        margin-bottom: 1.25rem; 
        color: var(--cf-title);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 18px;
        background: var(--cf-gold-gradient);
        border-radius: 4px;
    }
    
    .field { margin-bottom: 1.25rem; display: flex; flex-direction: column; }
    .field label { font-weight: 600; margin-bottom: 0.45rem; font-size: 0.9rem; color: var(--cf-label); }
    .field-hint { font-size: 0.8rem; color: var(--cf-hint); margin-top: 0.45rem; display: block; }
    
    .field-grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; }
    
    /* سوئیچر روش انتخاب تصویر */
    .source-switch { 
        display: inline-flex; 
        background: var(--cf-switch-bg); 
        padding: 4px; 
        border-radius: 8px; 
        margin-bottom: 0.85rem; 
        gap: 4px; 
        width: fit-content;
        border: 1px solid var(--cf-border);
    }
    .source-switch button { 
        border: none; 
        background: transparent; 
        padding: 7px 16px; 
        font-size: 0.85rem; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: all 0.25s ease; 
        color: var(--cf-switch-text); 
        font-family: inherit; 
    }
    .source-switch button[aria-pressed="true"] { 
        background: var(--cf-switch-active-bg); 
        color: var(--cf-switch-active-text); 
        font-weight: 700; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.12); 
    }
    
    /* باکس پنل تصویر */
    .source-pane {
        background: var(--cf-box-bg);
        border: 1px solid var(--cf-border);
        border-radius: 12px;
        padding: 1.2rem;
        transition: all 0.25s ease;
    }
    .source-pane:focus-within {
        border-color: var(--cf-gold);
        box-shadow: 0 0 0 3px var(--cf-gold-glow);
    }

    /* ورودی لینک با تِم طلایی و سفید */
    .input-icon-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .input-icon-wrap .input-icon {
        position: absolute;
        left: 14px;
        color: var(--cf-icon);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        transition: color 0.25s;
    }
    .input-icon-wrap input {
        width: 100%;
        padding: 10px 14px 10px 42px !important;
        border: 1px solid var(--cf-input-border);
        border-radius: 8px;
        background: var(--cf-input-bg);
        color: var(--cf-input-text);
        font-size: 0.92rem;
        transition: all 0.25s;
    }
    .input-icon-wrap input:focus {
        outline: none;
        border-color: var(--cf-gold);
        box-shadow: 0 0 0 3px var(--cf-gold-glow);
    }
    .input-icon-wrap:focus-within .input-icon {
        color: var(--cf-gold);
    }

    /* کادر آپلود فایل */
    .file-upload-box input[type="file"] {
        width: 100%;
        padding: 10px 14px;
        border: 1px dashed var(--cf-gold);
        border-radius: 8px;
        background: var(--cf-input-bg);
        color: var(--cf-input-text);
        cursor: pointer;
        font-family: inherit;
        font-size: 0.88rem;
    }

    /* کادر پیش‌نمایش تصویر فعلی */
    .cover-preview-box { 
        display: flex; 
        align-items: center; 
        gap: 1.25rem; 
        padding: 0.85rem; 
        background: var(--cf-box-bg); 
        border: 1px solid var(--cf-border); 
        border-radius: 10px; 
        margin-bottom: 1rem; 
    }
    .cover-preview-box img { 
        max-height: 90px; 
        width: auto; 
        border-radius: 8px; 
        object-fit: cover; 
        border: 1px solid var(--cf-gold); 
        box-shadow: 0 2px 8px var(--cf-gold-glow);
    }
    
    textarea { resize: vertical; min-height: 120px; font-family: inherit; }
    
    /* دکمه ارسال به سبک طلایی سلطنتی */
    .form-submit-bar { display: flex; justify-content: flex-end; margin-top: 1.75rem; }
    .course-form .btn-primary {
        background: var(--cf-gold-gradient);
        color: #ffffff;
        border: none;
        padding: 10px 24px;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        min-width: 220px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px var(--cf-gold-glow);
    }
    .course-form .btn-primary:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px var(--cf-gold-glow);
    }

    /* رنگ دکمه اصلی در دارک‌مود برای کنتراست بی‌نقص */
    @media (prefers-color-scheme: dark) {
        :root:not([data-theme="light"]) .course-form .btn-primary {
            color: #121214;
        }
    }
    [data-theme="dark"] .course-form .btn-primary,
    .dark .course-form .btn-primary {
        color: #121214;
    }

    @media (max-width: 640px) {
        .course-form .btn-primary { width: 100%; }
        .head-actions { display: flex; gap: 0.5rem; }
    }
</style>

<div class="page-head">
    <div>
        <h1><?= $isNew ? 'ساخت دورهٔ جدید' : 'ویرایش دوره' ?></h1>
        <?php if (!$isNew): ?>
            <p class="muted"><?= e($course['title']) ?></p>
        <?php endif; ?>
    </div>
    <div class="head-actions">
        <?php if (!$isNew): ?>
            <a class="btn btn-outline btn-sm" href="<?= url('/admin/courses/' . (int) $course['id'] . '/lessons') ?>">مدیریت دروس</a>
        <?php endif; ?>
        <a class="btn btn-ghost btn-sm" href="<?= url('/admin/courses') ?>">بازگشت</a>
    </div>
</div>

<section class="card form-card course-form">
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" data-validate novalidate>
        <?= csrf_field() ?>

        <!-- اطلاعات اصلی -->
        <div class="form-section">
            <h2 class="form-section-title">اطلاعات اصلی</h2>

            <div class="field">
                <label for="title">عنوان دوره <span style="color: var(--cf-danger);">*</span></label>
                <input id="title" name="title" type="text" required maxlength="180"
                       value="<?= e($course['title'] ?? '') ?>">
            </div>

            <div class="field-grid-2">
                <div class="field">
                    <label for="slug">نشانی کوتاه (Slug)</label>
                    <input id="slug" name="slug" type="text" dir="ltr" maxlength="200"
                           value="<?= e($course['slug'] ?? '') ?>" placeholder="bridal-makeup">
                    <small class="field-hint">خالی بگذارید تا خودکار از عنوان ساخته شود</small>
                </div>

                <div class="field">
                    <label for="price_display">قیمت نمایشی (تومان)</label>
                    <input id="price_display" name="price_display" type="text" dir="ltr" inputmode="numeric"
                           value="<?= e((string) (int) ($course['price_display'] ?? 0)) ?>">
                    <small class="field-hint">فقط نمایشی — هیچ پرداختی انجام نمی‌شود</small>
                </div>
            </div>

            <div class="field">
                <label for="short_description">توضیح کوتاه</label>
                <input id="short_description" name="short_description" type="text" maxlength="300"
                       value="<?= e($course['short_description'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="description">توضیح کامل</label>
                <textarea id="description" name="description" rows="6"><?= e($course['description'] ?? '') ?></textarea>
                <small class="field-hint">برای جداکردن پاراگراف‌ها یک خط خالی بگذارید</small>
            </div>
        </div>

        <!-- دسته‌بندی و نمایش -->
        <div class="form-section">
            <h2 class="form-section-title">دسته‌بندی و نمایش</h2>

            <div class="field-grid-2">
                <div class="field">
                    <label for="level">سطح دوره</label>
                    <select id="level" name="level">
                        <option value="beginner" <?= ($course['level'] ?? 'beginner') === 'beginner' ? 'selected' : '' ?>>مقدماتی</option>
                        <option value="intermediate" <?= ($course['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>متوسط</option>
                        <option value="advanced" <?= ($course['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>پیشرفته</option>
                    </select>
                </div>

                <div class="field">
                    <label for="status">وضعیت انتشار</label>
                    <select id="status" name="status">
                        <option value="published" <?= ($course['status'] ?? 'draft') === 'published' ? 'selected' : '' ?>>منتشرشده</option>
                        <option value="draft" <?= ($course['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>پیش‌نویس (در سایت دیده نمی‌شود)</option>
                        <option value="archived" <?= ($course['status'] ?? '') === 'archived' ? 'selected' : '' ?>>بایگانی</option>
                    </select>
                </div>

                <div class="field">
                    <label for="sort_order">ترتیب نمایش</label>
                    <input id="sort_order" name="sort_order" type="number" dir="ltr"
                           value="<?= e((string) (int) ($course['sort_order'] ?? 0)) ?>">
                </div>

                <div class="field">
                    <label for="is_featured">نمایش به عنوان دورهٔ ویژه</label>
                    <select id="is_featured" name="is_featured">
                        <option value="0" <?= (int) ($course['is_featured'] ?? 0) === 0 ? 'selected' : '' ?>>خیر</option>
                        <option value="1" <?= (int) ($course['is_featured'] ?? 0) === 1 ? 'selected' : '' ?>>بله — نمایش در صفحهٔ اول</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- تصویر کاور -->
        <div class="form-section">
            <h2 class="form-section-title">تصویر کاور</h2>

            <?php if (!$isNew && !empty($course['cover_image'])): ?>
                <div class="cover-preview-box">
                    <img src="<?= e(cover_url((string) $course['cover_image'])) ?>" alt="تصویر فعلی کاور">
                    <label class="checkbox" style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--cf-danger);">
                        <input type="checkbox" name="remove_cover" value="1">
                        <span style="font-size: 0.9rem; font-weight: 600;">حذف تصویر فعلی</span>
                    </label>
                </div>
            <?php endif; ?>

            <div class="field">
                <label>روش درج تصویر</label>
                <div class="source-switch" role="group" aria-label="انتخاب روش تعیین تصویر" data-source-switch>
                    <button type="button" data-target="url" aria-pressed="<?= $defaultCoverTab === 'url' ? 'true' : 'false' ?>">لینک اینترنتی</button>
                    <button type="button" data-target="upload" aria-pressed="<?= $defaultCoverTab === 'upload' ? 'true' : 'false' ?>">آپلود از سیستم</button>
                </div>

                <!-- باکس طلایی/سفید برای قرار دادن لینک تصویر -->
                <div class="source-pane" data-pane="url" <?= $defaultCoverTab === 'url' ? '' : 'hidden' ?>>
                    <div class="input-icon-wrap">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </span>
                        <input id="cover_url" name="cover_url" type="url" dir="ltr"
                               placeholder="https://cdn.example.com/covers/course-1.jpg"
                               value="<?= $existingIsUrl ? e($course['cover_image']) : '' ?>">
                    </div>
                    <small class="field-hint">نشانی اینترنتی مستقیم فایل تصویر (هاست دانلود یا CDN)</small>
                </div>

                <!-- باکس آپلود فایل با کادر طلایی -->
                <div class="source-pane" data-pane="upload" <?= $defaultCoverTab === 'upload' ? '' : 'hidden' ?>>
                    <div class="file-upload-box">
                        <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <small class="field-hint">فرمت‌های مجاز: JPG، PNG یا WebP</small>
                </div>
            </div>
        </div>

        <div class="form-submit-bar">
            <button class="btn btn-primary" type="submit">
                <?= $isNew ? 'ساخت دوره و رفتن به دروس' : 'ذخیرهٔ تغییرات' ?>
            </button>
        </div>
    </form>
</section>

<script>
(function () {
    document.querySelectorAll('[data-source-switch]').forEach(function (group) {
        var wrap = group.closest('.field');
        group.querySelectorAll('button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                group.querySelectorAll('button').forEach(function (b) { 
                    b.setAttribute('aria-pressed', 'false'); 
                });
                btn.setAttribute('aria-pressed', 'true');
                wrap.querySelectorAll('.source-pane').forEach(function (pane) {
                    pane.hidden = pane.getAttribute('data-pane') !== btn.getAttribute('data-target');
                });
            });
        });
    });
})();
</script>