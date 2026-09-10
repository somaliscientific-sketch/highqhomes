<?php
$sectionName = ucwords(str_replace(['_', '-'], ' ', (string)$section['section_key']));
$pageName = ucwords(str_replace(['_', '-'], ' ', (string)$section['page_key']));
$pageTitle = 'Edit ' . $sectionName;
$dataJson = json_encode($section['data'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$pagePathMap = [
    'home'     => '',
    'about'    => 'about',
    'services' => 'services',
    'projects' => 'projects',
    'gallery'  => 'gallery',
    'paints'   => 'paints',
    'contact'  => 'contact',
    'header'   => '',
    'footer'   => '',
];
$previewPath = $pagePathMap[$section['page_key']] ?? $section['page_key'];

$imageUrl = trim((string)($section['image_url'] ?? ''));
$imagePreview = $imageUrl === ''
    ? ''
    : (preg_match('~^https?://~i', $imageUrl) ? $imageUrl : url(ltrim($imageUrl, '/')));

$data = $section['data'] ?? [];

// Detect structured data type
$detectedType = 'empty';
if (is_array($data) && !empty($data)) {
    if (array_is_list($data)) {
        $first = $data[0] ?? null;
        if (is_string($first)) {
            $detectedType = 'strings';
        } elseif (is_array($first)) {
            if (isset($first['q']) || isset($first['a'])) {
                $detectedType = 'faqs';
            } elseif (isset($first['year']) && (isset($first['text']) || isset($first['title']))) {
                $detectedType = 'timeline';
            } elseif (isset($first['img']) || isset($first['href']) || isset($first['mod'])) {
                $detectedType = 'bento';
            } elseif (
                isset($first['num'])
                && (isset($first['label']) || isset($first['suffix']) || isset($first['icon']))
                && !isset($first['title'])
                && !isset($first['text'])
            ) {
                $detectedType = 'stats';
            } elseif (isset($first['num'])) {
                $detectedType = 'steps';
            } elseif (isset($first['label']) && (isset($first['value']) || isset($first['num']))) {
                $detectedType = 'facts';
            } elseif (isset($first['title']) || isset($first['text']) || isset($first['icon'])) {
                $detectedType = 'cards';
            } else {
                $detectedType = 'custom_list';
            }
        }
    } else {
        $detectedType = 'map';
    }
}
?>

<div class="admin-section-editor-head">
  <div>
    <a href="<?= url('admin/sections?page=' . urlencode($section['page_key'])) ?>" class="admin-section-editor-head__back">
      <i class="bi bi-arrow-left"></i> <?= e($pageName) ?> sections
    </a>
    <h2><?= e($sectionName) ?></h2>
    <p>Page: <strong><?= e($pageName) ?></strong> &middot; Key: <code><?= e($section['section_key']) ?></code></p>
    <?php
      $sectionHints = [
        'home.hero' => 'Hero slides stay in Hero Slider. Here you control the live badge, metric cards, and whether the banner is visible.',
        'home.hero_trust' => 'Edit the scrolling trust badges shown under the homepage hero.',
        'home.about_highlights' => 'Controls the Who We Are copy, highlight cards, and the featured photo.',
        'home.capabilities' => 'Controls the What We Build bento cards, including image, icon, and link.',
        'home.projects' => 'Project cards come from the Projects module. Here you control headings, button labels, and the bottom band.',
        'home.why_us' => 'Controls the Why Choose Us heading and principle cards.',
        'home.process' => 'Controls the delivery steps from discovery to handover.',
        'home.excellence' => 'Controls the excellence pillars. Extra JSON keys: checklist (list) and cta_label.',
        'home.connect' => 'Controls the Start With Confidence copy and promise cards. Phone and email come from Settings.',
        'home.stats' => 'Controls the achievement counters shown on the homepage.',
        'home.testimonials' => 'Reviews come from the Testimonials module. Here you control headings and chips.',
        'home.faq' => 'Controls FAQ questions and answers. Extra JSON key: chips.',
        'home.cta' => 'Controls the final call-to-action copy, background image, and button labels.',
        'about.hero' => 'Controls the About hero headline, lead, photo, trust chips, snapshot title, and button labels.',
        'about.history' => 'Controls the Our Story copy, photo, and company facts. Extra JSON keys: facts, cta_label, badge_label.',
        'about.timeline' => 'Controls the milestone years shown under Our Story.',
        'about.stats' => 'Controls the highlight counters on the About page.',
        'about.mission' => 'Controls the Mission and Vision cards, including eyebrow, title, and body copy.',
        'about.values' => 'Controls the values heading and principle cards.',
        'about.process' => 'Controls the How We Work heading and delivery steps.',
        'about.team' => 'Team members come from the Team module. Here you control the section heading and visibility.',
        'about.testimonials' => 'Reviews come from the Testimonials module. Here you control the Client Voices heading and visibility.',
        'about.cta' => 'Controls the About page call-to-action copy and button labels.',
        'services.hero' => 'Controls the Services hero headline, lead, photo, and trust badges.',
        'services.intro' => 'Controls the highlights heading. Stats come from Settings / live counts.',
        'services.pillars' => 'Controls the four delivery-pillar cards.',
        'services.catalog' => 'Controls the service catalog heading. Cards come from the Services module.',
        'services.scope' => 'Controls scope copy. Extra JSON keys: checklist (list), cards (list), cta_label.',
        'services.process' => 'Controls delivery steps from consultation to handover.',
        'services.faq' => 'Controls service FAQs shown on this page.',
        'services.cta' => 'Controls the Services CTA. Extra JSON keys: cta_primary, cta_secondary.',
        'projects.hero' => 'Controls the Projects hero headline, lead, and photo.',
        'projects.intro' => 'Controls the portfolio intro. Counts come from live project data.',
        'projects.pillars' => 'Controls the delivery-principle cards.',
        'projects.catalog' => 'Controls the portfolio grid heading. Cards come from the Projects module.',
        'projects.approach' => 'Controls the discovery-to-handover steps.',
        'projects.cta' => 'Controls the Projects CTA. Extra JSON keys: cta_primary, cta_secondary.',
        'gallery.hero' => 'Controls the Gallery hero headline, lead, and photo.',
        'gallery.intro' => 'Controls the gallery intro. Counts come from live gallery data.',
        'gallery.highlights' => 'Controls the highlight cards above the photo grid.',
        'gallery.catalog' => 'Controls the photo grid heading. Images come from the Gallery module.',
        'gallery.cta' => 'Controls the Gallery CTA. Extra JSON keys: cta_primary, cta_secondary.',
        'paints.hero' => 'Controls the Products hero headline, lead, and photo.',
        'paints.intro' => 'Controls the finishing intro. Counts come from live product data.',
        'paints.benefits' => 'Controls the benefit cards.',
        'paints.catalog' => 'Controls the product grid heading. Cards come from the Paints module.',
        'paints.guide' => 'Controls the buying guide. Extra JSON keys: checklist (list), cards (list).',
        'paints.cta' => 'Controls the Products CTA. Extra JSON keys: cta_primary, cta_secondary.',
        'contact.hero' => 'Controls the Contact hero headline, lead, and photo.',
        'contact.form' => 'Controls the inquiry form heading. Extra JSON key: submit_label. The form itself always stays live.',
        'contact.process' => 'Controls the response-step cards beside the form.',
        'contact.map' => 'Controls the office map heading. Address and embed come from Settings.',
        'contact.cta' => 'Controls the Contact WhatsApp CTA. Extra JSON keys: cta_primary, cta_secondary.',
      ];
      $hintKey = ($section['page_key'] ?? '') . '.' . ($section['section_key'] ?? '');
      $sectionHint = $sectionHints[$hintKey] ?? '';
    ?>
    <?php if ($sectionHint !== ''): ?>
    <p class="admin-section-editor-head__hint"><?= e($sectionHint) ?></p>
    <?php endif; ?>
  </div>
  <div class="admin-section-editor-head__actions">
    <a href="<?= url($previewPath) ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
      <i class="bi bi-box-arrow-up-right"></i> Preview on website
    </a>
  </div>
</div>

<form action="<?= url('admin/sections/' . $section['id'] . '/edit') ?>" method="POST" class="admin-section-editor" id="section-form">
  <?= csrf() ?>
  <main class="admin-section-editor__main">
    <!-- Primary Content Panel -->
    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-type"></i></span>
        <div>
          <h3>Section Content</h3>
          <p>Text displayed to website visitors.</p>
        </div>
      </header>
      <div class="admin-section-editor__body">
        <div class="admin-form-group">
          <label class="admin-label" for="section-title">
            Eyebrow / Kicker <small>Optional short label or badge above headline</small>
          </label>
          <input id="section-title" class="admin-input" name="title" value="<?= e($section['title'] ?? '') ?>" placeholder="For example: Why Choose Us, Our Portfolio, Built on Trust">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="section-subtitle">Headline / Subtitle</label>
          <input id="section-subtitle" class="admin-input admin-input--prominent" name="subtitle" value="<?= e($section['subtitle'] ?? '') ?>" placeholder="Main section headline">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="section-content">Description / Lead Copy <small>Supporting paragraph</small></label>
          <textarea id="section-content" class="admin-input" name="content" rows="4" placeholder="Explain the section value clearly."><?= e($section['content'] ?? '') ?></textarea>
        </div>
      </div>
    </section>

    <!-- Structured Content / Visual Item Builder Panel -->
    <section class="admin-card admin-section-editor__panel">
      <header class="admin-section-panel-tabs-head">
        <div class="admin-section-panel-tabs-head__title">
          <span><i class="bi bi-list-stars"></i></span>
          <div>
            <h3>Structured Items</h3>
            <p>Cards, steps, FAQs, trust badges, and lists.</p>
          </div>
        </div>
        <div class="admin-tabs-nav" role="tablist">
          <button type="button" class="admin-tab-btn is-active" data-tab-target="#tab-visual">
            <i class="bi bi-ui-checks"></i> Visual Editor
          </button>
          <button type="button" class="admin-tab-btn" data-tab-target="#tab-json">
            <i class="bi bi-code-slash"></i> Advanced JSON
          </button>
        </div>
      </header>

      <div class="admin-section-editor__body">
        <!-- Visual Editor Tab -->
        <div id="tab-visual" class="admin-tab-pane is-active">
          <div id="visual-builder" data-detected-type="<?= e($detectedType) ?>">
            <!-- Visual items rendered by JavaScript from section-data JSON -->
            <div id="visual-items-container" class="admin-visual-items"></div>

            <div class="admin-visual-builder-actions">
              <button type="button" id="btn-add-item" class="admin-btn admin-btn-secondary">
                <i class="bi bi-plus-lg"></i> Add Item
              </button>
              <div class="admin-template-presets">
                <span class="admin-text-muted">Template presets:</span>
                <button type="button" class="admin-btn-pill" data-preset="cards"><i class="bi bi-grid-1x2"></i> Cards</button>
                <button type="button" class="admin-btn-pill" data-preset="bento"><i class="bi bi-columns-gap"></i> Bento</button>
                <button type="button" class="admin-btn-pill" data-preset="steps"><i class="bi bi-list-ol"></i> Steps</button>
                <button type="button" class="admin-btn-pill" data-preset="faqs"><i class="bi bi-patch-question"></i> FAQs</button>
                <button type="button" class="admin-btn-pill" data-preset="strings"><i class="bi bi-tags"></i> Badges</button>
                <button type="button" class="admin-btn-pill" data-preset="stats"><i class="bi bi-graph-up"></i> Stats</button>
                <button type="button" class="admin-btn-pill" data-preset="timeline"><i class="bi bi-clock-history"></i> Timeline</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Raw JSON Tab -->
        <div id="tab-json" class="admin-tab-pane">
          <div class="admin-form-group">
            <label class="admin-label" for="section-data">Raw JSON</label>
            <textarea id="section-data" class="admin-input admin-code admin-section-json" name="data_json" rows="12" spellcheck="false"><?= e($dataJson ?: '[]') ?></textarea>
            <div class="admin-section-note">
              <i class="bi bi-info-circle"></i>
              <span>Changes in the Visual Editor automatically update this JSON, and vice versa.</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <aside class="admin-section-editor__aside">
    <!-- Publishing Panel -->
    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-send-check"></i></span>
        <div>
          <h3>Visibility &amp; Order</h3>
          <p>Control live status on the website.</p>
        </div>
      </header>
      <div class="admin-section-editor__body">
        <label class="admin-section-publish">
          <span>
            <strong>Visible on website</strong>
            <small>Turn off to hide this section from visitors</small>
          </span>
          <input type="checkbox" name="is_enabled" value="1" <?= ($section['is_enabled'] ?? 1) ? 'checked' : '' ?>>
          <i aria-hidden="true"></i>
        </label>
        <div class="admin-form-group admin-mt-md">
          <label class="admin-label" for="section-order">Display Order</label>
          <input id="section-order" class="admin-input" type="number" name="sort_order" min="0" step="1" value="<?= (int)($section['sort_order'] ?? 0) ?>">
          <p class="admin-help">Lower numbers appear first on the page.</p>
        </div>
        <dl class="admin-section-meta">
          <div><dt>Page</dt><dd><?= e($pageName) ?></dd></div>
          <div><dt>Section Key</dt><dd><code><?= e($section['section_key']) ?></code></dd></div>
        </dl>
      </div>
    </section>

    <!-- Image / Media Panel -->
    <section class="admin-card admin-section-editor__panel">
      <header>
        <span><i class="bi bi-image"></i></span>
        <div>
          <h3>Supporting Media</h3>
          <p>Banner, background, or featured image.</p>
        </div>
      </header>
      <div class="admin-section-editor__body">
        <div class="admin-section-image-preview-wrap" id="image-preview-container" <?= $imagePreview === '' ? 'style="display:none;"' : '' ?>>
          <img id="admin-image-preview" src="<?= e($imagePreview) ?>" alt="" loading="lazy">
          <button type="button" id="btn-clear-image" class="admin-image-clear-btn" title="Remove image">&times;</button>
        </div>

        <div class="admin-form-group">
          <label class="admin-label" for="section-image">Image URL</label>
          <div class="admin-input-btn-group">
            <input id="section-image" class="admin-input" name="image_url" value="<?= e($imageUrl) ?>" placeholder="uploads/media/... or https://...">
            <button type="button" class="admin-btn admin-btn-secondary" id="btn-open-media-modal" title="Choose from Media Library">
              <i class="bi bi-folder2-open"></i> Browse
            </button>
          </div>
          <p class="admin-help">Choose from your media library or enter an image URL.</p>
        </div>
      </div>
    </section>
  </aside>

  <footer class="admin-section-editor__actions admin-section-editor__actions--sticky">
    <p class="admin-section-editor__save-hint"><i class="bi bi-keyboard"></i> Press <kbd>Ctrl</kbd> + <kbd>S</kbd> to save</p>
    <a href="<?= url('admin/sections?page=' . urlencode($section['page_key'])) ?>" class="admin-btn admin-btn-secondary">Back to list</a>
    <button class="admin-btn admin-btn-primary" type="submit" id="btn-save-section"><i class="bi bi-check2"></i> Save section</button>
  </footer>
</form>

<!-- Media Picker Modal -->
<div id="media-picker-modal" class="admin-modal" role="dialog" aria-modal="true" aria-label="Media Library Picker" hidden>
  <div class="admin-modal__backdrop" data-close-modal></div>
  <div class="admin-modal__dialog admin-modal__dialog--lg">
    <header class="admin-modal__header">
      <div class="admin-modal__title">
        <i class="bi bi-images"></i>
        <span>Select Media from Library</span>
      </div>
      <button type="button" class="admin-modal__close" data-close-modal aria-label="Close">&times;</button>
    </header>
    <div class="admin-modal__body">
      <?php if (!empty($mediaImages)): ?>
      <div class="admin-media-picker-grid">
        <?php foreach ($mediaImages as $media): ?>
        <?php
          $mediaUrl = uploadUrl($media['file_path']);
        ?>
        <button type="button" class="admin-media-picker-item" data-media-url="<?= e($media['file_path']) ?>" data-full-url="<?= e($mediaUrl) ?>">
          <img src="<?= e($mediaUrl) ?>" alt="<?= e($media['alt_text'] ?? $media['title'] ?? '') ?>" loading="lazy">
          <span class="admin-media-picker-title"><?= e(truncate($media['title'] ?: basename($media['file_path']), 24)) ?></span>
        </button>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="admin-empty admin-empty--compact">
        <i class="bi bi-image"></i>
        <p>No media files found. Upload images in the Media Library first.</p>
      </div>
      <?php endif; ?>
    </div>
    <footer class="admin-modal__footer">
      <a href="<?= url('admin/media') ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
        <i class="bi bi-upload"></i> Upload New in Media Library
      </a>
      <button type="button" class="admin-btn admin-btn-secondary" data-close-modal>Close</button>
    </footer>
  </div>
</div>
