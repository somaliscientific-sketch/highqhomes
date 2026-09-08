<?php
$bodyPage  = 'about';
$pageTitle = $seo['meta_title'] ?? 'About Us — HighQ Homes';

$siteName  = $settings['site_name'] ?? 'HighQ Homes';
$mission   = $settings['mission'] ?? '';
$vision    = $settings['vision'] ?? '';
$aboutText = $settings['about_text'] ?? '';
$phone     = $settings['phone'] ?? '+252 907 734 667';
$phoneHref = preg_replace('/\s+/', '', $phone);
$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20learn%20more%20about%20your%20services';

$cms     = $sections ?? [];
$hero    = $cms['hero'] ?? [];
$history = $cms['history'] ?? [];
$values  = $cms['values'] ?? [];
$process = $cms['process'] ?? [];

$heroKicker = $hero['title'] ?? 'Who We Are';
$heroTitle  = $hero['subtitle'] ?? 'Built on integrity. Delivered with precision.';
$heroLead   = $hero['content'] ?? ($settings['tagline'] ?? 'Premium construction across Puntland, Somalia.');
$heroImage  = !empty($hero['image_url']) ? (str_starts_with($hero['image_url'], 'http') ? $hero['image_url'] : uploadUrl($hero['image_url'])) : 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1920&q=80';

$historyTitle    = $history['title'] ?? 'Our History';
$historySubtitle = $history['subtitle'] ?? 'The HighQ Homes story';
$historyContent  = $history['content'] ?? ($aboutText ?: 'HighQ Homes was founded in Garowe with a clear purpose — deliver world-class residential and commercial construction with honest service, broad vision, and great value.');
$historyImage    = !empty($history['image_url']) ? (str_starts_with($history['image_url'], 'http') ? $history['image_url'] : uploadUrl($history['image_url'])) : (!empty($settings['home_about_image']) ? uploadUrl($settings['home_about_image']) : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80');

$historyData     = is_array($history['data'] ?? null) ? $history['data'] : [];
$historyFacts    = $historyData['facts'] ?? [
  ['label' => 'Founded', 'value' => '2020'],
  ['label' => 'Headquarters', 'value' => 'Garowe, Puntland'],
  ['label' => 'Focus', 'value' => 'Residential & Commercial'],
];
$historyTimeline = $historyData['timeline'] ?? [
  ['year' => '2020', 'text' => 'HighQ Homes established in Garowe with a mission to raise construction standards across Puntland.'],
  ['year' => '2021', 'text' => 'First residential and commercial projects delivered with structured quality control.'],
  ['year' => '2023', 'text' => 'Expanded integrated design, construction, and finishing services under one team.'],
  ['year' => 'Today', 'text' => 'Trusted partner for premium builds — from planning to handover.'],
];

$valueItems = is_array($values['data'] ?? null) ? $values['data'] : [
  ['icon' => 'bi-shield-check', 'title' => 'Integrity First', 'text' => 'Honest timelines, transparent pricing, and accountable communication.'],
  ['icon' => 'bi-gem', 'title' => 'Premium Quality', 'text' => 'Materials and workmanship held to standards you can measure.'],
  ['icon' => 'bi-people', 'title' => 'Client Partnership', 'text' => 'Responsive support before, during, and after handover.'],
  ['icon' => 'bi-compass', 'title' => 'Local Expertise', 'text' => 'Deep knowledge of Puntland sites, regulations, and community needs.'],
];

$processSteps = is_array($process['data'] ?? null) ? $process['data'] : [
  ['num' => '01', 'title' => 'Consultation', 'text' => 'Goals, site conditions, and budget expectations.'],
  ['num' => '02', 'title' => 'Planning', 'text' => 'Drawings, materials, and a milestone schedule.'],
  ['num' => '03', 'title' => 'Construction', 'text' => 'Disciplined execution with quality checks.'],
  ['num' => '04', 'title' => 'Handover', 'text' => 'Final walkthrough and after-care support.'],
];

$clientMeta = static function (array $t): string {
    return trim(($t['position'] ?? '') . (!empty($t['company']) ? ', ' . $t['company'] : ''));
};
?>

<section class="hq-about-pro-hero">
  <div class="hq-about-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-about-pro-hero__overlay" aria-hidden="true"></div>
  <div class="hq-about-pro-hero__mesh" aria-hidden="true"></div>

  <div class="container-site hq-about-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">About</span>
    </nav>

    <div class="hq-about-pro-hero__content" data-anim="up">
      <p class="hq-about-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-about-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-about-pro-hero__lead"><?= e($heroLead) ?></p>
      <div class="hq-about-pro-hero__actions">
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange hq-btn--lg">View our work <i class="bi bi-arrow-up-right"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      </div>
    </div>

    <nav class="hq-about-pro-hero__anchors" aria-label="About page sections">
      <a href="#our-story"><i class="bi bi-book"></i> Our story</a>
      <a href="#our-values"><i class="bi bi-heart"></i> Values</a>
      <a href="#our-process"><i class="bi bi-diagram-3"></i> Process</a>
      <?php if (!empty($team)): ?><a href="#our-team"><i class="bi bi-people"></i> Team</a><?php endif; ?>
    </nav>
  </div>
</section>

<section class="hq-about-pro-story" id="our-story">
  <div class="container-site">
    <div class="hq-about-pro-story__shell">
      <div class="hq-about-pro-story__media" data-anim="left">
        <div class="hq-about-pro-story__frame">
          <img src="<?= e($historyImage) ?>" alt="<?= e($siteName) ?> — our story" loading="eager">
        </div>
        <div class="hq-about-pro-story__badge">
          <strong><?= e($historyFacts[0]['value'] ?? '2020') ?></strong>
          <span>Established</span>
        </div>
        <div class="hq-about-pro-story__chip">
          <i class="bi bi-geo-alt-fill"></i> Garowe · Puntland
        </div>
      </div>

      <div class="hq-about-pro-story__copy" data-anim="right">
        <p class="hq-about-pro-eyebrow"><?= e($historyTitle) ?></p>
        <h2 class="hq-about-pro-title"><?= e($historySubtitle) ?></h2>
        <p class="hq-about-pro-lead"><?= e($historyContent) ?></p>

        <ul class="hq-about-pro-facts">
          <?php foreach ($historyFacts as $fact): ?>
          <li>
            <span><?= e($fact['label'] ?? '') ?></span>
            <strong><?= e($fact['value'] ?? '') ?></strong>
          </li>
          <?php endforeach; ?>
        </ul>

        <div class="hq-about-pro-story__actions">
          <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline">Talk to our team</a>
          <a href="tel:<?= e($phoneHref) ?>" class="hq-about-pro-link"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
        </div>
      </div>
    </div>

    <?php if (!empty($historyTimeline)): ?>
    <ol class="hq-about-pro-timeline">
      <?php foreach ($historyTimeline as $i => $item): ?>
      <li data-anim="up" data-delay="<?= $i * 50 ?>">
        <span class="hq-about-pro-timeline__dot" aria-hidden="true"></span>
        <span class="hq-about-pro-timeline__year"><?= e($item['year'] ?? '') ?></span>
        <p><?= e($item['text'] ?? '') ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>
  </div>
</section>

<section class="hq-about-pro-stats" aria-label="Company highlights">
  <div class="container-site">
    <ul class="hq-about-pro-stats__grid">
      <?php foreach ($statItems as $i => $stat): ?>
      <li data-anim="up" data-delay="<?= $i * 45 ?>">
        <span class="hq-about-pro-stats__icon"><i class="bi <?= e($stat['icon']) ?>"></i></span>
        <strong data-counter data-target="<?= e($stat['num']) ?>" data-suffix="<?= e($stat['suffix']) ?>"><?= e($stat['num']) ?><?= e($stat['suffix']) ?></strong>
        <span><?= e($stat['label']) ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="hq-about-pro-mv">
  <div class="container-site hq-about-pro-mv__grid">
    <article class="hq-about-pro-mv__card hq-about-pro-mv__card--mission" data-anim="up">
      <span class="hq-about-pro-mv__icon"><i class="bi bi-bullseye"></i></span>
      <p class="hq-about-pro-eyebrow">Our mission</p>
      <h3>Mission</h3>
      <p><?= e($mission ?: 'Deliver exceptional construction with premium materials, skilled craftsmanship, and unwavering integrity.') ?></p>
    </article>
    <article class="hq-about-pro-mv__card hq-about-pro-mv__card--vision" data-anim="up" data-delay="70">
      <span class="hq-about-pro-mv__icon"><i class="bi bi-compass"></i></span>
      <p class="hq-about-pro-eyebrow">Our vision</p>
      <h3>Vision</h3>
      <p><?= e($vision ?: 'Lead East Africa in sustainable, innovative construction that transforms communities.') ?></p>
    </article>
  </div>
</section>

<?php if (($values['is_enabled'] ?? 1) && !empty($valueItems)): ?>
<section class="hq-about-pro-values" id="our-values">
  <div class="hq-about-pro-values__bg" aria-hidden="true"></div>
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e($values['title'] ?? 'Our Values') ?></p>
      <h2 class="hq-about-pro-title"><?= e($values['subtitle'] ?? 'What guides every project') ?></h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead">Principles that shape how we plan, build, and support every client relationship.</p>
    </header>
    <div class="hq-about-pro-values__grid">
      <?php foreach ($valueItems as $i => $val): ?>
      <article class="hq-about-pro-value" data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-about-pro-value__icon"><i class="bi <?= e($val['icon'] ?? 'bi-star') ?>"></i></span>
        <h3><?= e($val['title']) ?></h3>
        <p><?= e($val['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (($process['is_enabled'] ?? 1) && !empty($processSteps)): ?>
<section class="hq-about-pro-process" id="our-process">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e($process['title'] ?? 'How We Work') ?></p>
      <h2 class="hq-about-pro-title"><?= e($process['subtitle'] ?? 'Simple steps, clear delivery') ?></h2>
      <?php if (!empty($process['content'])): ?>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e($process['content']) ?></p>
      <?php endif; ?>
    </header>
    <ol class="hq-about-pro-process__steps">
      <?php foreach ($processSteps as $i => $step): ?>
      <li data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-about-pro-process__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
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

<?php if (!empty($team)): ?>
<section class="hq-about-pro-team" id="our-team">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow">Leadership</p>
      <h2 class="hq-about-pro-title">The team behind every build</h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead">Experienced leaders guiding strategy, quality, and client care on every project.</p>
    </header>
    <div class="hq-about-pro-team__grid">
      <?php foreach ($team as $i => $m): ?>
        <?php View::partial('about/leadership-card', ['member' => $m, 'index' => $i, 'delay' => $i * 55]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($testimonials)): ?>
<section class="hq-about-pro-quotes">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow">Client voices</p>
      <h2 class="hq-about-pro-title">Trusted by owners &amp; developers</h2>
    </header>
    <div class="hq-about-pro-quotes__grid">
      <?php foreach ($testimonials as $i => $t): ?>
      <?php $meta = $clientMeta($t); ?>
      <article class="hq-about-pro-quote" data-anim="up" data-delay="<?= $i * 55 ?>">
        <div class="hq-about-pro-quote__mark" aria-hidden="true"><i class="bi bi-quote"></i></div>
        <div class="hq-about-pro-quote__stars"><?= stars((int)($t['rating'] ?? 5)) ?></div>
        <blockquote>&ldquo;<?= e($t['content']) ?>&rdquo;</blockquote>
        <footer>
          <?php if (!empty($t['image'])): ?>
          <img src="<?= e(uploadUrl($t['image'])) ?>" alt="<?= e($t['client_name']) ?>" loading="lazy" width="48" height="48">
          <?php else: ?>
          <span class="hq-about-pro-quote__avatar"><?= mb_strtoupper(mb_substr($t['client_name'], 0, 1)) ?></span>
          <?php endif; ?>
          <div>
            <cite><?= e($t['client_name']) ?></cite>
            <?php if ($meta !== ''): ?><span><?= e($meta) ?></span><?php endif; ?>
          </div>
        </footer>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="hq-about-pro-cta" aria-labelledby="about-cta-title">
  <div class="hq-about-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-about-pro-cta__box" data-anim="up">
    <div class="hq-about-pro-cta__copy">
      <p class="hq-about-pro-eyebrow hq-about-pro-eyebrow--light">Start your project</p>
      <h2 id="about-cta-title" class="hq-about-pro-cta__title">Ready to build with <?= e($siteName) ?>?</h2>
      <p class="hq-about-pro-cta__lead">Share your vision — we'll guide you from planning to handover with clarity, quality, and care.</p>
    </div>
    <div class="hq-about-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> Get a quote</a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">Contact us</a>
    </div>
  </div>
</section>
