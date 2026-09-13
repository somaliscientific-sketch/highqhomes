<?php
$bodyPage  = 'services';
$pageTitle = e($service['title']) . ' — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20quote%20for%20' . rawurlencode($service['title']);
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$sImg      = serviceImageUrl($service);

$serviceMeta = [
  'architecture' => [
    'deliverables' => ['Concept & schematic design', 'Detailed architectural drawings', '3D visualisations', 'Regulatory coordination', 'Construction documentation'],
    'ideal_for'    => 'New builds, extensions, and commercial structures requiring cohesive architectural vision.',
  ],
  'exterior-design' => [
    'deliverables' => ['Facade concept development', 'Material & colour palettes', 'Elevation drawings', 'Lighting & signage integration', 'Exterior finishing specs'],
    'ideal_for'    => 'Clients who want standout curb appeal with durable, climate-appropriate exterior solutions.',
  ],
  'landscape-design' => [
    'deliverables' => ['Site landscape master plan', 'Planting & hardscape layout', 'Outdoor living zones', 'Irrigation & drainage guidance', 'Maintenance recommendations'],
    'ideal_for'    => 'Residential compounds, commercial entrances, and outdoor spaces that complement the built form.',
  ],
  'site-planning' => [
    'deliverables' => ['Site analysis & constraints review', 'Master layout planning', 'Access & circulation design', 'Utility routing guidance', 'Phased development planning'],
    'ideal_for'    => 'Landowners and developers optimising plot use before design and construction begin.',
  ],
  'interior-design' => [
    'deliverables' => ['Space planning & layouts', 'Material & finish schedules', 'Custom joinery concepts', 'Lighting & furniture coordination', 'Installation oversight'],
    'ideal_for'    => 'Homes, offices, and hospitality spaces needing functional, elegant interiors.',
  ],
  'furniture-design' => [
    'deliverables' => ['Bespoke furniture concepts', 'Material selection', 'Production drawings', 'Workshop coordination', 'Delivery & placement'],
    'ideal_for'    => 'Projects requiring custom pieces aligned with architectural and interior themes.',
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
$idealFor = $meta['ideal_for'] ?? 'Residential and commercial clients seeking professional design and build support in Puntland.';

$processSteps = [
  ['icon' => 'bi-chat-dots', 'title' => 'Discovery', 'text' => 'We align on goals, constraints, and budget before work begins.'],
  ['icon' => 'bi-pencil-square', 'title' => 'Design', 'text' => 'Concepts, drawings, and approvals with clear revision rounds.'],
  ['icon' => 'bi-hammer', 'title' => 'Delivery', 'text' => 'Execution with milestone reviews and quality assurance.'],
  ['icon' => 'bi-key', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care guidance.'],
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
      <span class="hq-services-pro-service__icon"><i class="bi <?= e($service['icon'] ?? 'bi-building') ?>"></i></span>
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
          <a href="<?= url('services/' . $s['slug']) ?>" class="hq-services-pro-nav__link<?= $s['id'] === $service['id'] ? ' is-active' : '' ?>">
            <i class="bi <?= e($s['icon'] ?? 'bi-building') ?>"></i>
            <span><?= e($s['title']) ?></span>
            <i class="bi bi-chevron-right"></i>
          </a>
          <?php endforeach; ?>
        </nav>

        <div class="hq-services-pro-enquiry" data-anim="right" data-delay="50">
          <h3>Request a quote</h3>
          <p>Tell us about your project — we'll respond with scope, timeline, and next steps.</p>
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
          <p>Every service follows structured milestones, documented scope, and quality checks before handover.</p>
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
