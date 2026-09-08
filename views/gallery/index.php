<?php
$pageTitle = $seo['meta_title'] ?? 'Gallery — HighQ Homes';
$bodyPage  = 'gallery';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20discuss%20a%20project';
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$heroImage = 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80';

$highlights = [
    ['icon' => 'bi-buildings', 'title' => 'Residential & commercial', 'text' => 'Exterior and interior shots from homes, offices, and mixed-use builds.'],
    ['icon' => 'bi-brush', 'title' => 'Finishing details', 'text' => 'Kitchens, bathrooms, joinery, and premium fit-out craftsmanship.'],
    ['icon' => 'bi-hammer', 'title' => 'On-site progress', 'text' => 'Structure, cladding, and quality checks throughout delivery.'],
    ['icon' => 'bi-tree', 'title' => 'Landscape & exterior', 'text' => 'Facades, compounds, hardscape, and outdoor spaces.'],
];
?>

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
      <p class="hq-gallery-pro-hero__kicker">Our work</p>
      <h1 class="hq-gallery-pro-hero__title">Project gallery</h1>
      <p class="hq-gallery-pro-hero__lead">Photos from residential, commercial, and community projects across Puntland — structure, finishes, and handover.</p>
      <div class="hq-gallery-pro-hero__actions">
        <a href="#gallery-grid" class="hq-btn hq-btn--orange hq-btn--lg">Browse photos <i class="bi bi-arrow-down"></i></a>
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">View projects</a>
      </div>
    </div>
  </div>
</section>

<section class="hq-gallery-pro-intro" aria-label="Gallery highlights">
  <div class="container-site">
    <div class="hq-gallery-pro-intro__shell" data-anim="up">
      <div class="hq-gallery-pro-intro__copy">
        <p class="hq-gallery-pro-eyebrow">Visual portfolio</p>
        <h2 class="hq-gallery-pro-title">Craftsmanship in every frame</h2>
        <p class="hq-gallery-pro-lead">Browse real project imagery — from structural milestones to final finishes — and see the quality HighQ Homes delivers.</p>
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

<section class="hq-gallery-pro-highlights">
  <div class="container-site">
    <div class="hq-gallery-pro-highlights__grid">
      <?php foreach ($highlights as $i => $item): ?>
      <article class="hq-gallery-pro-highlight" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-gallery-pro-highlight__icon"><i class="bi <?= e($item['icon']) ?>"></i></span>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hq-gallery-pro-catalog" id="gallery-grid">
  <div class="container-site">
    <header class="hq-gallery-pro-section-head" data-anim="up">
      <p class="hq-gallery-pro-eyebrow">Photo collection</p>
      <h2 class="hq-gallery-pro-title">Explore by category</h2>
      <p class="hq-gallery-pro-lead hq-gallery-pro-section-head__lead">Filter images by project type — click any photo to view full size.</p>
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

<section class="hq-gallery-pro-cta" aria-labelledby="gallery-cta-title">
  <div class="hq-gallery-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-gallery-pro-cta__box" data-anim="up">
    <div class="hq-gallery-pro-cta__copy">
      <p class="hq-gallery-pro-eyebrow hq-gallery-pro-eyebrow--light">Start your build</p>
      <h2 id="gallery-cta-title" class="hq-gallery-pro-cta__title">Want results like these on your site?</h2>
      <p class="hq-gallery-pro-cta__lead">Share your project vision — we'll plan scope, timeline, and premium delivery from day one.</p>
    </div>
    <div class="hq-gallery-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      <a href="<?= url('projects') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">View projects</a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-gallery-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
