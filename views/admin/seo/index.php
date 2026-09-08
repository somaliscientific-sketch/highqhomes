<?php $pageTitle = 'SEO Settings'; ?>

<div class="admin-settings-layout">
  <aside>
    <div class="admin-card overflow-hidden">
      <div class="admin-side-nav-header">Pages</div>
      <?php foreach ($pages as $pageSlug => $pageLabel): ?>
      <a href="<?= url('admin/seo') ?>?page=<?= $pageSlug ?>" class="admin-side-nav-link <?= $slug === $pageSlug ? 'active' : '' ?>">
        <?= e($pageLabel) ?>
        <?php if ($slug === $pageSlug): ?><i class="bi bi-chevron-right" style="font-size:0.7rem"></i><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <div>
    <div class="admin-card">
      <div class="admin-card-header" style="flex-direction:column;align-items:flex-start">
        <h2 class="admin-card-title">SEO — <?= e($pages[$slug] ?? $slug) ?> Page</h2>
        <p class="admin-table-desc" style="margin-top:0.25rem">URL: <span class="admin-seo-slug"><?= e(url($slug === 'home' ? '' : $slug)) ?></span></p>
      </div>

      <form action="<?= url('admin/seo') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <input type="hidden" name="page_slug" value="<?= e($slug) ?>">

        <div class="admin-form-fields">
          <div class="admin-form-section">
            <h3 class="admin-form-section-title">Meta Tags</h3>
            <div class="admin-form-group">
              <label class="admin-label">Meta Title</label>
              <input type="text" name="meta_title" value="<?= e($current['meta_title'] ?? '') ?>" class="admin-input" placeholder="Page title for search engines (50-60 chars)">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Meta Description</label>
              <textarea name="meta_description" rows="3" class="admin-input admin-textarea" placeholder="Page description for search engines (150-160 chars)"><?= e($current['meta_description'] ?? '') ?></textarea>
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Meta Keywords</label>
              <input type="text" name="meta_keywords" value="<?= e($current['meta_keywords'] ?? '') ?>" class="admin-input" placeholder="keyword1, keyword2, keyword3">
            </div>
          </div>

          <div class="admin-form-section">
            <h3 class="admin-form-section-title">Open Graph (Social Share)</h3>
            <div class="admin-form-group">
              <label class="admin-label">OG Title</label>
              <input type="text" name="og_title" value="<?= e($current['og_title'] ?? '') ?>" class="admin-input" placeholder="Leave blank to use Meta Title">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">OG Description</label>
              <textarea name="og_description" rows="2" class="admin-input admin-textarea" placeholder="Leave blank to use Meta Description"><?= e($current['og_description'] ?? '') ?></textarea>
            </div>
            <div class="admin-form-group">
              <label class="admin-label">OG Image</label>
              <?php if (!empty($current['og_image'])): ?>
              <div class="admin-settings-image-preview" style="margin-bottom:.75rem">
                <img src="<?= e(uploadUrl($current['og_image'])) ?>" alt="" style="max-height:120px;border-radius:8px">
              </div>
              <?php endif; ?>
              <input type="file" name="og_image" accept="image/*" class="admin-input">
              <p class="admin-form-hint">Recommended 1200×630px for social sharing.</p>
            </div>
          </div>

          <?php if ($slug === 'home'): ?>
          <div class="admin-form-section">
            <h3 class="admin-form-section-title">Analytics</h3>
            <div class="admin-form-group">
              <label class="admin-label">Google Analytics ID</label>
              <input type="text" name="google_analytics" value="<?= e($settings['google_analytics'] ?? '') ?>" class="admin-input" placeholder="G-XXXXXXXXXX or UA-XXXXXXX-X">
            </div>
          </div>
          <?php endif; ?>

          <div class="admin-form-group">
            <label class="admin-label">Schema Markup (JSON-LD)</label>
            <textarea name="schema_markup" rows="6" class="admin-input admin-textarea" style="font-family:ui-monospace,monospace;font-size:0.8125rem" placeholder='{"@context":"https://schema.org","@type":"Organization",...}'><?= e($current['schema_markup'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="admin-form-footer">
          <button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-check-lg"></i> Save SEO</button>
        </div>
      </form>
    </div>
  </div>
</div>
