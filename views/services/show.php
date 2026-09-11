<?php
$bodyPage  = 'services';
$pageTitle = e($service['title']) . ' — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20quote%20for%20' . rawurlencode($service['title']);
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$sImg      = serviceImageUrl($service);

$serviceMeta = [
  'residential-construction' => [
    'deliverables' => ['Written scope and milestone plan', 'Structure, envelope, and finishing under one team', 'Site updates you can follow', 'Quality checks before each stage is signed off', 'Handover walkthrough'],
    'ideal_for'    => 'Family villas, two-storey homes, and gated residences in Garowe that need one accountable builder.',
  ],
  'architecture-planning' => [
    'deliverables' => ['Site-aware concept drawings', 'Plot layout and openings', 'Buildable plans before construction starts', 'Milestone schedule', 'Coordination with the build team'],
    'ideal_for'    => 'Landowners who want a calm, practical house plan before the first block is laid.',
  ],
  'finishing-interiors' => [
    'deliverables' => ['Finish schedule for sun, dust, and daily use', 'Joinery, openings, and colour specified together', 'Interior and exterior finishing in one pass', 'Phased upgrades on existing homes', 'Inspection before handover'],
    'ideal_for'    => 'New builds and renovations that need a durable, complete interior — not leftover finishing.',
  ],
  'compounds-site-works' => [
    'deliverables' => ['Gate and boundary wall design', 'Approach and street elevation', 'Hardscape coordinated with the house', 'Security and proportion in one delivery', 'Finished compound at handover'],
    'ideal_for'    => 'Residences where the compound is the first room of the house.',
  ],
  'multi-unit-homes' => [
    'deliverables' => ['Paired or family-plot layout', 'Independent living with a composed street front', 'Shared structure, separate homes', 'Aligned levels, materials, and access', 'Delivery as one programme'],
    'ideal_for'    => 'Twin residences and plots that house two households without looking like an add-on.',
  ],
  'project-delivery' => [
    'deliverables' => ['Set-out and programme', 'Milestone control from foundation to keys', 'Site updates you can see', 'Inspections before the next stage', 'Finish standard matched to our portfolio'],
    'ideal_for'    => 'Clients who want the build managed to the same standard as the completed villas on this site.',
  ],
];

$meta         = $serviceMeta[$service['slug'] ?? ''] ?? null;
$deliverables = $meta['deliverables'] ?? [
  'Initial consultation & scope review',
  'Professional design documentation',
  'Material & finish recommendations',
  'Progress updates & quality checks',
  'Handover support',
];
$idealFor = $meta['ideal_for'] ?? 'Residential and commercial clients in Garowe who want a written scope, visible progress, and a finish they can inspect.';

$processSteps = [
  ['icon' => 'bi-search', 'title' => 'Discovery', 'text' => 'We walk the site, confirm your goals, and test what the plot can support.'],
  ['icon' => 'bi-rulers', 'title' => 'Design', 'text' => 'Drawings, a written scope, and a schedule with checkpoints you can follow.'],
  ['icon' => 'bi-hammer', 'title' => 'Build', 'text' => 'Disciplined site work with inspections before each stage is signed off.'],
  ['icon' => 'bi-key', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care when you move in.'],
];
?>

<section class="hq-services-pro-hero hq-services-pro-hero--service">
  <div class="hq-services-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($sImg) ?>" alt="" loading="eager">
  </div>
  <div class="hq-services-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-services-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= url('services') ?>">Services</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= e($service['title']) ?></span>
    </nav>
    <div class="hq-services-pro-hero__content" data-anim="up">
      <p class="hq-services-pro-hero__kicker">Service</p>
      <h1 class="hq-services-pro-hero__title"><?= e($service['title']) ?></h1>
      <?php if (!empty($service['short_description'])): ?>
      <p class="hq-services-pro-hero__lead"><?= e($service['short_description']) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="hq-services-pro-detail">
  <div class="container-site">
    <div class="hq-services-pro-detail__layout">
      <div class="hq-services-pro-detail__main">
        <div class="hq-services-pro-detail__gallery" data-anim="up">
          <img src="<?= e($sImg) ?>" alt="<?= e($service['title']) ?>" class="hq-services-pro-detail__hero-img" loading="eager">
        </div>

        <article class="hq-services-pro-detail__content" data-anim="up" data-delay="50">
          <h2 class="hq-services-pro-detail__heading">Service overview</h2>
          <div class="hq-services-pro-detail__prose hq-cms-content">
            <?php if (!empty($service['description'])): ?>
            <?= $service['description'] ?>
            <?php else: ?>
            <p><?= e($service['short_description'] ?? '') ?></p>
            <p><?= e($idealFor) ?></p>
            <?php endif; ?>
          </div>
        </article>

        <section class="hq-services-pro-detail__deliverables" data-anim="up" data-delay="70">
          <h2 class="hq-services-pro-detail__heading">What you receive</h2>
          <ul class="hq-services-pro-deliverables">
            <?php foreach ($deliverables as $item): ?>
            <li><i class="bi bi-check-circle-fill"></i><span><?= e($item) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </section>

        <section class="hq-services-pro-detail__process" data-anim="up" data-delay="90">
          <h2 class="hq-services-pro-detail__heading">How we deliver this service</h2>
          <div class="hq-services-pro-detail__steps">
            <?php foreach ($processSteps as $step): ?>
            <article class="hq-services-pro-detail__step">
              <span class="hq-services-pro-detail__step-icon"><i class="bi <?= e($step['icon']) ?>"></i></span>
              <div>
                <strong><?= e($step['title']) ?></strong>
                <p><?= e($step['text']) ?></p>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
        </section>

        <aside class="hq-services-pro-detail__ideal" data-anim="up" data-delay="110">
          <h3><i class="bi bi-lightbulb"></i> Ideal for</h3>
          <p><?= e($idealFor) ?></p>
        </aside>
      </div>

      <aside class="hq-services-pro-detail__aside">
        <nav class="hq-services-pro-nav" data-anim="right">
          <strong>All services</strong>
          <?php foreach ($allServices as $s): ?>
          <a href="<?= url('services/' . $s['slug']) ?>" class="hq-services-pro-nav__link<?= ($s['slug'] ?? '') === ($service['slug'] ?? '') ? ' is-active' : '' ?>">
            <i class="bi <?= e($s['icon'] ?? 'bi-building') ?>"></i>
            <span><?= e($s['title']) ?></span>
            <i class="bi bi-chevron-right"></i>
          </a>
          <?php endforeach; ?>
        </nav>

        <div class="hq-services-pro-enquiry" data-anim="right" data-delay="50">
          <h3>Request a quote</h3>
          <p>Tell us about the plot and the brief — we will come back with a clear plan and an honest quote.</p>
          <div class="hq-services-pro-enquiry__actions">
            <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--block"><i class="bi bi-whatsapp"></i> WhatsApp enquiry</a>
            <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline hq-btn--block">Contact form</a>
            <?php if ($phone !== ''): ?>
            <a href="tel:<?= e($phoneHref) ?>" class="hq-services-pro-enquiry__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
            <?php endif; ?>
          </div>
        </div>

        <div class="hq-services-pro-aside-note">
          <h4><i class="bi bi-shield-check"></i> Quality commitment</h4>
          <p>Every service follows a written scope, site updates, and a finish you can inspect before handover.</p>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php if (!empty($related)): ?>
<section class="hq-services-pro-related">
  <div class="container-site">
    <header class="hq-services-pro-section-head" data-anim="up">
      <p class="hq-services-pro-eyebrow">Related services</p>
      <h2 class="hq-services-pro-title">Often combined with this service</h2>
    </header>
    <div class="hq-services-pro-related__grid">
      <?php foreach ($related as $i => $rs): ?>
      <?php $rImg = serviceImageUrl($rs); ?>
      <a href="<?= url('services/' . $rs['slug']) ?>" class="hq-services-pro-related__card" data-anim="up" data-delay="<?= $i * 55 ?>">
        <img src="<?= e($rImg) ?>" alt="<?= e($rs['title']) ?>" loading="lazy">
        <div>
          <span><i class="bi <?= e($rs['icon'] ?? 'bi-building') ?>"></i> Service</span>
          <strong><?= e($rs['title']) ?></strong>
        </div>
        <i class="bi bi-arrow-up-right"></i>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="hq-services-pro-related__back">
      <a href="<?= url('services') ?>" class="hq-btn hq-btn--outline"><i class="bi bi-arrow-left"></i> All services</a>
    </div>
  </div>
</section>
<?php endif; ?>
