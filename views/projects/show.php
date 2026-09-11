<?php
$pageTitle = e($project['title']) . ' — HighQ Homes';
$bodyPage  = 'project';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20discuss%20' . rawurlencode($project['title']);
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$pImg      = projectImageUrl($project);
$gallery   = $project['gallery_images'] ?? [];
$pCat      = projectCategoryLabel($project['category'] ?? null);
$pStatus   = projectStatusLabel($project['status'] ?? null);
$statusClass = match ($project['status'] ?? '') {
    'completed'   => 'done',
    'in_progress' => 'active',
    'planned'     => 'planned',
    default       => 'done',
};
$location  = trim((string)($project['location'] ?? ''));
$shortDesc = trim((string)($project['short_description'] ?? ''));

$scopeMap = [
  'residential'    => ['Structural & civil works', 'Façade & exterior finishing', 'Interior fit-out', 'MEP coordination', 'Quality inspections & handover'],
  'commercial'     => ['Space planning & layout', 'Structural build', 'Commercial-grade finishes', 'MEP & services integration', 'Compliance & handover'],
  'industrial'     => ['Site preparation', 'Structural steel/concrete works', 'Industrial flooring & coatings', 'Utility routing', 'Safety & QA sign-off'],
  'mosque'         => ['Architectural design execution', 'Structural build', 'Interior & acoustic finishes', 'Landscaping & access', 'Community handover'],
  'infrastructure' => ['Site assessment', 'Civil engineering works', 'Drainage & utilities', 'Roads & hardscape', 'Project documentation'],
  'other'          => ['Scope definition', 'Design coordination', 'Construction execution', 'Quality assurance', 'Client handover'],
];
$scopeItems = $scopeMap[$project['category'] ?? ''] ?? $scopeMap['other'];
?>

<section class="hq-projects-pro-hero hq-projects-pro-hero--project">
  <div class="hq-projects-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($pImg) ?>" alt="" loading="eager">
  </div>
  <div class="hq-projects-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-projects-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= url('projects') ?>">Projects</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= e($project['title']) ?></span>
    </nav>
    <div class="hq-projects-pro-hero__content" data-anim="up">
      <div class="hq-projects-pro-project__chips">
        <span class="hq-tag hq-tag--cat"><?= e($pCat) ?></span>
        <span class="hq-tag hq-tag--<?= e($statusClass) ?>"><?= e($pStatus) ?></span>
        <?php if ($location !== ''): ?>
        <span class="hq-projects-pro-project__loc"><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></span>
        <?php endif; ?>
      </div>
      <h1 class="hq-projects-pro-hero__title"><?= e($project['title']) ?></h1>
      <?php if ($shortDesc !== ''): ?>
      <p class="hq-projects-pro-hero__lead"><?= e($shortDesc) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="hq-projects-pro-detail">
  <div class="container-site">
    <div class="hq-projects-pro-detail__layout">
      <article class="hq-projects-pro-detail__main">
        <figure class="hq-projects-pro-detail__hero" data-anim="up">
          <img src="<?= e($pImg) ?>" alt="<?= e($project['title']) ?>" loading="eager">
        </figure>

        <div class="hq-projects-pro-detail__content" data-anim="up" data-delay="50">
          <h2 class="hq-projects-pro-detail__heading">Project overview</h2>
          <div class="hq-cms-content hq-projects-pro-detail__prose">
            <?= $project['description'] ?: '<p>' . e($shortDesc) . '</p>' ?>
          </div>
        </div>

        <section class="hq-projects-pro-detail__scope" data-anim="up" data-delay="70">
          <h2 class="hq-projects-pro-detail__heading">Scope of work</h2>
          <ul class="hq-projects-pro-scope-list">
            <?php foreach ($scopeItems as $item): ?>
            <li><i class="bi bi-check-circle-fill"></i><span><?= e($item) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </section>

        <?php if (!empty($gallery)): ?>
        <section class="hq-projects-pro-detail__gallery" data-anim="up" data-delay="90">
          <header class="hq-projects-pro-detail__gallery-head">
            <p class="hq-projects-pro-eyebrow">Gallery</p>
            <h2 class="hq-projects-pro-detail__heading">Project photos</h2>
          </header>
          <div class="hq-projects-pro-detail__gallery-grid">
            <?php foreach ($gallery as $img): ?>
            <?php $gSrc = mediaPathUrl((string)$img); ?>
            <button type="button" class="hq-projects-pro-gallery-item lightbox-trigger" data-lightbox="gallery" data-src="<?= e($gSrc) ?>" data-alt="<?= e($project['title']) ?>">
              <img src="<?= e($gSrc) ?>" alt="<?= e($project['title']) ?>" loading="lazy">
              <span class="hq-projects-pro-gallery-item__zoom"><i class="bi bi-zoom-in"></i></span>
            </button>
            <?php endforeach; ?>
          </div>
        </section>
        <?php endif; ?>
      </article>

      <aside class="hq-projects-pro-detail__aside">
        <div class="hq-projects-pro-meta-card" data-anim="right">
          <p class="hq-projects-pro-eyebrow">Project overview</p>
          <h2 class="hq-projects-pro-meta-card__title">At a glance</h2>
          <dl class="hq-projects-pro-meta">
            <div><dt>Category</dt><dd><?= e($pCat) ?></dd></div>
            <div><dt>Status</dt><dd><span class="hq-projects-pro-status hq-projects-pro-status--<?= e($statusClass) ?>"><?= e($pStatus) ?></span></dd></div>
            <?php if ($location !== ''): ?>
            <div><dt>Location</dt><dd><?= e($location) ?></dd></div>
            <?php endif; ?>
            <?php if (!empty($project['client_name'])): ?>
            <div><dt>Client</dt><dd><?= e($project['client_name']) ?></dd></div>
            <?php endif; ?>
            <?php if (!empty($project['project_year'])): ?>
            <div><dt>Year</dt><dd><?= e((string)$project['project_year']) ?></dd></div>
            <?php endif; ?>
            <?php if (!empty($project['project_area'])): ?>
            <div><dt>Area</dt><dd><?= e($project['project_area']) ?></dd></div>
            <?php endif; ?>
          </dl>
          <div class="hq-projects-pro-meta-card__actions">
            <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--block"><i class="bi bi-whatsapp"></i> Discuss this project</a>
            <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline hq-btn--block"><i class="bi bi-send-fill"></i> Send inquiry</a>
            <a href="<?= url('projects') ?>" class="hq-projects-pro-meta-card__back"><i class="bi bi-arrow-left"></i> Back to portfolio</a>
          </div>
        </div>

        <div class="hq-projects-pro-aside-note">
          <h4><i class="bi bi-info-circle"></i> Similar project?</h4>
          <p>Share your site details and scope — we'll provide a structured consultation and transparent proposal.</p>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php if (!empty($related)): ?>
<section class="hq-projects-pro-related">
  <div class="container-site">
    <header class="hq-projects-pro-section-head" data-anim="up">
      <p class="hq-projects-pro-eyebrow">Related projects</p>
      <h2 class="hq-projects-pro-title">More in this category</h2>
    </header>
    <div class="hq-projects-pro-related__grid">
      <?php foreach ($related as $i => $rel): ?>
      <?php $rImg = projectImageUrl($rel); ?>
      <a href="<?= url('projects/' . $rel['slug']) ?>" class="hq-projects-pro-related__card" data-anim="up" data-delay="<?= $i * 55 ?>">
        <img src="<?= e($rImg) ?>" alt="<?= e($rel['title']) ?>" loading="lazy">
        <div>
          <span><?= e(projectCategoryLabel($rel['category'] ?? null)) ?></span>
          <strong><?= e($rel['title']) ?></strong>
        </div>
        <i class="bi bi-arrow-up-right"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="hq-projects-pro-cta" aria-labelledby="project-cta-title">
  <div class="hq-projects-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-projects-pro-cta__box" data-anim="up">
    <div class="hq-projects-pro-cta__copy">
      <p class="hq-projects-pro-eyebrow hq-projects-pro-eyebrow--light">Your project next</p>
      <h2 id="project-cta-title" class="hq-projects-pro-cta__title">Ready to build something like this?</h2>
      <p class="hq-projects-pro-cta__lead">Talk to our team for planning, pricing guidance, and premium delivery from start to handover.</p>
    </div>
    <div class="hq-projects-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      <a href="<?= url('projects') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">Browse portfolio</a>
    </div>
  </div>
</section>
