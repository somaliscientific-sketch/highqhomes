<?php
$pageTitle = $seo['meta_title'] ?? 'Our Projects — HighQ Homes';
$bodyPage  = 'projects';

$hasFilters = (bool)($category || $status);

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20discuss%20a%20project';
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);

$heroImage = 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80';

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
    ['icon' => 'bi-clipboard-data', 'title' => 'Structured delivery', 'text' => 'Milestone planning, progress updates, and quality checks at every phase.'],
    ['icon' => 'bi-gem', 'title' => 'Premium finishes', 'text' => 'Materials and craftsmanship held to standards you can see and measure.'],
    ['icon' => 'bi-shield-check', 'title' => 'Transparent scope', 'text' => 'Clear pricing, documented scope, and accountable communication.'],
    ['icon' => 'bi-geo-alt', 'title' => 'Local expertise', 'text' => 'Deep experience building across Puntland — from Garowe to Bosaso.'],
];

$approachItems = [
    ['icon' => 'bi-search', 'title' => 'Discovery', 'text' => 'Site review, goals, and feasibility before design begins.'],
    ['icon' => 'bi-rulers', 'title' => 'Design & planning', 'text' => 'Drawings, approvals, and a milestone schedule you can track.'],
    ['icon' => 'bi-hammer', 'title' => 'Build execution', 'text' => 'Disciplined site work with structured QA inspections.'],
    ['icon' => 'bi-key', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care support.'],
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

<section class="hq-projects-pro-hero">
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
      <p class="hq-projects-pro-hero__kicker">Portfolio</p>
      <h1 class="hq-projects-pro-hero__title">Projects delivered with confidence</h1>
      <p class="hq-projects-pro-hero__lead">Explore residential, commercial, and community builds — each delivered with structured planning, quality control, and premium finishes.</p>
      <div class="hq-projects-pro-hero__actions">
        <a href="#portfolio" class="hq-btn hq-btn--orange hq-btn--lg">Browse portfolio <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Discuss a project</a>
      </div>
    </div>
  </div>
</section>

<section class="hq-projects-pro-intro" aria-label="Portfolio highlights">
  <div class="container-site">
    <div class="hq-projects-pro-intro__shell" data-anim="up">
      <div class="hq-projects-pro-intro__copy">
        <p class="hq-projects-pro-eyebrow">Our work</p>
        <h2 class="hq-projects-pro-title">Built to last. Designed to impress.</h2>
        <p class="hq-projects-pro-lead">Every project reflects our commitment to transparent delivery, disciplined craftsmanship, and spaces people trust for generations.</p>
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
          <strong><?= number_format($stats['categories']) ?></strong>
          <span>Categories</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="hq-projects-pro-pillars">
  <div class="container-site">
    <div class="hq-projects-pro-pillars__grid">
      <?php foreach ($pillars as $i => $item): ?>
      <article class="hq-projects-pro-pillar" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-projects-pro-pillar__icon"><i class="bi <?= e($item['icon']) ?>"></i></span>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hq-projects-pro-catalog" id="portfolio">
  <div class="container-site">
    <header class="hq-projects-pro-section-head" data-anim="up">
      <p class="hq-projects-pro-eyebrow">Project portfolio</p>
      <h2 class="hq-projects-pro-title">Explore our builds</h2>
      <p class="hq-projects-pro-lead hq-projects-pro-section-head__lead">Filter by category or status to find projects similar to yours.</p>
    </header>

    <div class="hq-projects-pro-toolbar" data-anim="up" data-delay="50">
      <?php if (!empty($categories)): ?>
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

      <div class="hq-projects-pro-toolbar__group">
        <span class="hq-projects-pro-toolbar__label">Status</span>
        <div class="hq-projects-pro-filters">
          <a href="<?= e($filterUrl(null, '')) ?>" class="hq-projects-pro-filter<?= !$status ? ' is-active' : '' ?>">All</a>
          <?php foreach (['completed' => 'Completed', 'in_progress' => 'In Progress', 'planned' => 'Planned'] as $st => $label): ?>
          <a href="<?= e($filterUrl(null, $st)) ?>" class="hq-projects-pro-filter<?= $status === $st ? ' is-active' : '' ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ($hasFilters): ?>
      <a href="<?= url('projects') ?>" class="hq-projects-pro-clear"><i class="bi bi-x-lg"></i> Clear filters</a>
      <?php endif; ?>
    </div>

    <p class="hq-projects-pro-count" data-anim="up" data-delay="60">
      <?= $stats['showing'] === 1 ? '1 project' : number_format($stats['showing']) . ' projects' ?>
      <?= $hasFilters ? ' matching your filters' : '' ?>
    </p>

    <?php if (empty($projects)): ?>
    <div class="hq-projects-pro-empty" data-anim="up">
      <span class="hq-projects-pro-empty__icon"><i class="bi bi-buildings"></i></span>
      <h3>No projects found</h3>
      <p>Try adjusting your filters or browse the full portfolio.</p>
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

<section class="hq-projects-pro-approach">
  <div class="container-site">
    <div class="hq-projects-pro-approach__shell">
      <header class="hq-projects-pro-approach__head" data-anim="left">
        <p class="hq-projects-pro-eyebrow">Our approach</p>
        <h2 class="hq-projects-pro-title">How every project is delivered</h2>
        <p class="hq-projects-pro-lead">From first site visit to final handover — a proven path that keeps builds on schedule and quality on point.</p>
      </header>
      <div class="hq-projects-pro-approach__steps" data-anim="right">
        <?php foreach ($approachItems as $i => $item): ?>
        <article class="hq-projects-pro-approach__step">
          <span class="hq-projects-pro-approach__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <div>
            <strong><i class="bi <?= e($item['icon']) ?>"></i> <?= e($item['title']) ?></strong>
            <p><?= e($item['text']) ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="hq-projects-pro-cta" aria-labelledby="projects-cta-title">
  <div class="hq-projects-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-projects-pro-cta__box" data-anim="up">
    <div class="hq-projects-pro-cta__copy">
      <p class="hq-projects-pro-eyebrow hq-projects-pro-eyebrow--light">Start your build</p>
      <h2 id="projects-cta-title" class="hq-projects-pro-cta__title">Ready to add your project to our portfolio?</h2>
      <p class="hq-projects-pro-cta__lead">Share your vision with our team for a clear plan, honest timeline, and premium delivery.</p>
    </div>
    <div class="hq-projects-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">Contact us</a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-projects-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
