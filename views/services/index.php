<?php
$bodyPage  = 'services';
$pageTitle = $seo['meta_title'] ?? 'Construction & Design Services — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20a%20construction%20quote';
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);

$cms      = $sections ?? [];
$hero     = $cms['hero'] ?? [];
$intro    = $cms['intro'] ?? [];
$process  = $cms['process'] ?? [];
$faqBlock = $cms['faq'] ?? [];

$heroKicker = $hero['title'] ?? 'Our Services';
$heroTitle  = $hero['subtitle'] ?? 'Design, build & deliver with one trusted team';
$heroLead   = $hero['content'] ?? 'From architecture to finishing — clear scope, premium quality, and accountable delivery at every step.';
$heroImage  = !empty($hero['image_url']) ? (str_starts_with($hero['image_url'], 'http') ? $hero['image_url'] : uploadUrl($hero['image_url'])) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80';

$heroBadges = is_array($hero['data'] ?? null) ? $hero['data'] : [
  'Licensed & Insured',
  'Transparent Quotes',
  'Premium Finishes',
  'On-Time Delivery',
];

$processSteps = is_array($process['data'] ?? null) ? $process['data'] : [
  ['num' => '01', 'title' => 'Consultation', 'text' => 'Goals, site, budget, and timeline.'],
  ['num' => '02', 'title' => 'Design & Scope', 'text' => 'Drawings and transparent pricing.'],
  ['num' => '03', 'title' => 'Build & QA', 'text' => 'Execution with quality checks.'],
  ['num' => '04', 'title' => 'Handover', 'text' => 'Walkthrough and after-care.'],
];

$faqItems = is_array($faqBlock['data'] ?? null) ? $faqBlock['data'] : [
  ['q' => 'Do you offer design-only services?', 'a' => 'Yes. Architecture, exterior, interior, and site planning are available standalone or as integrated packages.', 'icon' => 'bi-rulers'],
  ['q' => 'Can I combine multiple services?', 'a' => 'Most clients choose one team for design, construction, and finishing — we coordinate everything end to end.', 'icon' => 'bi-layers'],
  ['q' => 'How are quotes structured?', 'a' => 'Milestone-based with clear scope, materials, and timeline before work begins.', 'icon' => 'bi-calculator'],
  ['q' => 'Do you handle renovations?', 'a' => 'Yes — new builds, renovations, premium finishing, and phased upgrades.', 'icon' => 'bi-tools'],
];

$pillars = [
  ['icon' => 'bi-diagram-3', 'title' => 'Integrated delivery', 'text' => 'Design, construction, and finishing coordinated under one accountable team.'],
  ['icon' => 'bi-clipboard-check', 'title' => 'Clear milestones', 'text' => 'Structured scope, progress updates, and quality inspections at every phase.'],
  ['icon' => 'bi-gem', 'title' => 'Premium standards', 'text' => 'Materials and workmanship selected for durability in local conditions.'],
  ['icon' => 'bi-headset', 'title' => 'Responsive support', 'text' => 'Direct communication from consultation through handover and after-care.'],
];

$scopeItems = [
  ['icon' => 'bi-building', 'title' => 'Residential builds', 'text' => 'Homes, villas, and gated communities with full design-build support.'],
  ['icon' => 'bi-shop', 'title' => 'Commercial projects', 'text' => 'Offices, retail, and mixed-use spaces built to operational requirements.'],
  ['icon' => 'bi-brush', 'title' => 'Finishing & upgrades', 'text' => 'Interior fit-outs, exterior refreshes, and phased renovation work.'],
];

$schemaServices = array_map(static fn(array $s): array => [
  '@type'       => 'Service',
  'name'        => $s['title'],
  'description' => $s['short_description'] ?? '',
  'url'         => url('services/' . $s['slug']),
  'provider'    => ['@type' => 'Organization', 'name' => $settings['site_name'] ?? 'HighQ Homes'],
], $services);

$schemaJson = json_encode([
  '@context'        => 'https://schema.org',
  '@type'           => 'ItemList',
  'name'            => 'HighQ Homes Construction Services',
  'itemListElement' => array_values(array_map(static fn(array $item, int $i): array => [
    '@type'    => 'ListItem',
    'position' => $i + 1,
    'item'     => $item,
  ], $schemaServices, array_keys($schemaServices))),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<script type="application/ld+json"><?= $schemaJson ?></script>

<section class="hq-services-pro-hero">
  <div class="hq-services-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-services-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-services-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Services</span>
    </nav>
    <div class="hq-services-pro-hero__content" data-anim="up">
      <p class="hq-services-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-services-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-services-pro-hero__lead"><?= e($heroLead) ?></p>
      <?php if (!empty($heroBadges)): ?>
      <ul class="hq-services-pro-hero__badges">
        <?php foreach ($heroBadges as $badge): ?>
        <?php if (is_string($badge) && $badge !== ''): ?>
        <li><i class="bi bi-check-circle-fill"></i> <?= e($badge) ?></li>
        <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <div class="hq-services-pro-hero__actions">
        <a href="#svc-catalog" class="hq-btn hq-btn--orange hq-btn--lg">Explore services <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      </div>
    </div>
  </div>
</section>

<section class="hq-services-pro-intro" aria-label="Company highlights">
  <div class="container-site">
    <div class="hq-services-pro-intro__shell" data-anim="up">
      <div class="hq-services-pro-intro__copy">
        <p class="hq-services-pro-eyebrow"><?= e($intro['title'] ?? 'What We Deliver') ?></p>
        <h2 class="hq-services-pro-title"><?= e($intro['subtitle'] ?? 'One team. Every phase.') ?></h2>
        <p class="hq-services-pro-lead"><?= e($intro['content'] ?? 'Each service is managed with structured milestones, quality inspections, and clear communication — so you always know where your project stands.') ?></p>
      </div>
      <div class="hq-services-pro-intro__stats">
        <?php foreach ($statItems as $stat): ?>
        <div class="hq-services-pro-stat">
          <strong data-counter data-target="<?= e($stat['num']) ?>" data-suffix="<?= e($stat['suffix']) ?>"><?= e($stat['num']) ?><?= e($stat['suffix']) ?></strong>
          <span><?= e($stat['label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="hq-services-pro-pillars">
  <div class="container-site">
    <div class="hq-services-pro-pillars__grid">
      <?php foreach ($pillars as $i => $item): ?>
      <article class="hq-services-pro-pillar" data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-services-pro-pillar__icon"><i class="bi <?= e($item['icon']) ?>"></i></span>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hq-services-pro-catalog" id="svc-catalog" aria-labelledby="svc-catalog-title">
  <div class="container-site">
    <header class="hq-services-pro-section-head" data-anim="up">
      <p class="hq-services-pro-eyebrow">Service catalog</p>
      <h2 id="svc-catalog-title" class="hq-services-pro-title">Everything your project needs</h2>
      <p class="hq-services-pro-lead hq-services-pro-section-head__lead">Architecture, design, planning, and finishing — delivered with professional oversight from first sketch to final handover.</p>
    </header>

    <?php if (empty($services)): ?>
    <div class="hq-services-pro-empty" data-anim="up">
      <span class="hq-services-pro-empty__icon"><i class="bi bi-building"></i></span>
      <h3>Services coming soon</h3>
      <p>Published services will appear here. Contact us to discuss your project in the meantime.</p>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline">Contact us</a>
    </div>
    <?php else: ?>
    <p class="hq-services-pro-count" data-anim="up" data-delay="50">
      <?= $stats['total'] === 1 ? '1 service' : number_format($stats['total']) . ' services' ?>
      <?= $stats['featured'] > 0 ? ' · ' . number_format($stats['featured']) . ' featured' : '' ?>
    </p>
    <div class="hq-services-pro-grid">
      <?php foreach ($services as $i => $svc): ?>
        <?php View::partial('services/service-card', ['service' => $svc, 'index' => $i]); ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="hq-services-pro-scope">
  <div class="container-site">
    <div class="hq-services-pro-scope__shell">
      <div class="hq-services-pro-scope__copy" data-anim="left">
        <p class="hq-services-pro-eyebrow">Project scope</p>
        <h2 class="hq-services-pro-title">Built for residential &amp; commercial clients</h2>
        <p class="hq-services-pro-lead">Whether you need a single design discipline or full design-build delivery, we scale our team and timeline to match your project.</p>
        <ul class="hq-services-pro-scope__list">
          <li><i class="bi bi-check2-circle"></i> New builds with integrated architecture and construction</li>
          <li><i class="bi bi-check2-circle"></i> Renovations, extensions, and premium finishing upgrades</li>
          <li><i class="bi bi-check2-circle"></i> Site planning and landscape design for optimal land use</li>
          <li><i class="bi bi-check2-circle"></i> Bespoke interior and furniture solutions</li>
        </ul>
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--outline">View our work <i class="bi bi-arrow-up-right"></i></a>
      </div>
      <div class="hq-services-pro-scope__cards" data-anim="right">
        <?php foreach ($scopeItems as $item): ?>
        <article class="hq-services-pro-scope__card">
          <i class="bi <?= e($item['icon']) ?>"></i>
          <strong><?= e($item['title']) ?></strong>
          <span><?= e($item['text']) ?></span>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php if (($process['is_enabled'] ?? 1) && !empty($processSteps)): ?>
<section class="hq-services-pro-process" aria-labelledby="svc-process-title">
  <div class="container-site">
    <header class="hq-services-pro-section-head" data-anim="up">
      <p class="hq-services-pro-eyebrow"><?= e($process['title'] ?? 'How We Work') ?></p>
      <h2 id="svc-process-title" class="hq-services-pro-title"><?= e($process['subtitle'] ?? 'Simple steps, clear delivery') ?></h2>
      <?php if (!empty($process['content'])): ?>
      <p class="hq-services-pro-lead hq-services-pro-section-head__lead"><?= e($process['content']) ?></p>
      <?php endif; ?>
    </header>
    <ol class="hq-services-pro-process__steps">
      <?php foreach ($processSteps as $i => $step): ?>
      <li data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-services-pro-process__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
        <div>
          <h3><?= e($step['title']) ?></h3>
          <p><?= e($step['text']) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>

<?php if (($faqBlock['is_enabled'] ?? 1) && !empty($faqItems)): ?>
<section class="hq-services-pro-faq" id="svc-faq" aria-labelledby="svc-faq-title">
  <div class="container-site">
    <div class="hq-services-pro-faq__shell">
      <header class="hq-services-pro-faq__head" data-anim="up">
        <p class="hq-services-pro-eyebrow"><?= e($faqBlock['title'] ?? 'FAQ') ?></p>
        <h2 id="svc-faq-title" class="hq-services-pro-title"><?= e($faqBlock['subtitle'] ?? 'Common questions') ?></h2>
        <?php if (!empty($faqBlock['content'])): ?>
        <p class="hq-services-pro-lead"><?= e($faqBlock['content']) ?></p>
        <?php endif; ?>
      </header>
      <div class="hq-services-pro-faq__list" data-anim="up" data-delay="80">
        <?php foreach ($faqItems as $i => $faq): ?>
        <article class="hq-faq__item hq-services-pro-faq__item<?= $i === 0 ? ' is-open' : '' ?>">
          <button type="button" class="hq-faq__toggle" data-faq-toggle aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
            <?php if (!empty($faq['icon'])): ?>
            <i class="bi <?= e($faq['icon']) ?> hq-services-pro-faq__icon" aria-hidden="true"></i>
            <?php endif; ?>
            <span class="hq-faq__label"><?= e($faq['q']) ?></span>
            <i class="bi bi-plus-lg hq-faq__chev" aria-hidden="true"></i>
          </button>
          <div class="hq-faq__panel">
            <p><?= e($faq['a']) ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="hq-services-pro-cta" aria-labelledby="svc-cta-title">
  <div class="hq-services-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-services-pro-cta__box" data-anim="up">
    <div class="hq-services-pro-cta__copy">
      <p class="hq-services-pro-eyebrow hq-services-pro-eyebrow--light">Start your project</p>
      <h2 id="svc-cta-title" class="hq-services-pro-cta__title">Ready to build with confidence?</h2>
      <p class="hq-services-pro-cta__lead">Get a free consultation — scope, budget, and timeline with no obligation.</p>
    </div>
    <div class="hq-services-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">Contact us</a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-services-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
