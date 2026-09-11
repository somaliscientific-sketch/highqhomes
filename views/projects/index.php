<?php
$pageTitle = $seo['meta_title'] ?? 'Our Projects — HighQ Homes';
$bodyPage  = 'projects';

$hasFilters = (bool)($category || $status);
$statuses   = $statuses ?? [];
$projects   = $projects ?? [];
$stats      = $stats ?? ['showing' => 0, 'total' => 0, 'completed' => 0, 'in_progress' => 0, 'categories' => 0, 'years' => ''];

if ($projects === []) {
    $projects = projectShowcaseItems(($category ?? '') ?: null, ($status ?? '') ?: null);
    $allShowcase = projectShowcaseItems();
    $years = array_values(array_filter(array_map(static fn(array $row): int => (int)($row['project_year'] ?? 0), $allShowcase)));
    $stats['showing'] = count($projects);
    $stats['total'] = count($allShowcase);
    $stats['completed'] = count(array_filter($allShowcase, static fn(array $row): bool => ($row['status'] ?? '') === 'completed'));
    $stats['in_progress'] = count(array_filter($allShowcase, static fn(array $row): bool => ($row['status'] ?? '') === 'in_progress'));
    $stats['categories'] = count(array_unique(array_map(static fn(array $row): string => (string)($row['category'] ?? ''), $allShowcase)));
    $stats['years'] = $years === [] ? '' : (min($years) === max($years) ? (string) min($years) : min($years) . '–' . max($years));
    $statuses = array_values(array_unique(array_map(static fn(array $row): string => (string)($row['status'] ?? ''), $allShowcase)));
    $categories = array_values(array_unique(array_map(static fn(array $row): string => (string)($row['category'] ?? ''), $allShowcase)));
}

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

$filterUrl = static function (?string $cat, ?string $st) use ($category, $status): string {
    $params = array_filter([
        'category' => ($cat ?? $category) !== '' ? ($cat ?? $category) : null,
        'status'   => ($st ?? $status) !== '' ? ($st ?? $status) : null,
    ]);
    $qs = http_build_query($params);
    return url('projects') . ($qs !== '' ? '?' . $qs : '');
};

$cardSize = static function (int $i, int $total): string {
    if ($total === 1) {
        return 'solo';
    }
    if ($i === 0) {
        return 'featured';
    }
    if ($i === 1) {
        return 'compact';
    }
    return 'standard';
};

$pillars = [
    ['icon' => 'bi-clipboard-data', 'title' => 'Structured delivery', 'text' => 'A milestone plan, site updates, and quality checks so you always know what happens next.'],
    ['icon' => 'bi-gem', 'title' => 'Premium finishes', 'text' => 'Materials chosen for Puntland conditions — durable, clean, and finished to a standard you can inspect.'],
    ['icon' => 'bi-shield-check', 'title' => 'Transparent scope', 'text' => 'Written scope, clear pricing, and one team accountable from foundation to handover.'],
    ['icon' => 'bi-geo-alt', 'title' => 'Local expertise', 'text' => 'Built in Garowe and across Puntland — we know the sites, the climate, and the finish that lasts.'],
];

$approachItems = [
    ['icon' => 'bi-search', 'title' => 'Discovery', 'text' => 'We walk the site, confirm your goals, and test what the plot can support before design begins.'],
    ['icon' => 'bi-rulers', 'title' => 'Design & planning', 'text' => 'Drawings, approvals, and a milestone schedule with checkpoints you can follow.'],
    ['icon' => 'bi-hammer', 'title' => 'Build execution', 'text' => 'Disciplined site work with inspections before each stage is signed off.'],
    ['icon' => 'bi-key', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care when you move in.'],
];

$cms = $sections ?? [];
$hero = cmsRow($cms, 'hero');
$intro = cmsRow($cms, 'intro');
$pillarsSec = cmsRow($cms, 'pillars');
$catalogSec = cmsRow($cms, 'catalog');
$approachSec = cmsRow($cms, 'approach');
$ctaSec = cmsRow($cms, 'cta');
$ctaMap = cmsMap($ctaSec);

$showHero = cmsRowEnabled($cms, 'hero', true);
$showIntro = cmsRowEnabled($cms, 'intro', true);
$showPillars = cmsRowEnabled($cms, 'pillars', true);
$showCatalog = cmsRowEnabled($cms, 'catalog', true);
$showApproach = cmsRowEnabled($cms, 'approach', true);
$showCta = cmsRowEnabled($cms, 'cta', true);

$heroImageDefault = asset('images/builds/grey-villa-evening.jpg');
$heroKicker = $copyIf(cmsText($hero, 'title', ''), ['Portfolio', 'Our Projects', 'Projects'], 'Selected work');
$heroTitle  = $copyIf(cmsText($hero, 'subtitle', ''), [
    'Projects delivered with confidence',
    'Projects Delivered With Confidence',
], 'Built in Garowe. Finished to last.');
$heroLead   = $copyIf(cmsText($hero, 'content', ''), [
    'Explore residential, commercial, and community builds — each delivered with structured planning, quality control, and premium finishes.',
    'Residential, commercial, and community builds across Puntland.',
], 'Real HighQ Homes projects — family villas, compounds, and active sites — photographed on location in Puntland.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', $heroImageDefault);
if ($heroImage === '' || str_contains($heroImage, 'unsplash.com')) {
    $heroImage = $heroImageDefault;
}

$introEyebrow = $copyIf(cmsText($intro, 'title', ''), ['Our work', 'Our Work'], 'Our work');
$introTitle   = $copyIf(cmsText($intro, 'subtitle', ''), [
    'Built to last. Designed to impress.',
    'Built to Last. Designed to Impress.',
], 'Homes people live in — not catalogue renders.');
$introLead    = $copyIf(cmsText($intro, 'content', ''), [
    'Every project reflects our commitment to transparent delivery, disciplined craftsmanship, and spaces people trust for generations.',
], 'Each project on this page is a HighQ Homes build. Filter by status, then open a card for location, year, and how we delivered it.');

$cmsPillars = cmsList($pillarsSec);
if ($cmsPillars !== []) {
    $cmsTitles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsPillars);
    $seedTitles = ['Structured delivery', 'Premium finishes', 'Transparent scope', 'Local expertise'];
    $pillars = array_intersect($seedTitles, $cmsTitles) === $seedTitles ? $pillars : $cmsPillars;
}

$cmsApproach = cmsList($approachSec);
if ($cmsApproach !== []) {
    $cmsTitles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsApproach);
    $seedTitles = ['Discovery', 'Design & planning', 'Build execution', 'Handover'];
    $approachItems = array_intersect($seedTitles, $cmsTitles) === $seedTitles ? $approachItems : $cmsApproach;
}

$catalogKicker = $copyIf(cmsText($catalogSec, 'title', ''), ['Project portfolio'], 'Project portfolio');
$catalogTitle  = $copyIf(cmsText($catalogSec, 'subtitle', ''), ['Explore our builds'], 'Explore the builds');
$catalogLead   = $copyIf(cmsText($catalogSec, 'content', ''), [
    'Filter by category or status to find projects similar to yours.',
], 'Completed homes and active sites. Open any card for the full story.');

$approachKicker = $copyIf((string)($approachSec['title'] ?? ''), ['Our approach', 'Approach', 'Our Approach'], 'Our approach');
$approachTitle  = $copyIf((string)($approachSec['subtitle'] ?? ''), [
    'How every project is delivered',
    'From discovery to handover',
    'How We Build Your Vision',
], 'How every project is delivered');
$approachLead   = $copyIf((string)($approachSec['content'] ?? ''), [
    'From first site visit to final handover — a proven path that keeps builds on schedule and quality on point.',
    'From concept design through final key handover, our phased delivery ensures zero surprises.',
], 'From the first site visit to the keys — a clear path that keeps the build on schedule and the finish on standard.');

$ctaKicker = $copyIf((string)($ctaSec['title'] ?? ''), ['Start your build', 'Start a project', 'Start Your Project'], 'Start your build');
$ctaTitle  = $copyIf((string)($ctaSec['subtitle'] ?? ''), [
    'Ready to add your project to our portfolio?',
    'Share your vision',
    'Ready to Build Your Next Space?',
], 'Ready to plan a home like these?');
$ctaLead   = $copyIf((string)($ctaSec['content'] ?? ''), [
    'Share your vision with our team for a clear plan, honest timeline, and premium delivery.',
    'Share your drawings or ideas with our engineering team for an accurate estimate and consultation.',
], 'Tell us about your plot. We will come back with a clear plan, an honest timeline, and a quote you can trust.');

$statusLabels = [
    'completed'   => 'Completed',
    'in_progress' => 'In progress',
    'planned'     => 'Planned',
];

$schemaProjects = array_map(static fn(array $p): array => [
    '@type'       => 'CreativeWork',
    'name'        => $p['title'],
    'description' => $p['short_description'] ?? '',
    'url'         => url('projects/' . $p['slug']),
    'locationCreated' => $p['location'] ?? null,
], $projects);

$schemaJson = json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'HighQ Homes Project Portfolio',
    'itemListElement' => array_values(array_map(static fn(array $item, int $i): array => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => $item,
    ], $schemaProjects, array_keys($schemaProjects))),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<script type="application/ld+json"><?= $schemaJson ?></script>

<?php if ($showHero): ?>
<section class="hq-projects-pro-hero hq-projects-pro-hero--portfolio">
  <div class="hq-projects-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-projects-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-projects-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Projects</span>
    </nav>
    <div class="hq-projects-pro-hero__content" data-anim="up">
      <p class="hq-projects-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-projects-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-projects-pro-hero__lead"><?= e($heroLead) ?></p>
      <div class="hq-projects-pro-hero__actions">
        <a href="#portfolio" class="hq-btn hq-btn--orange hq-btn--lg">Browse portfolio <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Discuss a project</a>
      </div>
      <ul class="hq-projects-pro-hero__chips">
        <li><i class="bi bi-house-heart"></i> Residential portfolio</li>
        <li><i class="bi bi-geo-alt-fill"></i> Garowe, Puntland</li>
        <li><i class="bi bi-camera-fill"></i> Photographed on site</li>
      </ul>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showIntro): ?>
<section class="hq-projects-pro-intro" aria-label="Portfolio highlights">
  <div class="container-site">
    <div class="hq-projects-pro-intro__shell" data-anim="up">
      <div class="hq-projects-pro-intro__copy">
        <p class="hq-projects-pro-eyebrow"><?= e($introEyebrow) ?></p>
        <h2 class="hq-projects-pro-title"><?= e($introTitle) ?></h2>
        <p class="hq-projects-pro-lead"><?= e($introLead) ?></p>
      </div>
      <div class="hq-projects-pro-intro__stats">
        <div class="hq-projects-pro-stat">
          <strong><?= number_format($hasFilters ? $stats['showing'] : $stats['total']) ?></strong>
          <span><?= $hasFilters ? 'Matching' : 'Total' ?> projects</span>
        </div>
        <div class="hq-projects-pro-stat">
          <strong><?= number_format($stats['completed']) ?></strong>
          <span>Completed</span>
        </div>
        <div class="hq-projects-pro-stat">
          <strong><?= number_format($stats['in_progress']) ?></strong>
          <span>In progress</span>
        </div>
        <div class="hq-projects-pro-stat">
          <?php if (!empty($stats['years'])): ?>
          <strong><?= e((string)$stats['years']) ?></strong>
          <span>Delivery years</span>
          <?php else: ?>
          <strong>Garowe</strong>
          <span>Based in Puntland</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showPillars && !empty($pillars)): ?>
<section class="hq-projects-pro-pillars">
  <div class="container-site">
    <div class="hq-projects-pro-pillars__grid">
      <?php foreach ($pillars as $i => $item): ?>
      <article class="hq-projects-pro-pillar" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-projects-pro-pillar__icon"><i class="bi <?= e($item['icon'] ?? 'bi-shield-check') ?>"></i></span>
        <h3><?= e($item['title'] ?? '') ?></h3>
        <p><?= e($item['text'] ?? '') ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCatalog): ?>
<section class="hq-projects-pro-catalog" id="portfolio">
  <div class="container-site">
    <header class="hq-projects-pro-section-head" data-anim="up">
      <p class="hq-projects-pro-eyebrow"><?= e($catalogKicker) ?></p>
      <h2 class="hq-projects-pro-title"><?= e($catalogTitle) ?></h2>
      <p class="hq-projects-pro-lead hq-projects-pro-section-head__lead"><?= e($catalogLead) ?></p>
    </header>

    <?php if ((!empty($categories) && count($categories) > 1) || !empty($statuses) || $hasFilters): ?>
    <div class="hq-projects-pro-toolbar" data-anim="up" data-delay="50">
      <?php if (!empty($categories) && count($categories) > 1): ?>
      <div class="hq-projects-pro-toolbar__group">
        <span class="hq-projects-pro-toolbar__label">Category</span>
        <div class="hq-projects-pro-filters">
          <a href="<?= e($filterUrl('', null)) ?>" class="hq-projects-pro-filter<?= !$category ? ' is-active' : '' ?>">All</a>
          <?php foreach ($categories as $cat): ?>
          <a href="<?= e($filterUrl($cat, null)) ?>" class="hq-projects-pro-filter<?= $category === $cat ? ' is-active' : '' ?>"><?= e(projectCategoryLabel($cat)) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($statuses)): ?>
      <div class="hq-projects-pro-toolbar__group">
        <span class="hq-projects-pro-toolbar__label">Status</span>
        <div class="hq-projects-pro-filters">
          <a href="<?= e($filterUrl(null, '')) ?>" class="hq-projects-pro-filter<?= !$status ? ' is-active' : '' ?>">All</a>
          <?php foreach ($statuses as $st): ?>
          <a href="<?= e($filterUrl(null, $st)) ?>" class="hq-projects-pro-filter<?= $status === $st ? ' is-active' : '' ?>"><?= e($statusLabels[$st] ?? projectStatusLabel($st)) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($hasFilters): ?>
      <a href="<?= url('projects') ?>" class="hq-projects-pro-clear"><i class="bi bi-x-lg"></i> Clear filters</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <p class="hq-projects-pro-count" data-anim="up" data-delay="60">
      <?= $stats['showing'] === 1 ? '1 project' : number_format($stats['showing']) . ' projects' ?>
      <?= $hasFilters ? ' matching your filters' : ' from Garowe, Puntland' ?>
    </p>

    <?php if (empty($projects)): ?>
    <div class="hq-projects-pro-empty" data-anim="up">
      <span class="hq-projects-pro-empty__icon"><i class="bi bi-buildings"></i></span>
      <h3>No projects match these filters</h3>
      <p>Clear the filters to see the full HighQ Homes portfolio.</p>
      <a href="<?= url('projects') ?>" class="hq-btn hq-btn--outline">View all projects</a>
    </div>
    <?php else: ?>
    <div class="hq-projects-pro-grid">
      <?php foreach ($projects as $i => $proj): ?>
        <?php View::partial('projects/portfolio-card', [
          'project' => $proj,
          'index'   => $i,
          'delay'   => ($i % 9) * 45,
          'size'    => $cardSize($i, count($projects)),
        ]); ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showApproach && !empty($approachItems)): ?>
<section class="hq-projects-pro-approach">
  <div class="container-site">
    <div class="hq-projects-pro-approach__shell">
      <header class="hq-projects-pro-approach__head" data-anim="left">
        <p class="hq-projects-pro-eyebrow"><?= e($approachKicker) ?></p>
        <h2 class="hq-projects-pro-title"><?= e($approachTitle) ?></h2>
        <p class="hq-projects-pro-lead"><?= e($approachLead) ?></p>
      </header>
      <div class="hq-projects-pro-approach__steps" data-anim="right">
        <?php foreach ($approachItems as $i => $item): ?>
        <article class="hq-projects-pro-approach__step">
          <span class="hq-projects-pro-approach__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <div>
            <strong><i class="bi <?= e($item['icon'] ?? 'bi-check-circle') ?>"></i> <?= e($item['title'] ?? '') ?></strong>
            <p><?= e($item['text'] ?? '') ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-projects-pro-cta" aria-labelledby="projects-cta-title">
  <div class="hq-projects-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-projects-pro-cta__box" data-anim="up">
    <div class="hq-projects-pro-cta__copy">
      <p class="hq-projects-pro-eyebrow hq-projects-pro-eyebrow--light"><?= e($ctaKicker) ?></p>
      <h2 id="projects-cta-title" class="hq-projects-pro-cta__title"><?= e($ctaTitle) ?></h2>
      <p class="hq-projects-pro-cta__lead"><?= e($ctaLead) ?></p>
    </div>
    <div class="hq-projects-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMap['cta_primary'] ?? 'Get a quote') ?></a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg"><?= e($ctaMap['cta_secondary'] ?? 'Contact us') ?></a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-projects-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
