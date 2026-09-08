<?php
$pageTitle = 'Page Sections';
$pageLabel = $pages[$pageKey] ?? ucfirst($pageKey);
$sectionCount = count($sections);
$enabledCount = count(array_filter($sections, static fn(array $section): bool => (bool)($section['is_enabled'] ?? false)));
$hiddenCount = $sectionCount - $enabledCount;
$previewPath = in_array($pageKey, ['header', 'footer'], true) ? '' : $pageKey;
$sectionLabels = [
    'hero' => 'Hero',
    'about' => 'About',
    'services' => 'Services',
    'projects' => 'Projects',
    'why_choose_us' => 'Why Choose Us',
    'process' => 'Process',
    'testimonials' => 'Testimonials',
    'team' => 'Team',
    'gallery' => 'Gallery',
    'stats' => 'Statistics',
    'cta' => 'Call to Action',
    'contact' => 'Contact',
    'partners' => 'Partners',
];
?>

<section class="admin-sections-hero">
  <div>
    <span class="admin-sections-hero__eyebrow"><i class="bi bi-layout-text-window-reverse"></i> Website content</span>
    <h2>Manage <?= e($pageLabel) ?></h2>
    <p>Edit the headings, supporting copy, media and visibility of each website section from one workspace.</p>
  </div>
  <a href="<?= url($previewPath) ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
    <i class="bi bi-box-arrow-up-right"></i> Preview page
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

<nav class="admin-section-pages" aria-label="Website pages">
  <?php foreach ($pages as $key => $label): ?>
  <a href="<?= url('admin/sections?page=' . urlencode($key)) ?>" class="<?= $pageKey === $key ? 'is-active' : '' ?>" <?= $pageKey === $key ? 'aria-current="page"' : '' ?>>
    <i class="bi <?= $key === 'header' ? 'bi-window-dock' : ($key === 'footer' ? 'bi-window-stack' : 'bi-file-earmark-text') ?>"></i>
    <span><?= e($label) ?></span>
  </a>
  <?php endforeach; ?>
</nav>

<?php if (!empty($sections)): ?>
<div class="admin-section-list">
  <?php foreach ($sections as $section):
    $key = (string)$section['section_key'];
    $label = $sectionLabels[$key] ?? ucwords(str_replace(['_', '-'], ' ', $key));
    $hasCopy = trim((string)($section['title'] ?? '')) !== ''
        || trim((string)($section['subtitle'] ?? '')) !== ''
        || trim((string)($section['content'] ?? '')) !== '';
    $hasMedia = trim((string)($section['image_url'] ?? '')) !== '';
    $hasData = !empty($section['data']);
  ?>
  <article class="admin-section-row <?= empty($section['is_enabled']) ? 'is-hidden' : '' ?>">
    <div class="admin-section-row__order" title="Display order"><?= (int)($section['sort_order'] ?? 0) ?></div>
    <div class="admin-section-row__identity">
      <span class="admin-section-row__icon"><i class="bi bi-layout-text-sidebar-reverse"></i></span>
      <div>
        <h3><?= e($label) ?></h3>
        <code><?= e($key) ?></code>
      </div>
    </div>
    <div class="admin-section-row__content">
      <strong><?= e($section['subtitle'] ?: ($section['title'] ?: 'Untitled section')) ?></strong>
      <p><?= e(truncate($section['content'] ?: 'No supporting description has been added.', 105)) ?></p>
      <div class="admin-section-row__signals">
        <span class="<?= $hasCopy ? 'is-ready' : '' ?>"><i class="bi bi-type"></i> Copy</span>
        <span class="<?= $hasMedia ? 'is-ready' : '' ?>"><i class="bi bi-image"></i> Media</span>
        <span class="<?= $hasData ? 'is-ready' : '' ?>"><i class="bi bi-list-ul"></i> Structured data</span>
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
        <i class="bi bi-pencil-square"></i> Edit content
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
