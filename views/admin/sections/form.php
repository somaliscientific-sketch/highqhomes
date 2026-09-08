<?php
$sectionName = ucwords(str_replace(['_', '-'], ' ', (string)$section['section_key']));
$pageName = ucwords(str_replace(['_', '-'], ' ', (string)$section['page_key']));
$pageTitle = 'Edit ' . $sectionName;
$dataJson = json_encode($section['data'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$previewPath = in_array($section['page_key'], ['header', 'footer'], true) ? '' : $section['page_key'];
$imageUrl = trim((string)($section['image_url'] ?? ''));
$imagePreview = $imageUrl === ''
    ? ''
    : (preg_match('~^https?://~i', $imageUrl) ? $imageUrl : url(ltrim($imageUrl, '/')));
?>

<div class="admin-section-editor-head">
  <div>
    <a href="<?= url('admin/sections?page=' . urlencode($section['page_key'])) ?>" class="admin-section-editor-head__back">
      <i class="bi bi-arrow-left"></i> <?= e($pageName) ?> sections
    </a>
    <h2><?= e($sectionName) ?></h2>
    <p>Update this section's public content and publishing settings.</p>
  </div>
  <a href="<?= url($previewPath) ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
    <i class="bi bi-box-arrow-up-right"></i> Preview page
  </a>
</div>

<form action="<?= url('admin/sections/' . $section['id'] . '/edit') ?>" method="POST" class="admin-section-editor">
  <?= csrf() ?>
  <main class="admin-section-editor__main">
    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-type"></i></span>
        <div><h3>Section content</h3><p>The primary text visitors see on the website.</p></div>
      </header>
      <div class="admin-section-editor__body">
        <div class="admin-form-group">
          <label class="admin-label" for="section-title">Eyebrow <small>Optional short label</small></label>
          <input id="section-title" class="admin-input" name="title" value="<?= e($section['title'] ?? '') ?>" placeholder="For example: What we do">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="section-subtitle">Headline</label>
          <input id="section-subtitle" class="admin-input admin-input--prominent" name="subtitle" value="<?= e($section['subtitle'] ?? '') ?>" placeholder="Enter the section headline">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="section-content">Description <small>Supporting copy</small></label>
          <textarea id="section-content" class="admin-input" name="content" rows="6" placeholder="Explain this section clearly and concisely."><?= e($section['content'] ?? '') ?></textarea>
        </div>
      </div>
    </section>

    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-list-nested"></i></span>
        <div><h3>Structured content</h3><p>Lists, cards, steps and other advanced section data.</p></div>
      </header>
      <div class="admin-section-editor__body">
        <div class="admin-form-group">
          <label class="admin-label" for="section-data">Section data <small>Valid JSON</small></label>
          <textarea id="section-data" class="admin-input admin-code admin-section-json" name="data_json" rows="15" spellcheck="false"><?= e($dataJson ?: '[]') ?></textarea>
          <div class="admin-section-note">
            <i class="bi bi-info-circle"></i>
            <span>Keep property names unchanged. Use this area for repeated items such as cards, steps, FAQs and trust badges.</span>
          </div>
        </div>
      </div>
    </section>
  </main>

  <aside class="admin-section-editor__aside">
    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-send-check"></i></span>
        <div><h3>Publishing</h3><p>Control placement and visibility.</p></div>
      </header>
      <div class="admin-section-editor__body">
        <label class="admin-section-publish">
          <span>
            <strong>Visible on website</strong>
            <small>Visitors can see this section</small>
          </span>
          <input type="checkbox" name="is_enabled" value="1" <?= ($section['is_enabled'] ?? 1) ? 'checked' : '' ?>>
          <i aria-hidden="true"></i>
        </label>
        <div class="admin-form-group">
          <label class="admin-label" for="section-order">Display order</label>
          <input id="section-order" class="admin-input" type="number" name="sort_order" min="0" step="1" value="<?= (int)($section['sort_order'] ?? 0) ?>">
          <p class="admin-help">Lower numbers appear first.</p>
        </div>
        <dl class="admin-section-meta">
          <div><dt>Page</dt><dd><?= e($pageName) ?></dd></div>
          <div><dt>Section key</dt><dd><code><?= e($section['section_key']) ?></code></dd></div>
        </dl>
      </div>
    </section>

    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-image"></i></span>
        <div><h3>Section image</h3><p>Optional supporting media.</p></div>
      </header>
      <?php if ($imagePreview !== ''): ?>
      <div class="admin-section-image-preview">
        <img src="<?= e($imagePreview) ?>" alt="" loading="lazy">
      </div>
      <?php endif; ?>
      <div class="admin-section-editor__body">
        <div class="admin-form-group">
          <label class="admin-label" for="section-image">Image URL</label>
          <input id="section-image" class="admin-input" name="image_url" value="<?= e($imageUrl) ?>" placeholder="uploads/media/image.jpg">
          <p class="admin-help">Choose media from the library, then paste its URL here.</p>
        </div>
        <a href="<?= url('admin/media') ?>" class="admin-btn admin-btn-secondary admin-btn-block">
          <i class="bi bi-folder2-open"></i> Open media library
        </a>
      </div>
    </section>
  </aside>

  <footer class="admin-section-editor__actions">
    <a href="<?= url('admin/sections?page=' . urlencode($section['page_key'])) ?>" class="admin-btn admin-btn-secondary">Cancel</a>
    <button class="admin-btn admin-btn-primary" type="submit"><i class="bi bi-check2"></i> Save section</button>
  </footer>
</form>
