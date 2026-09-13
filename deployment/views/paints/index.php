<?php
$bodyPage  = 'paints';
$pageTitle = $seo['meta_title'] ?? 'Paints & Building Products — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20need%20advice%20on%20paints%20and%20coatings';
$hasFilter = $category !== '' || $brand !== '';

$filterUrl = static function (?string $cat, ?string $br) use ($category, $brand): string {
    $params = array_filter([
        'category' => ($cat ?? $category) !== '' ? ($cat ?? $category) : null,
        'brand'    => ($br ?? $brand) !== '' ? ($br ?? $brand) : null,
    ]);
    $qs = http_build_query($params);
    return url('paints') . ($qs !== '' ? '?' . $qs : '');
};

$paintImage = static function (array $paint): string {
    if (!empty($paint['featured_image'])) {
        return str_starts_with($paint['featured_image'], 'http')
            ? $paint['featured_image']
            : uploadUrl($paint['featured_image']);
    }
    return 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=800&q=80';
};

$cms = $sections ?? [];
$hero = cmsRow($cms, 'hero');
$intro = cmsRow($cms, 'intro');
$benefitsSec = cmsRow($cms, 'benefits');
$catalogSec = cmsRow($cms, 'catalog');
$guideSec = cmsRow($cms, 'guide');
$ctaSec = cmsRow($cms, 'cta');
$ctaMap = cmsMap($ctaSec);
$guideMap = cmsMap($guideSec);

$showHero = cmsRowEnabled($cms, 'hero', true);
$showIntro = cmsRowEnabled($cms, 'intro', true);
$showBenefits = cmsRowEnabled($cms, 'benefits', true);
$showCatalog = cmsRowEnabled($cms, 'catalog', true);
$showGuide = cmsRowEnabled($cms, 'guide', true);
$showCta = cmsRowEnabled($cms, 'cta', true);

$heroKicker = cmsText($hero, 'title', 'Products');
$heroTitle  = cmsText($hero, 'subtitle', 'Premium paints & coatings');
$heroLead   = cmsText($hero, 'content', 'Professional-grade finishes for exterior walls, interiors, and textured surfaces — supplied with expert guidance for lasting results.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1920&q=80');

$introEyebrow = cmsText($intro, 'title', 'Finishing Excellence');
$introTitle   = cmsText($intro, 'subtitle', 'Colors & Coatings That Endure');
$introLead    = cmsText($intro, 'content', 'HighQ Homes supplies coatings chosen for weather resistance, coverage, and finish quality — backed by application advice from our construction team.');

$benefits = cmsList($benefitsSec) ?: [
    ['icon' => 'bi-shield-check', 'title' => 'Weather resistant', 'text' => 'Formulations selected for heat, dust, and seasonal rain.'],
    ['icon' => 'bi-droplet-half', 'title' => 'Reliable coverage', 'text' => 'Predictable yield so you can order the right quantity.'],
    ['icon' => 'bi-brush', 'title' => 'Premium finish', 'text' => 'Color hold and surface quality that last after handover.'],
    ['icon' => 'bi-headset', 'title' => 'Expert advice', 'text' => 'Application guidance from the same team that builds the site.'],
];
$guideList = (isset($guideMap['checklist']) && is_array($guideMap['checklist'])) ? $guideMap['checklist'] : [
    'Exterior walls — weather-resistant, UV-stable, breathable coatings',
    'Textured finishes — hide imperfections with durable cementitious systems',
    'Interior spaces — washable, low-odour finishes for living and commercial areas',
    'Surface preparation — priming and substrate guidance before application',
];
$guideCards = (isset($guideMap['cards']) && is_array($guideMap['cards'])) ? $guideMap['cards'] : [
    ['icon' => 'bi-droplet-half', 'title' => 'Coverage & yield', 'text' => 'Calculate m² per unit with our team before ordering.'],
    ['icon' => 'bi-sun', 'title' => 'Climate suitability', 'text' => 'Products selected for heat, dust, and seasonal rain.'],
    ['icon' => 'bi-tools', 'title' => 'Application method', 'text' => 'Trowel, roller, or spray — we advise the best approach.'],
];
?>

<?php if ($showHero): ?>
<section class="hq-paints-pro-hero">
  <div class="hq-paints-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-paints-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-paints-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Paints &amp; Products</span>
    </nav>
    <div class="hq-paints-pro-hero__content" data-anim="up">
      <p class="hq-paints-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-paints-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-paints-pro-hero__lead"><?= e($heroLead) ?></p>
      <div class="hq-paints-pro-hero__actions">
        <a href="#catalog" class="hq-btn hq-btn--orange hq-btn--lg">Browse catalog <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Product enquiry</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showIntro): ?>
<section class="hq-paints-pro-intro">
  <div class="container-site">
    <div class="hq-paints-pro-intro__shell" data-anim="up">
      <div class="hq-paints-pro-intro__copy">
        <p class="hq-paints-pro-eyebrow"><?= e($introEyebrow) ?></p>
        <h2 class="hq-paints-pro-title"><?= e($introTitle) ?></h2>
        <p class="hq-paints-pro-lead"><?= e($introLead) ?></p>
      </div>
      <div class="hq-paints-pro-intro__stats">
        <div class="hq-paints-pro-stat">
          <strong><?= number_format($stats['total']) ?></strong>
          <span>Products</span>
        </div>
        <div class="hq-paints-pro-stat">
          <strong><?= number_format($stats['categories']) ?></strong>
          <span>Categories</span>
        </div>
        <div class="hq-paints-pro-stat">
          <strong><?= number_format($stats['brands']) ?></strong>
          <span>Brands</span>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showBenefits && !empty($benefits)): ?>
<section class="hq-paints-pro-benefits">
  <div class="container-site">
    <div class="hq-paints-pro-benefits__grid">
      <?php foreach ($benefits as $i => $item): ?>
      <article class="hq-paints-pro-benefit" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-paints-pro-benefit__icon"><i class="bi <?= e($item['icon'] ?? 'bi-brush') ?>"></i></span>
        <h3><?= e($item['title'] ?? '') ?></h3>
        <p><?= e($item['text'] ?? '') ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCatalog): ?>
<section class="hq-paints-pro-catalog" id="catalog">
  <div class="container-site">
    <header class="hq-paints-pro-section-head" data-anim="up">
      <p class="hq-paints-pro-eyebrow"><?= e(cmsText($catalogSec, 'title', 'Product catalog')) ?></p>
      <h2 class="hq-paints-pro-title"><?= e(cmsText($catalogSec, 'subtitle', 'Browse our range')) ?></h2>
      <p class="hq-paints-pro-lead hq-paints-pro-section-head__lead"><?= e(cmsText($catalogSec, 'content', 'Filter by category or brand to find the right coating for your project.')) ?></p>
    </header>

    <div class="hq-paints-pro-toolbar" data-anim="up" data-delay="50">
      <?php if (!empty($categories)): ?>
      <div class="hq-paints-pro-toolbar__group">
        <span class="hq-paints-pro-toolbar__label">Category</span>
        <div class="hq-paints-pro-filters">
          <a href="<?= e(url('paints') . ($brand !== '' ? '?brand=' . urlencode($brand) : '')) ?>" class="hq-paints-pro-filter<?= $category === '' ? ' is-active' : '' ?>">All</a>
          <?php foreach ($categories as $cat): ?>
          <a href="<?= e($filterUrl($cat, null)) ?>" class="hq-paints-pro-filter<?= $category === $cat ? ' is-active' : '' ?>"><?= e($cat) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($brands)): ?>
      <div class="hq-paints-pro-toolbar__group">
        <span class="hq-paints-pro-toolbar__label">Brand</span>
        <div class="hq-paints-pro-filters">
          <a href="<?= e(url('paints') . ($category !== '' ? '?category=' . urlencode($category) : '')) ?>" class="hq-paints-pro-filter<?= $brand === '' ? ' is-active' : '' ?>">All</a>
          <?php foreach ($brands as $b): ?>
          <a href="<?= e($filterUrl(null, $b)) ?>" class="hq-paints-pro-filter<?= $brand === $b ? ' is-active' : '' ?>"><?= e($b) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($hasFilter): ?>
      <a href="<?= url('paints') ?>" class="hq-paints-pro-clear"><i class="bi bi-x-lg"></i> Clear filters</a>
      <?php endif; ?>
    </div>

    <p class="hq-paints-pro-count" data-anim="up" data-delay="60">
      <?= $stats['showing'] === 1 ? '1 product' : number_format($stats['showing']) . ' products' ?>
      <?= $hasFilter ? ' matching your filters' : '' ?>
    </p>

    <?php if (empty($paints)): ?>
    <div class="hq-paints-pro-empty" data-anim="up">
      <span class="hq-paints-pro-empty__icon"><i class="bi bi-palette"></i></span>
      <h3>No products found</h3>
      <p>Try adjusting your filters or contact us for custom product sourcing.</p>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline">Contact us</a>
    </div>
    <?php else: ?>
    <div class="hq-paints-pro-grid">
      <?php foreach ($paints as $i => $paint): ?>
      <?php $img = $paintImage($paint); $features = array_slice($paint['features'] ?? [], 0, 3); ?>
      <article class="hq-paints-pro-card" data-anim="up" data-delay="<?= ($i % 6) * 45 ?>">
        <a href="<?= url('paints/' . $paint['slug']) ?>" class="hq-paints-pro-card__media">
          <img src="<?= e($img) ?>" alt="<?= e($paint['name']) ?>" loading="lazy">
          <?php if (!empty($paint['category'])): ?>
          <span class="hq-paints-pro-card__cat"><?= e($paint['category']) ?></span>
          <?php endif; ?>
        </a>
        <div class="hq-paints-pro-card__body">
          <?php if (!empty($paint['brand'])): ?>
          <span class="hq-paints-pro-card__brand"><?= e($paint['brand']) ?></span>
          <?php endif; ?>
          <h3><a href="<?= url('paints/' . $paint['slug']) ?>"><?= e($paint['name']) ?></a></h3>
          <p><?= e(truncate($paint['short_description'] ?? '', 100)) ?></p>
          <?php if (!empty($features)): ?>
          <ul class="hq-paints-pro-card__tags">
            <?php foreach ($features as $f): ?>
            <li><?= e($f) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <div class="hq-paints-pro-card__foot">
            <?php if (!empty($paint['price'])): ?>
            <span class="hq-paints-pro-card__price"><?= e($paint['price']) ?><?= !empty($paint['unit']) ? ' <small>/ ' . e($paint['unit']) . '</small>' : '' ?></span>
            <?php endif; ?>
            <a href="<?= url('paints/' . $paint['slug']) ?>" class="hq-paints-pro-card__link">View details <i class="bi bi-arrow-up-right"></i></a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showGuide): ?>
<section class="hq-paints-pro-guide">
  <div class="container-site">
    <div class="hq-paints-pro-guide__shell">
      <div class="hq-paints-pro-guide__copy" data-anim="left">
        <p class="hq-paints-pro-eyebrow"><?= e(cmsText($guideSec, 'title', 'Professional guidance')) ?></p>
        <h2 class="hq-paints-pro-title"><?= e(cmsText($guideSec, 'subtitle', 'Choosing the right coating')) ?></h2>
        <p class="hq-paints-pro-lead"><?= e(cmsText($guideSec, 'content', 'The right product depends on surface type, exposure, and finish expectations. Our team helps you select coatings that perform in local conditions.')) ?></p>
        <ul class="hq-paints-pro-guide__list">
          <?php foreach ($guideList as $line): ?>
          <li><i class="bi bi-check2-circle"></i> <?= e(is_array($line) ? ($line['title'] ?? $line['text'] ?? '') : $line) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="hq-paints-pro-guide__cards" data-anim="right">
        <?php foreach ($guideCards as $card): ?>
        <article class="hq-paints-pro-guide__card">
          <i class="bi <?= e($card['icon'] ?? 'bi-check-circle') ?>"></i>
          <strong><?= e($card['title'] ?? '') ?></strong>
          <span><?= e($card['text'] ?? $card['body'] ?? '') ?></span>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-paints-pro-cta">
  <div class="hq-paints-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-paints-pro-cta__box" data-anim="up">
    <div class="hq-paints-pro-cta__copy">
      <p class="hq-paints-pro-eyebrow hq-paints-pro-eyebrow--light"><?= e($ctaSec['title'] ?? 'Need a quote?') ?></p>
      <h2 class="hq-paints-pro-cta__title"><?= e($ctaSec['subtitle'] ?? 'Get product advice & pricing') ?></h2>
      <p class="hq-paints-pro-cta__lead"><?= e($ctaSec['content'] ?? 'Tell us your project scope — we\'ll recommend the right coatings and supply options.') ?></p>
    </div>
    <div class="hq-paints-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMap['cta_primary'] ?? 'WhatsApp enquiry') ?></a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg"><?= e($ctaMap['cta_secondary'] ?? 'Contact form') ?></a>
    </div>
  </div>
</section>
<?php endif; ?>
