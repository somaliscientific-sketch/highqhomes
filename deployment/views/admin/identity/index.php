<?php
$pageTitle = 'Site Identity';
$logoSrc = !empty($identity['logo']) ? uploadUrl($identity['logo']) : asset('images/logo-header-icon.png');
$footerLogoSrc = !empty($identity['footer_logo']) ? uploadUrl($identity['footer_logo']) : asset('images/logo-footer-icon.png');
$faviconSrc = !empty($identity['favicon']) ? uploadUrl($identity['favicon']) : asset('favicon.svg');
$nameParts = preg_split('/\s+/', trim($identity['site_name'] ?: 'HighQ Homes'), 2);
?>

<div class="admin-identity" data-identity-form>
  <header class="admin-identity__head">
    <div>
      <span class="admin-report-kicker">Brand</span>
      <h2>Site identity</h2>
      <p>Name, logos, favicon, and brand colors — shown across the header, footer, and browser tab.</p>
    </div>
  </header>

  <form action="<?= url('admin/identity') ?>" method="POST" enctype="multipart/form-data" class="admin-identity__form">
    <?= csrf() ?>

    <div class="admin-identity__layout">
      <div class="admin-identity__main">
        <section class="admin-card admin-identity-card">
          <div class="admin-card-header">
            <h3 class="admin-card-title"><i class="bi bi-badge-ad"></i> Brand name</h3>
          </div>
          <div class="admin-card-body admin-stack">
            <div class="admin-form-group">
              <label class="admin-label" for="site_name">Site name *</label>
              <input type="text" id="site_name" name="site_name" value="<?= e($identity['site_name']) ?>" required class="admin-input admin-input--lg" placeholder="HighQ Homes" data-identity-field="site_name">
              <p class="admin-form-hint">Shown in the header logo lockup and page titles.</p>
            </div>
            <div class="admin-form-row admin-form-row--2">
              <div class="admin-form-group">
                <label class="admin-label" for="tagline">Tagline</label>
                <input type="text" id="tagline" name="tagline" value="<?= e($identity['tagline']) ?>" class="admin-input" placeholder="Broad Vision · Honest Service · Great Value" data-identity-field="tagline">
              </div>
              <div class="admin-form-group">
                <label class="admin-label" for="legal_name">Legal / copyright name</label>
                <input type="text" id="legal_name" name="legal_name" value="<?= e($identity['legal_name']) ?>" class="admin-input" placeholder="HighQ Homes Ltd">
              </div>
            </div>
          </div>
        </section>

        <section class="admin-card admin-identity-card">
          <div class="admin-card-header">
            <h3 class="admin-card-title"><i class="bi bi-images"></i> Logos &amp; icon</h3>
          </div>
          <div class="admin-card-body">
            <div class="admin-identity-uploads">
              <div class="admin-identity-upload">
                <label class="admin-label" for="logo">Header logo mark</label>
                <label class="admin-identity-drop" for="logo">
                  <img src="<?= e($logoSrc) ?>" alt="" id="preview-logo" data-identity-preview="logo">
                  <span><i class="bi bi-cloud-arrow-up"></i> Replace</span>
                </label>
                <input type="file" name="logo" id="logo" accept="image/*" class="admin-file-input" data-preview-target="preview-logo">
                <p class="admin-form-hint">PNG/SVG with transparent background · ~120×100px</p>
              </div>

              <div class="admin-identity-upload">
                <label class="admin-label" for="footer_logo">Footer logo mark</label>
                <label class="admin-identity-drop admin-identity-drop--dark" for="footer_logo">
                  <img src="<?= e($footerLogoSrc) ?>" alt="" id="preview-footer_logo" data-identity-preview="footer_logo">
                  <span><i class="bi bi-cloud-arrow-up"></i> Replace</span>
                </label>
                <input type="file" name="footer_logo" id="footer_logo" accept="image/*" class="admin-file-input" data-preview-target="preview-footer_logo">
                <p class="admin-form-hint">Light/white version for dark footer · optional</p>
              </div>

              <div class="admin-identity-upload">
                <label class="admin-label" for="favicon">Favicon</label>
                <label class="admin-identity-drop admin-identity-drop--favicon" for="favicon">
                  <img src="<?= e($faviconSrc) ?>" alt="" id="preview-favicon" data-identity-preview="favicon">
                  <span><i class="bi bi-cloud-arrow-up"></i> Replace</span>
                </label>
                <input type="file" name="favicon" id="favicon" accept="image/*,.ico" class="admin-file-input" data-preview-target="preview-favicon">
                <p class="admin-form-hint">Square · 32×32 or 64×64 PNG/ICO/SVG</p>
              </div>
            </div>
          </div>
        </section>

        <section class="admin-card admin-identity-card">
          <div class="admin-card-header">
            <h3 class="admin-card-title"><i class="bi bi-palette"></i> Brand colors</h3>
          </div>
          <div class="admin-card-body">
            <div class="admin-identity-colors">
              <div class="admin-form-group">
                <label class="admin-label" for="primary_color">Primary (navy)</label>
                <div class="admin-color-input">
                  <input type="color" id="primary_color_picker" value="<?= e($identity['primary_color']) ?>" data-sync-color="primary_color">
                  <input type="text" name="primary_color" id="primary_color" value="<?= e($identity['primary_color']) ?>" class="admin-input" data-identity-field="primary_color">
                </div>
              </div>
              <div class="admin-form-group">
                <label class="admin-label" for="accent_color">Accent (gold)</label>
                <div class="admin-color-input">
                  <input type="color" id="accent_color_picker" value="<?= e($identity['accent_color']) ?>" data-sync-color="accent_color">
                  <input type="text" name="accent_color" id="accent_color" value="<?= e($identity['accent_color']) ?>" class="admin-input" data-identity-field="accent_color">
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <aside class="admin-identity__aside">
        <div class="admin-card admin-identity-preview-card">
          <div class="admin-card-header">
            <h3 class="admin-card-title">Live preview</h3>
          </div>
          <div class="admin-card-body">
            <div class="admin-identity-preview" data-identity-preview-root style="--preview-navy: <?= e($identity['primary_color']) ?>; --preview-gold: <?= e($identity['accent_color']) ?>;">
              <div class="admin-identity-preview__header">
                <div class="admin-identity-preview__brand">
                  <img src="<?= e($logoSrc) ?>" alt="" data-identity-live="logo">
                  <span data-identity-live="site_name">
                    <?php if (count($nameParts) === 2): ?>
                    <span class="admin-identity-preview__word admin-identity-preview__word--primary"><?= e($nameParts[0]) ?></span><span class="admin-identity-preview__word admin-identity-preview__word--accent"><?= e($nameParts[1]) ?></span>
                    <?php else: ?>
                    <?= e($identity['site_name'] ?: 'Site Name') ?>
                    <?php endif; ?>
                  </span>
                </div>
                <span class="admin-identity-preview__cta">Get a Quote</span>
              </div>
              <div class="admin-identity-preview__footer">
                <img src="<?= e($footerLogoSrc) ?>" alt="" data-identity-live="footer_logo">
                <p data-identity-live="tagline"><?= e($identity['tagline'] ?: 'Your tagline appears here') ?></p>
              </div>
              <div class="admin-identity-preview__tab">
                <img src="<?= e($faviconSrc) ?>" alt="" width="16" height="16" data-identity-live="favicon">
                <span data-identity-live="tab_title"><?= e(truncate($identity['site_name'] ?: 'Site', 24)) ?></span>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary admin-btn-lg admin-btn-block">
          <i class="bi bi-check-lg"></i> Save site identity
        </button>
      </aside>
    </div>
  </form>
</div>

<script>
(function () {
  const root = document.querySelector('[data-identity-form]');
  if (!root) return;

  const previewRoot = root.querySelector('[data-identity-preview-root]');
  const siteNameEl = root.querySelector('[data-identity-live="site_name"]');
  const taglineEl = root.querySelector('[data-identity-live="tagline"]');
  const tabTitleEl = root.querySelector('[data-identity-live="tab_title"]');

  const renderSiteName = (raw) => {
    const parts = raw.trim().split(/\s+/);
    if (parts.length >= 2 && siteNameEl) {
      const primary = parts.slice(0, -1).join(' ');
      const accent = parts[parts.length - 1];
      siteNameEl.innerHTML = `<span class="admin-identity-preview__word admin-identity-preview__word--primary">${primary}</span><span class="admin-identity-preview__word admin-identity-preview__word--accent">${accent}</span>`;
    } else if (siteNameEl) {
      siteNameEl.textContent = raw || 'Site Name';
    }
    if (tabTitleEl) tabTitleEl.textContent = (raw || 'Site').slice(0, 24);
  };

  root.querySelector('#site_name')?.addEventListener('input', (e) => renderSiteName(e.target.value));
  root.querySelector('#tagline')?.addEventListener('input', (e) => {
    if (taglineEl) taglineEl.textContent = e.target.value || 'Your tagline appears here';
  });

  root.querySelectorAll('[data-sync-color]').forEach((picker) => {
    const targetId = picker.getAttribute('data-sync-color');
    const text = root.querySelector(`#${targetId}`);
    picker.addEventListener('input', () => {
      if (text) text.value = picker.value.toUpperCase();
      if (previewRoot) {
        previewRoot.style.setProperty(targetId === 'primary_color' ? '--preview-navy' : '--preview-gold', picker.value);
      }
    });
    text?.addEventListener('input', () => {
      if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) {
        picker.value = text.value;
        if (previewRoot) {
          previewRoot.style.setProperty(targetId === 'primary_color' ? '--preview-navy' : '--preview-gold', text.value);
        }
      }
    });
  });

  root.querySelectorAll('[data-preview-target]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = () => {
        const preview = document.getElementById(input.dataset.previewTarget || '');
        if (preview) preview.src = reader.result;
        const key = input.name;
        const live = root.querySelector(`[data-identity-live="${key}"]`);
        if (live) live.src = reader.result;
      };
      reader.readAsDataURL(file);
    });
  });
})();
</script>
