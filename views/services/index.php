<?php
$bodyPage  = 'services';
$pageTitle = $seo['meta_title'] ?? 'Construction & Design Services — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20a%20construction%20quote';
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

$cms        = $sections ?? [];
$hero       = cmsRow($cms, 'hero');
$intro      = cmsRow($cms, 'intro');
$catalogSec = cmsRow($cms, 'catalog');
$process    = cmsRow($cms, 'process');
$faqBlock   = cmsRow($cms, 'faq');
$ctaSec     = cmsRow($cms, 'cta');
$ctaMap     = cmsMap($ctaSec);

$heroImageDefault = asset('images/builds/modern-villa.jpg');
$heroKicker = $copyIf(cmsText($hero, 'title', ''), ['Our Services'], 'Our services');
$heroTitle  = $copyIf(cmsText($hero, 'subtitle', ''), ['Design, build & deliver with one trusted team'], 'One team from drawings to handover.');
$heroLead   = $copyIf(cmsText($hero, 'content', ''), [
    'From architecture to finishing — clear scope, premium quality, and accountable delivery at every step.',
], 'Architecture, construction, and finishing in Garowe — a written scope, visible progress, and a finish you can inspect.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', $heroImageDefault);
if ($heroImage === '' || str_contains($heroImage, 'unsplash.com')) {
    $heroImage = $heroImageDefault;
}

$heroBadges = [
    'Based in Garowe',
    'Clear quotes',
    'On-site photos',
    'Handover you can walk',
];
$cmsBadges = cmsList($hero);
if ($cmsBadges !== [] && !in_array('Licensed & Insured', $cmsBadges, true) && !in_array('Transparent Quotes', $cmsBadges, true)) {
    $mapped = [];
    foreach ($cmsBadges as $badge) {
        if (is_string($badge) && $badge !== '') {
            $mapped[] = $badge;
        }
    }
    if ($mapped !== []) {
        $heroBadges = $mapped;
    }
}

$processSteps = [
    ['num' => '01', 'title' => 'Discovery', 'text' => 'We walk the site, confirm your goals, and test what the plot can support.'],
    ['num' => '02', 'title' => 'Design & planning', 'text' => 'Drawings, a written scope, and a schedule with checkpoints you can follow.'],
    ['num' => '03', 'title' => 'Build execution', 'text' => 'Disciplined site work with inspections before each stage is signed off.'],
    ['num' => '04', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care when you move in.'],
];
$cmsProcess = cmsList($process);
if ($cmsProcess !== []) {
    $titles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsProcess);
    $seed = ['Consultation', 'Design & Scope', 'Build & QA', 'Handover'];
    $processSteps = array_intersect($seed, $titles) === $seed ? $processSteps : $cmsProcess;
}

$faqItems = [
    ['q' => 'Do you offer design without construction?', 'a' => 'Yes. Architecture and planning can be delivered on their own, or as part of a full design-build.', 'icon' => 'bi-rulers'],
    ['q' => 'Can one team handle the whole project?', 'a' => 'That is how we prefer to work — design, construction, and finishing under one standard so nothing is lost between trades.', 'icon' => 'bi-layers'],
    ['q' => 'How do quotes work?', 'a' => 'We price a written scope with materials and milestones before work begins. You know what is included, and what is not.', 'icon' => 'bi-calculator'],
    ['q' => 'Do you take renovations and finishing only?', 'a' => 'Yes. New builds, extensions, compound works, and phased finishing upgrades are all in scope.', 'icon' => 'bi-tools'],
];
$cmsFaq = cmsList($faqBlock);
if ($cmsFaq !== []) {
    $qs = array_map(static fn(array $row): string => (string)($row['q'] ?? ''), $cmsFaq);
    $seed = ['Do you offer design-only services?', 'Can I combine multiple services?', 'How are quotes structured?', 'Do you handle renovations?'];
    $faqItems = array_intersect($seed, $qs) === $seed ? $faqItems : $cmsFaq;
}

if (empty($services)) {
    $services = serviceShowcaseItems();
    $stats['total'] = count($services);
    $stats['featured'] = count(array_filter($services, static fn(array $row): bool => !empty($row['is_featured'])));
}

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

$introKicker = $copyIf((string)($intro['title'] ?? ''), ['What We Deliver'], 'What we deliver');
$introTitle  = $copyIf((string)($intro['subtitle'] ?? ''), ['One team. Every phase.'], 'Clear scope. One accountable team.');
$introLead   = $copyIf((string)($intro['content'] ?? ''), [
    'Each service is managed with structured milestones, quality inspections, and clear communication — so you always know where your project stands.',
], 'Pick a service, or ask us to combine them. Every engagement has a written scope, site updates, and a handover you can walk.');

$catalogKicker = $copyIf(cmsText($catalogSec, 'title', ''), ['Service catalog'], 'Services');
$catalogTitle  = $copyIf(cmsText($catalogSec, 'subtitle', ''), ['Everything your project needs'], 'What we build and deliver');
$catalogLead   = $copyIf(cmsText($catalogSec, 'content', ''), [
    'Architecture, design, planning, and finishing — delivered with professional oversight from first sketch to final handover.',
], 'Six services, one standard. Open a card for scope, process, and how we work in Garowe.');

$processKicker = $copyIf((string)($process['title'] ?? ''), ['How We Work'], 'How we work');
$processTitle  = $copyIf((string)($process['subtitle'] ?? ''), ['Simple steps, clear delivery'], 'From site visit to keys');
$processLead   = $copyIf((string)($process['content'] ?? ''), [
    'A structured path that keeps your project transparent, controlled, and on schedule.',
], 'A short path you can follow — no surprise stages, no split accountability.');

$ctaKicker = $copyIf(cmsText($ctaSec, 'title', ''), ['Start your project'], 'Start a project');
$ctaTitle  = $copyIf(cmsText($ctaSec, 'subtitle', ''), ['Ready to build with confidence?'], 'Tell us what you want to build.');
$ctaLead   = $copyIf(cmsText($ctaSec, 'content', ''), [
    'Get a free consultation — scope, budget, and timeline with no obligation.',
], 'Share the plot and the brief. We will come back with a clear plan and an honest quote.');
?>

<script type="application/ld+json"><?= $schemaJson ?></script>

<?php if (cmsRowEnabled($cms, 'hero', true)): ?>
<section class="hq-services-pro-hero hq-services-pro-hero--cinematic">
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
      <div class="hq-services-pro-hero__actions">
        <a href="#svc-catalog" class="hq-btn hq-btn--orange hq-btn--lg">Explore services <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      </div>
      <?php if (!empty($heroBadges)): ?>
      <ul class="hq-services-pro-hero__chips">
        <?php foreach ($heroBadges as $badge): ?>
        <li><i class="bi bi-check-circle-fill"></i> <?= e($badge) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (cmsRowEnabled($cms, 'intro', true)): ?>
<section class="hq-services-pro-intro" aria-label="Company highlights">
  <div class="container-site">
    <div class="hq-services-pro-intro__shell" data-anim="up">
      <div class="hq-services-pro-intro__copy">
        <p class="hq-services-pro-eyebrow"><?= e($introKicker) ?></p>
        <h2 class="hq-services-pro-title"><?= e($introTitle) ?></h2>
        <p class="hq-services-pro-lead"><?= e($introLead) ?></p>
      </div>
      <div class="hq-services-pro-intro__stats">
        <?php foreach ($statItems ?? [] as $stat): ?>
        <div class="hq-services-pro-stat">
          <strong><?= e($stat['num']) ?><?= e($stat['suffix'] ?? '') ?></strong>
          <span><?= e($stat['label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (cmsRowEnabled($cms, 'catalog', true)): ?>
<section class="hq-services-pro-catalog" id="svc-catalog" aria-labelledby="svc-catalog-title">
  <div class="container-site">
    <header class="hq-services-pro-section-head" data-anim="up">
      <p class="hq-services-pro-eyebrow"><?= e($catalogKicker) ?></p>
      <h2 id="svc-catalog-title" class="hq-services-pro-title"><?= e($catalogTitle) ?></h2>
      <p class="hq-services-pro-lead hq-services-pro-section-head__lead"><?= e($catalogLead) ?></p>
    </header>

    <div class="hq-services-pro-grid">
      <?php foreach ($services as $i => $svc): ?>
        <?php View::partial('services/service-card', ['service' => $svc, 'index' => $i]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (cmsRowEnabled($cms, 'process', true) && !empty($processSteps)): ?>
<section class="hq-services-pro-process" aria-labelledby="svc-process-title">
  <div class="container-site">
    <div class="hq-services-pro-process__shell">
      <header class="hq-services-pro-process__head" data-anim="left">
        <p class="hq-services-pro-eyebrow"><?= e($processKicker) ?></p>
        <h2 id="svc-process-title" class="hq-services-pro-title"><?= e($processTitle) ?></h2>
        <p class="hq-services-pro-lead"><?= e($processLead) ?></p>
      </header>
      <ol class="hq-services-pro-process__steps" data-anim="right">
        <?php foreach ($processSteps as $i => $step): ?>
        <li>
          <span class="hq-services-pro-process__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
          <div>
            <h3><?= e($step['title'] ?? '') ?></h3>
            <p><?= e($step['text'] ?? $step['body'] ?? '') ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (cmsRowEnabled($cms, 'faq', true) && !empty($faqItems)): ?>
<section class="hq-services-pro-faq" id="svc-faq" aria-labelledby="svc-faq-title">
  <div class="container-site">
    <div class="hq-services-pro-faq__shell">
      <header class="hq-services-pro-faq__head" data-anim="up">
        <p class="hq-services-pro-eyebrow"><?= e($faqBlock['title'] ?? 'FAQ') ?></p>
        <h2 id="svc-faq-title" class="hq-services-pro-title"><?= e($copyIf((string)($faqBlock['subtitle'] ?? ''), ['Common questions'], 'Questions we hear first')) ?></h2>
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

<?php if (cmsRowEnabled($cms, 'cta', true)): ?>
<section class="hq-services-pro-cta" aria-labelledby="svc-cta-title">
  <div class="hq-services-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-services-pro-cta__box" data-anim="up">
    <div class="hq-services-pro-cta__copy">
      <p class="hq-services-pro-eyebrow hq-services-pro-eyebrow--light"><?= e($ctaKicker) ?></p>
      <h2 id="svc-cta-title" class="hq-services-pro-cta__title"><?= e($ctaTitle) ?></h2>
      <p class="hq-services-pro-cta__lead"><?= e($ctaLead) ?></p>
    </div>
    <div class="hq-services-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMap['cta_primary'] ?? 'Get a quote') ?></a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-services-pro-cta__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
