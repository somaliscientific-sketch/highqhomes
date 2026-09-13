<?php
$pageTitle = 'Page Sections';
$pageLabel = $pages[$pageKey] ?? ucfirst($pageKey);
$sectionCount = count($sections);
$enabledCount = count(array_filter($sections, static fn(array $section): bool => (bool)($section['is_enabled'] ?? false)));
$hiddenCount = $sectionCount - $enabledCount;

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
$previewPath = $pagePathMap[$pageKey] ?? $pageKey;

$pageIconMap = [
    'home'     => 'bi-house-door',
    'about'    => 'bi-info-circle',
    'services' => 'bi-grid-3x3-gap',
    'projects' => 'bi-building',
    'gallery'  => 'bi-images',
    'paints'   => 'bi-palette',
    'contact'  => 'bi-telephone',
    'header'   => 'bi-window-dock',
    'footer'   => 'bi-window-stack',
];

$sectionLabels = [
    'hero'             => 'Hero Banner',
    'hero_trust'       => 'Trust Indicators Bar',
    'about'            => 'About Section',
    'about_highlights' => 'Who We Are / Highlights',
    'capabilities'     => 'What We Build / Capabilities',
    'services'         => 'Services',
    'projects'         => 'Projects Portfolio',
    'why_us'           => 'Why Choose Us',
    'why_choose_us'    => 'Why Choose Us',
    'process'          => 'Our Process / Delivery Steps',
    'excellence'       => 'Excellence Pillars',
    'connect'          => 'Connect / Trust Promises',
    'stats'            => 'Statistics / Proof',
    'testimonials'     => 'Testimonials',
    'team'             => 'Team',
    'gallery'          => 'Gallery',
    'faq'              => 'Frequently Asked Questions',
    'cta'              => 'Call to Action (CTA)',
    'contact'          => 'Contact Section',
    'history'          => 'Company History & Timeline',
    'timeline'         => 'Company Timeline',
    'mission'          => 'Mission & Vision',
    'values'           => 'Core Values & Principles',
    'intro'            => 'Page Introduction & Stats',
    'pillars'          => 'Engineering Pillars',
    'approach'         => 'Execution Approach',
    'highlights'       => 'Visual Highlights',
    'benefits'         => 'Product Benefits',
    'topbar'           => 'Top Info & Announcement Bar',
    'catalog'          => 'Catalog Heading',
    'scope'            => 'Project Scope',
    'guide'            => 'Buying Guide',
    'form'             => 'Inquiry Form Heading',
    'map'              => 'Office Map',
];

if ($pageKey === 'home') {
    $sectionLabels['hero'] = 'Homepage Hero Banner';
} elseif ($pageKey === 'about') {
    $sectionLabels['hero'] = 'About Hero Banner';
    $sectionLabels['history'] = 'Our Story & Company Facts';
    $sectionLabels['timeline'] = 'Company Timeline';
    $sectionLabels['stats'] = 'Company Highlights / Stats';
    $sectionLabels['mission'] = 'Mission & Vision';
    $sectionLabels['values'] = 'Core Values & Principles';
    $sectionLabels['process'] = 'How We Work';
    $sectionLabels['team'] = 'Leadership Team';
    $sectionLabels['testimonials'] = 'Client Voices';
    $sectionLabels['cta'] = 'About Page CTA';
} elseif ($pageKey === 'services') {
    $sectionLabels['hero'] = 'Services Hero Banner';
    $sectionLabels['intro'] = 'Highlights & Stats';
    $sectionLabels['pillars'] = 'Delivery Pillars';
    $sectionLabels['catalog'] = 'Service Catalog Heading';
    $sectionLabels['scope'] = 'Project Scope';
    $sectionLabels['process'] = 'How We Deliver';
    $sectionLabels['faq'] = 'Service FAQs';
    $sectionLabels['cta'] = 'Services CTA';
} elseif ($pageKey === 'projects') {
    $sectionLabels['hero'] = 'Projects Hero Banner';
    $sectionLabels['intro'] = 'Portfolio Intro';
    $sectionLabels['pillars'] = 'Delivery Principles';
    $sectionLabels['catalog'] = 'Portfolio Grid Heading';
    $sectionLabels['approach'] = 'Execution Approach';
    $sectionLabels['cta'] = 'Projects CTA';
} elseif ($pageKey === 'gallery') {
    $sectionLabels['hero'] = 'Gallery Hero Banner';
    $sectionLabels['intro'] = 'Gallery Intro';
    $sectionLabels['highlights'] = 'Visual Highlights';
    $sectionLabels['catalog'] = 'Photo Grid Heading';
    $sectionLabels['cta'] = 'Gallery CTA';
} elseif ($pageKey === 'paints') {
    $sectionLabels['hero'] = 'Products Hero Banner';
    $sectionLabels['intro'] = 'Finishing Intro';
    $sectionLabels['benefits'] = 'Product Benefits';
    $sectionLabels['catalog'] = 'Product Grid Heading';
    $sectionLabels['guide'] = 'Choosing the Right Coating';
    $sectionLabels['cta'] = 'Products CTA';
} elseif ($pageKey === 'contact') {
    $sectionLabels['hero'] = 'Contact Hero Banner';
    $sectionLabels['form'] = 'Inquiry Form Heading';
    $sectionLabels['process'] = 'What Happens Next';
    $sectionLabels['map'] = 'Office Map';
    $sectionLabels['cta'] = 'Contact CTA';
}
?>

<section class="admin-sections-hero">
  <div>
    <span class="admin-sections-hero__eyebrow"><i class="bi bi-layout-text-window-reverse"></i> Website Content &amp; Layout CMS</span>
    <h2>Manage <?= e($pageLabel) ?></h2>
    <p>Control the headings, descriptions, images, structured items, and live visibility of each section on the <?= strtolower(e($pageLabel)) ?>.</p>
    <?php if ($pageKey === 'home'): ?>
    <p>Every homepage block is listed below — hero badge, trust bar, about, capabilities, portfolio copy, why us, process, excellence, connect, stats, testimonials, FAQ, and the final CTA.</p>
    <?php elseif ($pageKey === 'about'): ?>
    <p>Every About page block is listed below — hero, story, timeline, stats, mission &amp; vision, values, process, leadership, client voices, and the final CTA.</p>
    <?php endif; ?>
  </div>
  <a href="<?= url($previewPath) ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
    <i class="bi bi-box-arrow-up-right"></i> Preview on website
  </a>
</section>

<div class="admin-sections-summary" aria-label="Section summary">
  <article>
    <span class="admin-sections-summary__icon"><i class="bi bi-grid-1x2"></i></span>
    <div><strong><?= $sectionCount ?></strong><span>Total sections</span></div>
  </article>
  <article>
    <span class="admin-sections-summary__icon admin-sections-summary__icon--live"><i class="bi bi-eye"></i></span>
    <div><strong><?= $enabledCount ?></strong><span>Visible on website</span></div>
  </article>
  <article>
    <span class="admin-sections-summary__icon admin-sections-summary__icon--hidden"><i class="bi bi-eye-slash"></i></span>
    <div><strong><?= $hiddenCount ?></strong><span>Currently hidden</span></div>
  </article>
</div>

<!-- Page navigation pills -->
<nav class="admin-section-pages" aria-label="Website pages">
  <?php foreach ($pages as $key => $label): ?>
  <?php $icon = $pageIconMap[$key] ?? 'bi-file-earmark-text'; ?>
  <a href="<?= url('admin/sections?page=' . urlencode($key)) ?>" class="<?= $pageKey === $key ? 'is-active' : '' ?>" <?= $pageKey === $key ? 'aria-current="page"' : '' ?>>
    <i class="bi <?= e($icon) ?>"></i>
    <span><?= e($label) ?></span>
  </a>
  <?php endforeach; ?>
</nav>

<!-- Section Filter Bar -->
<div class="admin-section-toolbar">
  <div class="admin-section-search">
    <i class="bi bi-search"></i>
    <input type="search" id="section-filter-input" class="admin-input admin-input--sm" placeholder="Filter sections by name, key, or copy...">
  </div>
  <div class="admin-section-view-filters" role="group" aria-label="Filter by visibility">
    <button type="button" class="is-active" data-section-view="all">All</button>
    <button type="button" data-section-view="live">Visible</button>
    <button type="button" data-section-view="hidden">Hidden</button>
  </div>
  <span class="admin-section-count-badge" id="section-count-badge"><?= $sectionCount ?> <?= $sectionCount === 1 ? 'section' : 'sections' ?></span>
</div>

<?php if (!empty($sections)): ?>
<div class="admin-section-list" id="section-list">
  <?php foreach ($sections as $section):
    $key = (string)$section['section_key'];
    $label = $sectionLabels[$key] ?? ucwords(str_replace(['_', '-'], ' ', $key));
    $hasCopy = trim((string)($section['title'] ?? '')) !== ''
        || trim((string)($section['subtitle'] ?? '')) !== ''
        || trim((string)($section['content'] ?? '')) !== '';
    $hasMedia = trim((string)($section['image_url'] ?? '')) !== '';
    $data = $section['data'] ?? null;
    $dataCount = 0;
    $dataDesc = '';
    if (is_array($data)) {
        $dataCount = count($data);
        if (array_is_list($data)) {
            $dataDesc = $dataCount . ' ' . ($dataCount === 1 ? 'item' : 'items');
        } else {
            $dataDesc = $dataCount . ' fields';
        }
    }
  ?>
  <article class="admin-section-row <?= empty($section['is_enabled']) ? 'is-hidden' : '' ?>" data-section-row data-visibility="<?= !empty($section['is_enabled']) ? 'live' : 'hidden' ?>" data-keywords="<?= strtolower(e($label . ' ' . $key . ' ' . ($section['subtitle'] ?? '') . ' ' . ($section['title'] ?? '') . ' ' . ($section['content'] ?? ''))) ?>">
    <div class="admin-section-row__order" title="Display order">
      <span>#<?= (int)($section['sort_order'] ?? 0) ?></span>
    </div>
    <div class="admin-section-row__identity">
      <span class="admin-section-row__icon"><i class="bi bi-layout-text-sidebar-reverse"></i></span>
      <div>
        <h3><?= e($label) ?></h3>
        <code><?= e($key) ?></code>
      </div>
    </div>
    <div class="admin-section-row__content">
      <strong><?= e($section['subtitle'] ?: ($section['title'] ?: 'Untitled section')) ?></strong>
      <p><?= e(truncate($section['content'] ?: 'No supporting description added.', 110)) ?></p>
      <div class="admin-section-row__signals">
        <span class="<?= $hasCopy ? 'is-ready' : '' ?>"><i class="bi bi-type"></i> Copy</span>
        <span class="<?= $hasMedia ? 'is-ready' : '' ?>"><i class="bi bi-image"></i> Media</span>
        <?php if ($dataCount > 0): ?>
        <span class="is-ready"><i class="bi bi-list-ul"></i> <?= e($dataDesc) ?></span>
        <?php else: ?>
        <span><i class="bi bi-list-ul"></i> No items</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="admin-section-row__status">
      <span class="admin-section-status <?= !empty($section['is_enabled']) ? 'is-live' : 'is-off' ?>">
        <i class="bi <?= !empty($section['is_enabled']) ? 'bi-check-circle-fill' : 'bi-dash-circle' ?>"></i>
        <?= !empty($section['is_enabled']) ? 'Visible' : 'Hidden' ?>
      </span>
    </div>
    <div class="admin-section-row__actions">
      <a href="<?= url('admin/sections/' . $section['id'] . '/edit') ?>" class="admin-btn admin-btn-sm admin-btn-primary">
        <i class="bi bi-pencil-square"></i> Edit
      </a>
      <form action="<?= url('admin/sections/' . $section['id'] . '/toggle') ?>" method="POST" class="admin-inline-form">
        <?= csrf() ?>
        <button class="admin-section-visibility" type="submit" title="<?= !empty($section['is_enabled']) ? 'Hide this section' : 'Show this section' ?>" aria-label="<?= !empty($section['is_enabled']) ? 'Hide ' . e($label) : 'Show ' . e($label) ?>">
          <i class="bi <?= !empty($section['is_enabled']) ? 'bi-eye-slash' : 'bi-eye' ?>"></i>
        </button>
      </form>
    </div>
  </article>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="admin-empty admin-empty--pro admin-sections-empty">
  <i class="bi bi-layout-text-window"></i>
  <h3>No sections configured</h3>
  <p>This page does not have any manageable sections yet.</p>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var filterInput = document.getElementById('section-filter-input');
  var badge = document.getElementById('section-count-badge');
  var view = 'all';
  var buttons = document.querySelectorAll('[data-section-view]');
  if (!filterInput) return;

  function applyFilters() {
    var q = filterInput.value.toLowerCase().trim();
    var rows = document.querySelectorAll('[data-section-row]');
    var shown = 0;
    rows.forEach(function(row) {
      var kw = row.getAttribute('data-keywords') || '';
      var vis = row.getAttribute('data-visibility') || 'live';
      var matchText = q === '' || kw.indexOf(q) !== -1;
      var matchView = view === 'all' || vis === view;
      var show = matchText && matchView;
      row.style.display = show ? '' : 'none';
      if (show) shown += 1;
    });
    if (badge) badge.textContent = shown + (shown === 1 ? ' section' : ' sections');
  }

  filterInput.addEventListener('input', applyFilters);
  buttons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      view = btn.getAttribute('data-section-view') || 'all';
      buttons.forEach(function(other) { other.classList.toggle('is-active', other === btn); });
      applyFilters();
    });
  });
});
</script>
