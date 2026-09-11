<?php
/** @var array $slider */
/** @var bool $isEdit */
/** @var string $action */
$slot = (int)($slider['id'] ?? 0);
$formId = 'hero-form-' . ($slot ?: 'new');
$imgSrc = $imgSrc ?? '';
$mobileSrc = $mobileSrc ?? '';
$videoSrc = $videoSrc ?? '';
$toLocal = $toLocal ?? static fn (?string $value): string => '';
?>

<div class="admin-hero-editor-wrap">
  <form id="<?= e($formId) ?>" action="<?= e($action) ?>" method="POST" enctype="multipart/form-data" class="admin-slider-form admin-hero-editor" data-slider-form>
    <?= csrf() ?>

    <div class="admin-hero-editor__main">
      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-type"></i> Headline &amp; copy</h4>
        </div>
        <div class="admin-card-body admin-stack">
          <div class="admin-form-group">
            <label class="admin-label" for="slider-title">Title *</label>
            <input id="slider-title" type="text" name="title" value="<?= e($slider['title'] ?? '') ?>" required maxlength="200" class="admin-input admin-input--lg" placeholder="Build Your Dream Home With HighQ Homes" data-slider-field="title">
          </div>
          <div class="admin-form-row admin-form-row--2">
            <div class="admin-form-group">
              <label class="admin-label" for="slider-subtitle">Subtitle / badge label</label>
              <input id="slider-subtitle" type="text" name="subtitle" value="<?= e($slider['subtitle'] ?? '') ?>" maxlength="200" class="admin-input" placeholder="Premium Construction & Architecture" data-slider-field="subtitle">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="badge-text">Photo caption</label>
              <input id="badge-text" type="text" name="badge_text" value="<?= e($slider['badge_text'] ?? '') ?>" maxlength="80" class="admin-input" placeholder="Garowe · Premium Build">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label" for="slider-description">Description</label>
            <textarea id="slider-description" name="description" rows="3" class="admin-input" placeholder="Supporting line under the headline" data-slider-field="description"><?= e($slider['description'] ?? '') ?></textarea>
          </div>
          <div class="admin-form-row admin-form-row--2">
            <div class="admin-form-group">
              <label class="admin-label" for="btn1-text">Primary button text</label>
              <input id="btn1-text" type="text" name="button_text" value="<?= e($slider['button_text'] ?? '') ?>" maxlength="80" class="admin-input" placeholder="Get a Free Quote" data-slider-field="button_text">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn1-link">Primary button URL</label>
              <input id="btn1-link" type="text" name="button_link" value="<?= e($slider['button_link'] ?? '') ?>" maxlength="200" class="admin-input" placeholder="/contact or https://wa.me/...">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn2-text">Secondary button text</label>
              <input id="btn2-text" type="text" name="button_text_2" value="<?= e($slider['button_text_2'] ?? '') ?>" maxlength="80" class="admin-input" placeholder="View Our Work" data-slider-field="button_text_2">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="btn2-link">Secondary button URL</label>
              <input id="btn2-link" type="text" name="button_link_2" value="<?= e($slider['button_link_2'] ?? '') ?>" maxlength="200" class="admin-input" placeholder="/projects">
            </div>
          </div>
        </div>
      </div>

      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-image"></i> Image &amp; video</h4>
        </div>
        <div class="admin-card-body admin-stack">
          <div class="admin-form-group">
            <label class="admin-label">Desktop image</label>
            <p class="admin-table-desc">JPG, PNG or WebP · recommended 1920×1080 · max <?= (int)(UPLOAD_MAX_SIZE / 1048576) ?>MB. Used as the photo, or as the poster if you add a video.</p>
            <label class="admin-image-zone admin-slider-upload admin-hero-upload" for="slider-image-file">
              <img id="slider-img-preview" src="<?= e($imgSrc) ?>" alt="" class="admin-image-preview<?= $imgSrc ? '' : ' is-hidden' ?>">
              <span id="slider-img-placeholder" class="admin-hero-upload__placeholder<?= $imgSrc ? ' is-hidden' : '' ?>">
                <i class="bi bi-cloud-arrow-up"></i>
                <strong>Upload desktop hero image</strong>
                <span>1920×1080 recommended</span>
              </span>
            </label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="admin-file-input" id="slider-image-file" data-preview-target="slider-img-preview" data-preview-placeholder="slider-img-placeholder">
            <?php if ($imgSrc): ?>
            <label class="admin-hero-remove"><input type="checkbox" name="remove_image" value="1"> Remove unused desktop image</label>
            <?php endif; ?>
            <?php $libraryPhotos = array_values(array_filter(heroSliderCatalog(), static fn(array $photo): bool => !empty($photo['image']))); $currentImage = (string)($slider['image'] ?? ''); ?>
            <p class="admin-label" style="margin-top:1rem">Or use a Garowe site photo</p>
            <div class="admin-hero-library" role="group" aria-label="Site photos">
              <?php foreach ($libraryPhotos as $photo): ?>
              <label class="admin-hero-library__item">
                <input type="radio" name="library_image" value="<?= e($photo['image']) ?>" <?= $currentImage === $photo['image'] ? 'checked' : '' ?>>
                <img src="<?= e(asset($photo['image'])) ?>" alt="<?= e($photo['badge_text'] ?? $photo['title']) ?>">
                <span><?= e($photo['badge_text'] ?? 'Site photo') ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Hero video <span class="admin-label-optional">(optional)</span></label>
            <p class="admin-table-desc">MP4 or WebM · muted looping background · max <?= (int)(UPLOAD_MAX_SIZE / 1048576) ?>MB. The desktop image is the poster while the clip loads.</p>
            <label class="admin-image-zone admin-slider-upload admin-hero-upload" for="slider-video-file">
              <video id="slider-video-preview" class="admin-image-preview<?= $videoSrc ? '' : ' is-hidden' ?>" src="<?= e($videoSrc) ?>" muted loop playsinline></video>
              <span id="slider-video-placeholder" class="admin-hero-upload__placeholder<?= $videoSrc ? ' is-hidden' : '' ?>">
                <i class="bi bi-camera-video"></i>
                <strong>Upload hero video</strong>
                <span>MP4 recommended · short clip</span>
              </span>
            </label>
            <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="admin-file-input" id="slider-video-file" data-preview-video="slider-video-preview" data-preview-placeholder="slider-video-placeholder">
            <?php if ($videoSrc): ?>
            <label class="admin-hero-remove"><input type="checkbox" name="remove_video" value="1"> Remove video (keep the photo)</label>
            <?php endif; ?>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Mobile image <span class="admin-label-optional">(optional)</span></label>
            <p class="admin-table-desc">Use a tighter crop for phones. Recommended 1080×1350. If empty, the desktop image is used.</p>
            <label class="admin-image-zone admin-slider-upload admin-hero-upload" for="slider-mobile-file">
              <img id="slider-mobile-preview" src="<?= e($mobileSrc) ?>" alt="" class="admin-image-preview<?= $mobileSrc ? '' : ' is-hidden' ?>">
              <span id="slider-mobile-placeholder" class="admin-hero-upload__placeholder<?= $mobileSrc ? ' is-hidden' : '' ?>">
                <i class="bi bi-phone"></i>
                <strong>Upload mobile crop</strong>
                <span>1080×1350 recommended</span>
              </span>
            </label>
            <input type="file" name="mobile_image" accept="image/jpeg,image/png,image/webp,image/gif" class="admin-file-input" id="slider-mobile-file" data-preview-target="slider-mobile-preview" data-preview-placeholder="slider-mobile-placeholder">
            <?php if ($mobileSrc): ?>
            <label class="admin-hero-remove"><input type="checkbox" name="remove_mobile_image" value="1"> Remove mobile image</label>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="admin-card admin-hero-section-card">
        <div class="admin-card-header admin-hero-section-card__header">
          <h4 class="admin-card-title"><i class="bi bi-palette"></i> Display, motion &amp; schedule</h4>
        </div>
        <div class="admin-card-body">
          <div class="admin-hero-ux-grid">
            <label class="admin-hero-option admin-hero-option--inline">
              <span class="admin-hero-option__copy">
                <strong>Show description</strong>
                <span>Visible under the headline on the homepage.</span>
              </span>
              <span class="toggle-switch">
                <input type="checkbox" name="show_description" value="1" <?= ($slider['show_description'] ?? 1) ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
              </span>
            </label>

            <div class="admin-form-group">
              <label class="admin-label" for="image-focus">Image focal point</label>
              <select id="image-focus" name="image_focus" class="admin-input">
                <?php foreach (['center' => 'Center', 'top' => 'Top (sky & rooflines)', 'bottom' => 'Bottom (ground level)'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= ($slider['image_focus'] ?? 'center') === $val ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="content-style">Content style</label>
              <select id="content-style" name="content_style" class="admin-input">
                <option value="standard" <?= ($slider['content_style'] ?? 'standard') === 'standard' ? 'selected' : '' ?>>Standard</option>
                <option value="minimal" <?= ($slider['content_style'] ?? '') === 'minimal' ? 'selected' : '' ?>>Minimal</option>
                <option value="bold" <?= ($slider['content_style'] ?? '') === 'bold' ? 'selected' : '' ?>>Bold</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="text-align">Text alignment</label>
              <select id="text-align" name="text_align" class="admin-input" data-slider-align>
                <?php foreach (['center', 'left', 'right'] as $a): ?>
                <option value="<?= $a ?>" <?= ($slider['text_align'] ?? 'center') === $a ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="transition-type">Slide transition</label>
              <select id="transition-type" name="transition_type" class="admin-input">
                <option value="inherit" <?= ($slider['transition_type'] ?? 'inherit') === 'inherit' ? 'selected' : '' ?>>Use slider default</option>
                <option value="kenburns" <?= ($slider['transition_type'] ?? '') === 'kenburns' ? 'selected' : '' ?>>Ken Burns</option>
                <option value="fade" <?= ($slider['transition_type'] ?? '') === 'fade' ? 'selected' : '' ?>>Fade</option>
                <option value="slide" <?= ($slider['transition_type'] ?? '') === 'slide' ? 'selected' : '' ?>>Slide</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="autoplay-duration">Display duration (seconds)</label>
              <input id="autoplay-duration" type="number" min="0" max="20" name="autoplay_duration" value="<?= e((string)($slider['autoplay_duration'] ?? '')) ?>" class="admin-input" placeholder="Use slider default">
              <p class="admin-table-desc">Leave empty to use the global duration. Allowed range: 3–20 seconds.</p>
            </div>

            <div class="admin-form-group">
              <label class="admin-label" for="start-date">Publish from</label>
              <input id="start-date" type="datetime-local" name="start_date" value="<?= e($toLocal($slider['start_date'] ?? null)) ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label" for="end-date">Publish until</label>
              <input id="end-date" type="datetime-local" name="end_date" value="<?= e($toLocal($slider['end_date'] ?? null)) ?>" class="admin-input">
            </div>

            <div class="admin-form-group admin-form-group--full">
              <label class="admin-label" for="overlay-range">Image overlay</label>
              <div class="admin-range-row">
                <input type="range" id="overlay-range" min="0" max="1" step="0.05" value="<?= e((string)($slider['overlay_opacity'] ?? 0.6)) ?>" data-slider-opacity-range>
                <input type="number" name="overlay_opacity" id="overlay-opacity" min="0" max="1" step="0.05" value="<?= e((string)($slider['overlay_opacity'] ?? 0.6)) ?>" class="admin-input admin-input--compact" data-slider-opacity-input>
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
              <input type="checkbox" name="is_published" value="1" <?= ($slider['is_published'] ?? 1) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </span>
            <span>
              <strong>Publish on homepage</strong>
              <small>Only published slides in date range appear in the carousel.</small>
            </span>
          </label>

          <button type="submit" class="admin-btn admin-btn-primary admin-btn-lg admin-btn-block">
            <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Save slide' : 'Create slide' ?>
          </button>
        </div>
      </div>
    </aside>
  </form>

  <?php if ($isEdit && !empty($slider['id'])): ?>
  <div class="admin-hero-aside-actions">
    <form action="<?= url('admin/sliders/' . $slider['id'] . '/duplicate') ?>" method="POST" class="admin-inline-form">
      <?= csrf() ?>
      <button type="submit" class="admin-btn admin-btn-secondary admin-btn-block admin-btn-sm">
        <i class="bi bi-copy"></i> Duplicate slide
      </button>
    </form>
    <form action="<?= url('admin/sliders/' . $slider['id'] . '/delete') ?>" method="POST" class="admin-inline-form">
      <?= csrf() ?>
      <button type="submit" data-confirm="Delete this hero slide permanently?" class="admin-btn admin-btn-ghost admin-btn-block admin-btn-sm admin-btn-danger-text">
        <i class="bi bi-trash"></i> Delete slide
      </button>
    </form>
  </div>
  <?php endif; ?>
</div>
