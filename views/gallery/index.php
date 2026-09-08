<?php
$pageTitle = $seo['meta_title'] ?? 'Gallery — HighQ Homes';
$bodyPage  = 'gallery';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20discuss%20a%20project';
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$heroImage = 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80';

$cms = $sections ?? [];
$hero = cmsRow($cms, 'hero');
$intro = cmsRow($cms, 'intro');
$highlightsSec = cmsRow($cms, 'highlights');
$catalogSec = cmsRow($cms, 'catalog');
$ctaSec = cmsRow($cms, 'cta');
$ctaMap = cmsMap($ctaSec);

$showHero = cmsRowEnabled($cms, 'hero', true);
$showIntro = cmsRowEnabled($cms, 'intro', true);
$showHighlights = cmsRowEnabled($cms, 'highlights', true);
$showCatalog = cmsRowEnabled($cms, 'catalog', true);
$showCta = cmsRowEnabled($cms, 'cta', true);

$heroKicker = cmsText($hero, 'title', 'Our work');
$heroTitle  = cmsText($hero, 'subtitle', 'Project gallery');
$heroLead   = cmsText($hero, 'content', 'Photos from residential, commercial, and community projects across Puntland — structure, finishes, and handover.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80');

$introEyebrow = cmsText($intro, 'title', 'Visual portfolio');
$introTitle   = cmsText($intro, 'subtitle', 'Craftsmanship in every frame');
$introLead    = cmsText($intro, 'content', 'Browse real project imagery — from structural milestones to final finishes — and see the quality HighQ Homes delivers.');

$highlights = cmsList($highlightsSec) ?: [
    ['icon' => 'bi-house-heart', 'title' => 'Residential', 'text' => 'Homes and villas finished to live-in quality.'],
    ['icon' => 'bi-building', 'title' => 'Commercial', 'text' => 'Workspaces and retail built for daily use.'],
    ['icon' => 'bi-brush', 'title' => 'Finishes', 'text' => 'Detail shots of coatings, interiors, and handover.'],
];
?>

<?php if ($showHero): ?>
<section class="hq-gallery-pro-hero">
  <div class="hq-gallery-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-gallery-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-gallery-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Gallery</span>
    </nav>
    <div class="hq-gallery-pro-hero__content" data-anim="up">
      <p class="hq-gallery-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-gallery-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-gallery-pro-hero__lead"><?= e($heroLead) ?></p>
      <div class="hq-gallery-pro-hero__actions">
        <a href="#gallery-grid" class="hq-btn hq-btn--orange hq-btn--lg">Browse photos <i class="bi bi-arrow-down"></i></a>
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">View projects</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showIntro): ?>
<section class="hq-gallery-pro-intro" aria-label="Gallery highlights">
  <div class="container-site">
    <div class="hq-gallery-pro-intro__shell" data-anim="up">
      <div class="hq-gallery-pro-intro__copy">
        <p class="hq-gallery-pro-eyebrow"><?= e($introEyebrow) ?></p>
        <h2 class="hq-gallery-pro-title"><?= e($introTitle) ?></h2>
        <p class="hq-gallery-pro-lead"><?= e($introLead) ?></p>
      </div>
      <div class="hq-gallery-pro-intro__stats">
        <div class="hq-gallery-pro-stat">
          <strong><?= number_format($stats['showing']) ?></strong>
          <span><?= $category ? 'In category' : 'Photos' ?></span>
        </div>
        <div class="hq-gallery-pro-stat">
          <strong><?= number_format($stats['total']) ?></strong>
          <span>Total images</span>
        </div>
        <div class="hq-gallery-pro-stat">
          <strong><?= number_format($stats['categories']) ?></strong>
          <span>Categories</span>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showHighlights && !empty($highlights)): ?>
<section class="hq-gallery-pro-highlights">
  <div class="container-site">
    <div class="hq-gallery-pro-highlights__grid">
      <?php foreach ($highlights as $i => $item): ?>
      <article class="hq-gallery-pro-highlight" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-gallery-pro-highlight__icon"><i class="bi <?= e($item['icon'] ?? 'bi-image') ?>"></i></span>
        <h3><?= e($item['title'] ?? '') ?></h3>
        <p><?= e($item['text'] ?? '') ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCatalog): ?>
<section class="hq-gallery-pro-catalog" id="gallery-grid">
  <div class="container-site">
    <header class="hq-gallery-pro-section-head" data-anim="up">
      <p class="hq-gallery-pro-eyebrow"><?= e(cmsText($catalogSec, 'title', 'Photo collection')) ?></p>
      <h2 class="hq-gallery-pro-title"><?= e(cmsText($catalogSec, 'subtitle', 'Explore by category')) ?></h2>
      <p class="hq-gallery-pro-lead hq-gallery-pro-section-head__lead"><?= e(cmsText($catalogSec, 'content', 'Filter images by project type — click any photo to view full size.')) ?></p>
    </header>

    <?php if (!empty($categories)): ?>
    <div class="hq-gallery-pro-toolbar" data-anim="up" data-delay="50">
      <span class="hq-gallery-pro-toolbar__label">Filter</span>
      <div class="hq-gallery-pro-filters">
        <button type="button" data-filter-btn="all" class="hq-gallery-pro-filter active">All</button>
        <?php foreach ($categories as $cat): ?>
        <button type="button" data-filter-btn="<?= e($cat) ?>" class="hq-gallery-pro-filter"><?= e(galleryCategoryLabel($cat)) ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
    <div class="hq-gallery-pro-empty" data-anim="up">
      <span class="hq-gallery-pro-empty__icon"><i class="bi bi-images"></i></span>
      <h3>No gallery images yet</h3>
      <p>Photos will appear here once published from the admin gallery.</p>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline">Contact us</a>
    </div>
    <?php else: ?>
    <p class="hq-gallery-pro-count" data-anim="up" data-delay="60">
      <?= $stats['showing'] === 1 ? '1 photo' : number_format($stats['showing']) . ' photos' ?>
    </p>
    <div class="hq-gallery-pro-grid">
      <?php foreach ($items as $i => $item): ?>
      <?php
        $imgSrc = galleryImageUrl($item);
        $alt    = $item['alt_text'] ?: ($item['title'] ?: 'Gallery');
        $cat    = $item['category'] ?? '';
      ?>
      <button
        type="button"
        class="hq-gallery-pro-item lightbox-trigger hq-gallery-trigger"
        data-lightbox="gallery"
        data-src="<?= e($imgSrc) ?>"
        data-alt="<?= e($alt) ?>"
        data-filter-item="<?= e($cat) ?>"
        data-anim="up"
        data-delay="<?= ($i % 12) * 35 ?>"
      >
        <img src="<?= e($imgSrc) ?>" alt="<?= e($alt) ?>" loading="<?= $i < 6 ? 'eager' : 'lazy' ?>">
        <span class="hq-gallery-pro-item__overlay">
          <?php if ($cat !== ''): ?>
          <span class="hq-gallery-pro-item__cat"><?= e(galleryCategoryLabel($cat)) ?></span>
          <?php endif; ?>
          <?php if (!empty($item['title'])): ?>
          <strong><?= e($item['title']) ?></strong>
          <?php endif; ?>
          <span class="hq-gallery-pro-item__zoom"><i class="bi bi-zoom-in"></i></span>
        </span>
      </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-gallery-pro-cta" aria-labelledby="gallery-cta-title">
  <div class="hq-gallery-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-gallery-pro-cta__box" data-anim="up">
    <div class="hq-gallery-pro-cta__copy">
      <p class="hq-gallery-pro-eyebrow hq-gallery-pro-eyebrow--light"><?= e($ctaSec['title'] ?? 'Start your build') ?></p>
      <h2 id="gallery-cta-title" class="hq-gallery-pro-cta__title"><?= e($ctaSec['subtitle'] ?? 'Want results like these on your site?') ?></h2>
      <p class="hq-gallery-pro-cta__lead"><?= e($ctaSec['content'] ?? 'Share your project vision — we\'ll plan scope, timeline, and premium delivery from day one.') ?></p>
    </div>
    <div class="hq-gallery-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMap['cta_primary'] ?? 'Get a quote') ?></a>
      <a href="<?= url('projects') ?>" class="hq-btn hq-btn--ghost hq-btn--lg"><?= e($ctaMap['cta_secondary'] ?? 'View projects') ?></a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-gallery-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
