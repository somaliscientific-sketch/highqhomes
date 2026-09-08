<?php
/** @var array|null $slider */
/** @var int $slot 1–4 */
$isEdit = !empty($slider);
$formId = 'hero-form-' . (int)$slot;
$imgSrc = !empty($slider['image'])
  ? (str_starts_with($slider['image'], 'http') ? $slider['image'] : uploadUrl($slider['image']))
  : '';
$action = $isEdit
  ? url('admin/sliders/' . $slider['id'] . '/edit')
  : url('admin/sliders/create');
?>

<div class="admin-hero-editor-wrap">
  <form id="<?= e($formId) ?>" action="<?= e($action) ?>" method="POST" enctype="multipart/form-data" class="admin-slider-form admin-hero-editor" data-slider-form>
    <?= csrf() ?>
    <input type="hidden" name="hero_slot" value="<?= (int)$slot ?>">

    <div class="admin-hero-editor__main">
      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-type"></i> Headline &amp; copy</h4>
        </div>
        <div class="admin-card-body admin-stack">
          <div class="admin-form-group">
            <label class="admin-label" for="slider-title-<?= (int)$slot ?>">Title *</label>
            <input id="slider-title-<?= (int)$slot ?>" type="text" name="title" value="<?= e($slider['title'] ?? '') ?>" required class="admin-input admin-input--lg" placeholder="We Build Spaces People Trust" data-slider-field="title">
          </div>
          <div class="admin-form-row admin-form-row--2">
            <div class="admin-form-group">
              <label class="admin-label" for="slider-subtitle-<?= (int)$slot ?>">Subtitle</label>
              <input id="slider-subtitle-<?= (int)$slot ?>" type="text" name="subtitle" value="<?= e($slider['subtitle'] ?? '') ?>" class="admin-input" placeholder="Premium Construction & Architecture" data-slider-field="subtitle">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="badge-text-<?= (int)$slot ?>">Photo badge</label>
              <input id="badge-text-<?= (int)$slot ?>" type="text" name="badge_text" value="<?= e($slider['badge_text'] ?? '') ?>" class="admin-input" placeholder="Garowe · Premium Build">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label" for="slider-description-<?= (int)$slot ?>">Description</label>
            <textarea id="slider-description-<?= (int)$slot ?>" name="description" rows="3" class="admin-input" placeholder="Supporting line under the headline" data-slider-field="description"><?= e($slider['description'] ?? '') ?></textarea>
          </div>
          <div class="admin-form-row admin-form-row--2">
            <div class="admin-form-group">
              <label class="admin-label" for="btn1-text-<?= (int)$slot ?>">Primary CTA</label>
              <input id="btn1-text-<?= (int)$slot ?>" type="text" name="button_text" value="<?= e($slider['button_text'] ?? '') ?>" class="admin-input" placeholder="Get a Free Quote" data-slider-field="button_text">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn1-link-<?= (int)$slot ?>">Primary link</label>
              <input id="btn1-link-<?= (int)$slot ?>" type="text" name="button_link" value="<?= e($slider['button_link'] ?? '') ?>" class="admin-input" placeholder="/contact">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn2-text-<?= (int)$slot ?>">Secondary CTA</label>
              <input id="btn2-text-<?= (int)$slot ?>" type="text" name="button_text_2" value="<?= e($slider['button_text_2'] ?? '') ?>" class="admin-input" placeholder="View Our Work" data-slider-field="button_text_2">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn2-link-<?= (int)$slot ?>">Secondary link</label>
              <input id="btn2-link-<?= (int)$slot ?>" type="text" name="button_link_2" value="<?= e($slider['button_link_2'] ?? '') ?>" class="admin-input" placeholder="/projects">
            </div>
          </div>
        </div>
      </div>

      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-image"></i> Hero image</h4>
        </div>
        <div class="admin-card-body">
          <label class="admin-image-zone admin-slider-upload admin-hero-upload" for="slider-image-file-<?= (int)$slot ?>">
            <img id="slider-img-preview-<?= (int)$slot ?>" src="<?= e($imgSrc) ?>" alt="" class="admin-image-preview<?= $imgSrc ? '' : ' is-hidden' ?>">
            <span id="slider-img-placeholder-<?= (int)$slot ?>" class="admin-hero-upload__placeholder<?= $imgSrc ? ' is-hidden' : '' ?>">
              <i class="bi bi-cloud-arrow-up"></i>
              <strong>Upload hero image</strong>
              <span>JPG, PNG or WebP · 1920×900 recommended</span>
            </span>
          </label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="admin-file-input" id="slider-image-file-<?= (int)$slot ?>" data-preview-target="slider-img-preview-<?= (int)$slot ?>" data-preview-placeholder="slider-img-placeholder-<?= (int)$slot ?>">
        </div>
      </div>

      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-palette"></i> Display &amp; UX</h4>
        </div>
        <div class="admin-card-body">
          <div class="admin-hero-ux-grid">
            <label class="admin-hero-option admin-hero-option--inline">
              <span class="admin-hero-option__copy">
                <strong>Show description</strong>
                <span>Visible under headline on homepage.</span>
              </span>
              <span class="toggle-switch">
                <input type="checkbox" name="show_description" value="1" <?= ($slider['show_description'] ?? 1) ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
              </span>
            </label>

            <div class="admin-form-group">
              <label class="admin-label" for="image-focus-<?= (int)$slot ?>">Image focal point</label>
              <select id="image-focus-<?= (int)$slot ?>" name="image_focus" class="admin-input">
                <?php foreach (['center' => 'Center', 'top' => 'Top (sky & rooflines)', 'bottom' => 'Bottom (ground level)'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= ($slider['image_focus'] ?? 'center') === $val ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="content-style-<?= (int)$slot ?>">Content style</label>
              <select id="content-style-<?= (int)$slot ?>" name="content_style" class="admin-input">
                <option value="standard" <?= ($slider['content_style'] ?? 'standard') === 'standard' ? 'selected' : '' ?>>Standard</option>
                <option value="minimal" <?= ($slider['content_style'] ?? '') === 'minimal' ? 'selected' : '' ?>>Minimal</option>
                <option value="bold" <?= ($slider['content_style'] ?? '') === 'bold' ? 'selected' : '' ?>>Bold</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="text-align-<?= (int)$slot ?>">Text alignment</label>
              <select id="text-align-<?= (int)$slot ?>" name="text_align" class="admin-input" data-slider-align>
                <?php foreach (['center', 'left', 'right'] as $a): ?>
                <option value="<?= $a ?>" <?= ($slider['text_align'] ?? 'center') === $a ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="admin-form-group admin-form-group--full">
              <label class="admin-label" for="overlay-range-<?= (int)$slot ?>">Image overlay</label>
              <div class="admin-range-row">
                <input type="range" id="overlay-range-<?= (int)$slot ?>" min="0" max="1" step="0.05" value="<?= e((string)($slider['overlay_opacity'] ?? 0.6)) ?>" data-slider-opacity-range>
                <input type="number" name="overlay_opacity" id="overlay-opacity-<?= (int)$slot ?>" min="0" max="1" step="0.05" value="<?= e((string)($slider['overlay_opacity'] ?? 0.6)) ?>" class="admin-input admin-input--compact" data-slider-opacity-input>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <aside class="admin-hero-editor__aside">
      <div class="admin-card admin-slider-live-preview admin-hero-preview-card">
        <div class="admin-card-header">
          <h4 class="admin-card-title">Homepage preview</h4>
        </div>
        <div class="admin-card-body">
          <div class="admin-slider-preview admin-hero-preview" data-slider-preview data-align="<?= e($slider['text_align'] ?? 'center') ?>">
            <div class="admin-slider-preview__media">
              <img src="<?= e($imgSrc ?: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1200&q=80') ?>" alt="" data-slider-preview-img>
              <div class="admin-slider-preview__overlay" data-slider-preview-overlay style="opacity:<?= e((string)($slider['overlay_opacity'] ?? 0.6)) ?>"></div>
            </div>
            <div class="admin-slider-preview__content" data-slider-preview-content>
              <span data-slider-preview-subtitle><?= e($slider['subtitle'] ?? 'Subtitle preview') ?></span>
              <strong data-slider-preview-title><?= e($slider['title'] ?? 'Slide title preview') ?></strong>
              <p data-slider-preview-desc><?= e(truncate($slider['description'] ?? 'Description text appears here.', 120)) ?></p>
              <div class="admin-slider-preview__btns">
                <span data-slider-preview-btn1><?= e($slider['button_text'] ?? 'Primary') ?></span>
                <span data-slider-preview-btn2><?= e($slider['button_text_2'] ?? 'Secondary') ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="admin-card admin-hero-publish-card">
        <div class="admin-card-body admin-stack">
          <label class="admin-hero-publish-row">
            <span class="toggle-switch">
              <input type="checkbox" name="is_published" <?= ($slider['is_published'] ?? 1) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </span>
            <span>
              <strong>Publish on homepage</strong>
              <small>Only published slides appear in the carousel.</small>
            </span>
          </label>

          <button type="submit" class="admin-btn admin-btn-primary admin-btn-lg admin-btn-block">
            <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Save section ' . (int)$slot : 'Create section ' . (int)$slot ?>
          </button>
        </div>
      </div>
    </aside>
  </form>

  <?php if ($isEdit): ?>
  <div class="admin-hero-aside-actions">
    <form action="<?= url('admin/sliders/' . $slider['id'] . '/toggle') ?>" method="POST" class="admin-inline-form">
      <?= csrf() ?>
      <input type="hidden" name="hero_slot" value="<?= (int)$slot ?>">
      <button type="submit" class="admin-btn admin-btn-secondary admin-btn-block admin-btn-sm">
        <i class="bi bi-<?= $slider['is_published'] ? 'eye-slash' : 'eye' ?>"></i>
        <?= $slider['is_published'] ? 'Unpublish' : 'Publish now' ?>
      </button>
    </form>
    <form action="<?= url('admin/sliders/' . $slider['id'] . '/delete') ?>" method="POST" class="admin-inline-form">
      <?= csrf() ?>
      <input type="hidden" name="hero_slot" value="<?= (int)$slot ?>">
      <button type="submit" data-confirm="Delete hero section <?= (int)$slot ?> permanently?" class="admin-btn admin-btn-ghost admin-btn-block admin-btn-sm admin-btn-danger-text">
        <i class="bi bi-trash"></i> Remove slide
      </button>
    </form>
  </div>
  <?php endif; ?>
</div>
