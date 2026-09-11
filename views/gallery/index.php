<?php
$pageTitle = $seo['meta_title'] ?? 'Gallery — HighQ Homes';
$bodyPage  = 'gallery';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20discuss%20a%20project';
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);

$copyIf = static function (string $current, array $stale, string $fresh): string {
    $norm = strtolower(trim($current));
    if ($norm === '') {
        return $fresh;
    }
    foreach ($stale as $old) {
        if ($norm === strtolower($old)) {
            return $fresh;
        }
    }
    return $current;
};

if (empty($items)) {
    $items = galleryShowcaseItems(($category ?? '') ?: null);
    $allShowcase = galleryShowcaseItems();
    $stats['showing'] = count($items);
    $stats['total'] = count($allShowcase);
    $stats['categories'] = count(array_unique(array_map(
        static fn(array $row): string => (string)($row['category'] ?? ''),
        $allShowcase
    )));
    $stats['completed'] = count(array_filter(
        $allShowcase,
        static fn(array $row): bool => ($row['category'] ?? '') !== 'construction'
    ));
    if (empty($categories)) {
        $categories = array_values(array_unique(array_map(
            static fn(array $row): string => (string)($row['category'] ?? ''),
            $allShowcase
        )));
    }
}

$cms = $sections ?? [];
$hero = cmsRow($cms, 'hero');
$intro = cmsRow($cms, 'intro');
$catalogSec = cmsRow($cms, 'catalog');
$ctaSec = cmsRow($cms, 'cta');
$ctaMap = cmsMap($ctaSec);

$showHero = cmsRowEnabled($cms, 'hero', true);
$showIntro = cmsRowEnabled($cms, 'intro', true);
$showCatalog = cmsRowEnabled($cms, 'catalog', true);
$showCta = cmsRowEnabled($cms, 'cta', true);

$heroImageDefault = asset('images/builds/twin-residences.jpg');
$heroKicker = $copyIf(cmsText($hero, 'title', ''), ['Our work', 'Our Work', 'Gallery'], 'On site');
$heroTitle  = $copyIf(cmsText($hero, 'subtitle', ''), ['Project gallery', 'Project Gallery'], 'Photographed in Garowe.');
$heroLead   = $copyIf(cmsText($hero, 'content', ''), [
    'Photos from residential, commercial, and community projects across Puntland — structure, finishes, and handover.',
], 'Real HighQ Homes builds — villas, compounds, and an active site — not stock photography.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', $heroImageDefault);
if ($heroImage === '' || str_contains($heroImage, 'unsplash.com')) {
    $heroImage = $heroImageDefault;
}

$introEyebrow = $copyIf(cmsText($intro, 'title', ''), ['Visual portfolio', 'Visual Portfolio'], 'The work');
$introTitle   = $copyIf(cmsText($intro, 'subtitle', ''), ['Craftsmanship in every frame', 'Craftsmanship in Every Frame'], 'Homes you can walk up to.');
$introLead    = $copyIf(cmsText($intro, 'content', ''), [
    'Browse real project imagery — from structural milestones to final finishes — and see the quality HighQ Homes delivers.',
], 'Every photo on this page was taken on a HighQ Homes site in Garowe. Open one for a closer look, or go through to the project stories.');

$catalogKicker = $copyIf(cmsText($catalogSec, 'title', ''), ['Photo collection', 'Photo Collection'], 'Gallery');
$catalogTitle  = $copyIf(cmsText($catalogSec, 'subtitle', ''), ['Explore by category', 'Explore By Category'], 'Browse the photos');
$catalogLead   = $copyIf(cmsText($catalogSec, 'content', ''), [
    'Filter images by project type — click any photo to view full size.',
], 'Filter by type, then click a photo to view it full size.');

$ctaKicker = $copyIf((string)($ctaSec['title'] ?? ''), ['Start your build', 'Start Your Build'], 'Start a project');
$ctaTitle  = $copyIf((string)($ctaSec['subtitle'] ?? ''), [
    'Want results like these on your site?',
    'Want Results Like These On Your Site?',
], 'Want a home that photographs like these?');
$ctaLead   = $copyIf((string)($ctaSec['content'] ?? ''), [
    'Share your project vision — we\'ll plan scope, timeline, and premium delivery from day one.',
    "Share your project vision — we'll plan scope, timeline, and premium delivery from day one.",
], 'Share the plot. We will come back with a clear plan, an honest timeline, and a quote you can trust.');

$filterUrl = static function (?string $cat): string {
    if ($cat === null || $cat === '') {
        return url('gallery');
    }
    return url('gallery') . '?category=' . rawurlencode($cat);
};

$schemaImages = array_map(static fn(array $item): array => [
    '@type'      => 'ImageObject',
    'name'       => $item['title'] ?? 'HighQ Homes project',
    'contentUrl' => galleryImageUrl($item),
    'caption'    => $item['description'] ?? ($item['alt_text'] ?? ''),
], $items);

$schemaJson = json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ImageGallery',
    'name'            => 'HighQ Homes Project Gallery',
    'url'             => url('gallery'),
    'image'           => array_values($schemaImages),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<script type="application/ld+json"><?= $schemaJson ?></script>

<?php if ($showHero): ?>
<section class="hq-gallery-pro-hero hq-gallery-pro-hero--cinematic">
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
      <ul class="hq-gallery-pro-hero__chips">
        <li><i class="bi bi-camera-fill"></i> Photographed on site</li>
        <li><i class="bi bi-geo-alt-fill"></i> Garowe, Puntland</li>
        <li><i class="bi bi-house-heart"></i> Residential builds</li>
      </ul>
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
          <strong><?= number_format((int)($stats['total'] ?? count($items))) ?></strong>
          <span>Photos</span>
        </div>
        <div class="hq-gallery-pro-stat">
          <strong><?= number_format((int)($stats['completed'] ?? 0)) ?></strong>
          <span>Finished builds</span>
        </div>
        <div class="hq-gallery-pro-stat">
          <strong><?= number_format((int)($stats['categories'] ?? 0)) ?></strong>
          <span>Categories</span>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCatalog): ?>
<section class="hq-gallery-pro-catalog" id="gallery-grid">
  <div class="container-site">
    <header class="hq-gallery-pro-section-head" data-anim="up">
      <p class="hq-gallery-pro-eyebrow"><?= e($catalogKicker) ?></p>
      <h2 class="hq-gallery-pro-title"><?= e($catalogTitle) ?></h2>
      <p class="hq-gallery-pro-lead hq-gallery-pro-section-head__lead"><?= e($catalogLead) ?></p>
    </header>

    <?php if (!empty($categories)): ?>
    <div class="hq-gallery-pro-toolbar" data-anim="up" data-delay="50">
      <span class="hq-gallery-pro-toolbar__label">Filter</span>
      <div class="hq-gallery-pro-filters">
        <a href="<?= e($filterUrl(null)) ?>" class="hq-gallery-pro-filter<?= ($category ?? '') === '' ? ' active' : '' ?>">All</a>
        <?php foreach ($categories as $cat): ?>
        <a href="<?= e($filterUrl($cat)) ?>" class="hq-gallery-pro-filter<?= ($category ?? '') === $cat ? ' active' : '' ?>"><?= e(galleryCategoryLabel($cat)) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="hq-gallery-pro-grid">
      <?php foreach ($items as $i => $item): ?>
      <?php
        $imgSrc = galleryImageUrl($item);
        $alt    = $item['alt_text'] ?: ($item['title'] ?: 'HighQ Homes project');
        $cat    = $item['category'] ?? '';
        $featured = $i === 0 && ($category ?? '') === '';
      ?>
      <button
        type="button"
        class="hq-gallery-pro-item lightbox-trigger hq-gallery-trigger<?= $featured ? ' hq-gallery-pro-item--featured' : '' ?>"
        data-lightbox="gallery"
        data-src="<?= e($imgSrc) ?>"
        data-alt="<?= e($alt) ?>"
        data-filter-item="<?= e($cat) ?>"
        data-anim="up"
        data-delay="<?= ($i % 12) * 35 ?>"
      >
        <img src="<?= e($imgSrc) ?>" alt="<?= e($alt) ?>" loading="<?= $i < 4 ? 'eager' : 'lazy' ?>">
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
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-gallery-pro-cta" aria-labelledby="gallery-cta-title">
  <div class="hq-gallery-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-gallery-pro-cta__box" data-anim="up">
    <div class="hq-gallery-pro-cta__copy">
      <p class="hq-gallery-pro-eyebrow hq-gallery-pro-eyebrow--light"><?= e($ctaKicker) ?></p>
      <h2 id="gallery-cta-title" class="hq-gallery-pro-cta__title"><?= e($ctaTitle) ?></h2>
      <p class="hq-gallery-pro-cta__lead"><?= e($ctaLead) ?></p>
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
