<?php
$bodyPage = 'home';
$siteName = $settings['site_name'] ?? 'HighQ Homes';
$aboutText = $settings['about_text'] ?? '';
$mission   = $settings['mission'] ?? '';
$vision    = $settings['vision'] ?? '';
$phone     = $settings['phone'] ?? '+252 907 734 667';
$email     = $settings['email'] ?? 'info@highqhomes.net';
$address   = $settings['address'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);
$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $phone);
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20a%20construction%20quote';
$aboutImg  = !empty($settings['home_about_image']) ? uploadUrl($settings['home_about_image']) : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80';
$ctaImg    = !empty($settings['home_cta_image']) ? uploadUrl($settings['home_cta_image']) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80';

if (empty($sliders)) {
  $sliders = [[
    'title' => 'We Build Spaces People Trust for Generations',
    'subtitle' => 'Premium Construction & Architecture',
    'description' => 'From blueprint to handover — disciplined planning, transparent timelines, and craftsmanship in every detail.',
    'button_text' => 'Get a Free Quote', 'button_link' => $quoteHref,
    'button_text_2' => 'View Our Work', 'button_link_2' => '/projects',
    'image' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80',
  ]];
}

$homeHref = static fn (string $link): string => str_starts_with($link, 'http') ? $link : url(ltrim($link, '/'));
$homeTarget = static fn (string $link): string => str_starts_with($link, 'http') ? ' target="_blank" rel="noopener"' : '';
$normalizeCta = static function (?string $link) use ($quoteHref): string {
  $link = trim((string)$link);
  return trim(strtolower(parse_url($link, PHP_URL_PATH) ?: $link), '/') === 'contact' ? $quoteHref : ($link ?: $quoteHref);
};

$hero = $sliders[0] ?? [];
$heroImg = !empty($hero['image']) ? (str_starts_with($hero['image'], 'http') ? $hero['image'] : uploadUrl($hero['image'])) : 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80';

$statItems = [
  ['value' => $settings['stat_years'] ?? '10', 'num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '', 'label' => 'Years Operating', 'icon' => 'bi-award'],
  ['value' => $settings['stat_projects'] ?? '8', 'num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
  ['value' => $settings['stat_clients'] ?? '3', 'num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
  ['value' => $settings['stat_satisfaction'] ?? '5', 'num' => statNumber((string)($settings['stat_satisfaction'] ?? '5')), 'suffix' => '', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
  ['value' => $settings['stat_awards'] ?? '2016', 'num' => statNumber((string)($settings['stat_awards'] ?? '2016')), 'suffix' => '', 'label' => 'Established', 'icon' => 'bi-calendar2-check'],
];

$whyItems = [
  ['icon' => 'bi-shield-check', 'title' => 'Quality Assured', 'text' => 'Structured inspections at every phase — materials, workmanship, and finish.'],
  ['icon' => 'bi-calendar2-check', 'title' => 'Clear Timelines', 'text' => 'Milestones, budgets, and progress communicated with full transparency.'],
  ['icon' => 'bi-gem', 'title' => 'Premium Finishes', 'text' => 'Durable materials and refined details built to last for decades.'],
  ['icon' => 'bi-clipboard2-check', 'title' => 'Documented Process', 'text' => 'Every stage organized with clear approvals and practical documentation.'],
  ['icon' => 'bi-buildings', 'title' => 'Integrated Delivery', 'text' => 'Architecture, construction, and finishing under one accountable team.'],
  ['icon' => 'bi-headset', 'title' => 'Client Support', 'text' => 'Responsive guidance before, during, and after project handover.'],
];

$steps = [
  ['num' => '01', 'title' => 'Discovery', 'text' => 'We understand your goals, site, budget, and finish expectations.'],
  ['num' => '02', 'title' => 'Design & Plan', 'text' => 'Drawings, materials, cost planning, and a clear project schedule.'],
  ['num' => '03', 'title' => 'Build & Control', 'text' => 'Site execution with structured quality checks and progress updates.'],
  ['num' => '04', 'title' => 'Handover', 'text' => 'Final review, documentation, and delivery of a ready-to-use space.'],
];

$capabilities = [
  [
    'title' => 'Residential Builds',
    'text' => 'Comfortable, durable homes designed for families and long-term living.',
    'icon' => 'bi-house-heart',
    'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
    'href' => url('projects'),
    'mod' => 'feature',
  ],
  [
    'title' => 'Commercial Spaces',
    'text' => 'Offices and business environments built for performance and presence.',
    'icon' => 'bi-building',
    'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=900&q=80',
    'href' => url('projects'),
    'mod' => '',
  ],
  [
    'title' => 'Premium Finishing',
    'text' => 'Refined interiors, paints, and detail work that elevate every space.',
    'icon' => 'bi-brush',
    'img' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=900&q=80',
    'href' => url('paints'),
    'mod' => '',
  ],
  [
    'title' => 'Design & Planning',
    'text' => 'Concept-to-blueprint support with clear scope, budget, and timelines.',
    'icon' => 'bi-rulers',
    'img' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1200&q=80',
    'href' => url('contact'),
    'mod' => 'wide',
  ],
];

$promiseItems = [
  ['icon' => 'bi-file-earmark-check', 'title' => 'Transparent Quotes', 'text' => 'Clear scope and pricing before any work begins.'],
  ['icon' => 'bi-camera-reels', 'title' => 'Progress Visibility', 'text' => 'Regular updates so you always know project status.'],
  ['icon' => 'bi-shield-lock', 'title' => 'Quality Control', 'text' => 'Structured inspections at every critical build phase.'],
];

$trustBarItems = [
  'Licensed & Insured', 'On-Time Delivery', 'Premium Finishes', 'Structured Quality Checks', 'Transparent Pricing', 'Dedicated Client Support',
];

$aboutHighlights = [
  ['icon' => 'bi-award', 'title' => 'Proven Track Record', 'text' => 'Years of successful residential and commercial delivery across Somalia.'],
  ['icon' => 'bi-people', 'title' => 'One Accountable Team', 'text' => 'Design, construction, and finishing coordinated under one roof.'],
  ['icon' => 'bi-graph-up-arrow', 'title' => 'Value-Driven Builds', 'text' => 'Durable materials and smart planning that protect your investment.'],
  ['icon' => 'bi-hand-thumbs-up', 'title' => 'Client-First Approach', 'text' => 'Clear communication from first meeting through final handover.'],
];

$excellencePillars = [
  ['icon' => 'bi-bricks', 'title' => 'Quality Materials', 'text' => 'We specify proven materials selected for strength, finish, and long-term performance in local conditions.'],
  ['icon' => 'bi-hard-hat', 'title' => 'Site Safety', 'text' => 'Disciplined site practices, protective standards, and organized workflows on every active project.'],
  ['icon' => 'bi-clipboard-data', 'title' => 'Documented Delivery', 'text' => 'Milestone reports, approvals, and handover documentation you can reference with confidence.'],
  ['icon' => 'bi-house-check', 'title' => 'After-Handover Care', 'text' => 'Responsive support after completion so your space continues to perform as intended.'],
];

$faqItems = [
  ['q' => 'How do I get a project quote?', 'a' => 'Share your site details, scope, and timeline via WhatsApp, phone, or our contact form. We respond with a structured consultation and transparent proposal.', 'icon' => 'bi-calculator'],
  ['q' => 'What types of projects do you handle?', 'a' => 'HighQ Homes delivers residential homes, commercial spaces, renovations, premium finishing, and design-to-build planning for clients across Somalia.', 'icon' => 'bi-buildings'],
  ['q' => 'How long does a typical build take?', 'a' => 'Timelines depend on scope, materials, and site conditions. After discovery, we provide a milestone schedule with clear dates and progress checkpoints.', 'icon' => 'bi-calendar2-week'],
  ['q' => 'Can I track progress during construction?', 'a' => 'Yes. We provide regular site updates and milestone reviews so you stay informed at every major phase of the project.', 'icon' => 'bi-camera-reels'],
  ['q' => 'Do you manage finishing and interior details?', 'a' => 'Absolutely. From structural work to paints and refined interior finishes, we offer integrated delivery for a complete, move-in-ready result.', 'icon' => 'bi-brush'],
];

$cms = $sections ?? [];
if (!empty($cms['hero_trust']['data']) && is_array($cms['hero_trust']['data'])) {
  $trustBarItems = $cms['hero_trust']['data'];
}
if (!empty($cms['why_us']['data']) && is_array($cms['why_us']['data'])) {
  $whyItems = $cms['why_us']['data'];
}
if (!empty($cms['process']['data']) && is_array($cms['process']['data'])) {
  $steps = $cms['process']['data'];
}
if (!empty($cms['connect']['data']) && is_array($cms['connect']['data'])) {
  $promiseItems = $cms['connect']['data'];
}
if (!empty($cms['excellence']['data']) && is_array($cms['excellence']['data'])) {
  $excellencePillars = $cms['excellence']['data'];
}
if (!empty($cms['faq']['data']) && is_array($cms['faq']['data'])) {
  $faqItems = $cms['faq']['data'];
}

$showProjects = ($settings['home_projects_enabled'] ?? '1') === '1';
$showAbout    = ($settings['home_about_enabled'] ?? '1') === '1';
$showTestimonials = ($settings['home_testimonials_enabled'] ?? '1') === '1' && !empty($testimonials);
$showCta = ($settings['home_cta_enabled'] ?? '1') === '1';
?>

<!-- HERO -->
<?php
$heroParseTitle = static function (string $raw): array {
  $words = preg_split('/\s+/', trim($raw)) ?: [$raw];
  if (count($words) >= 3) {
    $accent = implode(' ', array_splice($words, -2));
    return ['main' => implode(' ', $words), 'accent' => $accent];
  }
  if (count($words) === 2) {
    return ['main' => $words[0], 'accent' => $words[1]];
  }
  return ['main' => $raw, 'accent' => ''];
};

$heroLocation = trim((string)($settings['address'] ?? ''));
$defaultHeroTag = $heroLocation !== '' ? $heroLocation : 'Somalia · Premium Build';

$heroCarouselOpts = [
  'autoplay'    => ($settings['hero_carousel_autoplay'] ?? '1') === '1',
  'interval'    => max(3, min(15, (int)($settings['hero_carousel_interval'] ?? 6))),
  'dots'        => ($settings['hero_carousel_dots'] ?? '1') === '1',
  'pause_hover' => ($settings['hero_carousel_pause_hover'] ?? '1') === '1',
];
$heroMulti = count($sliders) > 1;
$imageFocusMap = ['center' => 'center', 'top' => 'center top', 'bottom' => 'center bottom'];

$heroMetrics = [
  ['num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '', 'label' => 'Projects', 'icon' => 'bi-buildings'],
  ['num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '', 'label' => 'Completed', 'icon' => 'bi-check2-circle'],
  ['num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '', 'label' => 'Years', 'icon' => 'bi-award'],
];

$heroBandStats = [
  ['num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '', 'label' => 'Profiled Projects'],
  ['num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '', 'label' => 'Completed Projects'],
  ['num' => statNumber((string)($settings['stat_satisfaction'] ?? '5')), 'suffix' => '', 'label' => 'Current Projects'],
  ['num' => statNumber((string)($settings['stat_awards'] ?? '2016')), 'suffix' => '', 'label' => 'Established'],
];
?>
<section
  class="hq-hero hq-hero--showcase<?= $heroMulti ? ' hq-hero--carousel' : '' ?>"
  id="hero"
  data-hero-carousel="<?= $heroMulti ? '1' : '0' ?>"
  data-autoplay="<?= $heroCarouselOpts['autoplay'] ? '1' : '0' ?>"
  data-interval="<?= (int)$heroCarouselOpts['interval'] * 1000 ?>"
  data-dots="<?= $heroCarouselOpts['dots'] ? '1' : '0' ?>"
  data-pause-hover="<?= $heroCarouselOpts['pause_hover'] ? '1' : '0' ?>"
>
  <div class="hq-hero__backdrop" aria-hidden="true">
    <div class="hq-hero__orb hq-hero__orb--a"></div>
    <div class="hq-hero__orb hq-hero__orb--b"></div>
    <div class="hq-hero__mesh"></div>
  </div>

  <div class="container-site hq-hero__wrap">
    <div class="hq-hero__layout">
      <div class="hq-hero__copy" data-anim="up">
        <div class="hq-hero__panes hq-hero__panes--copy">
          <?php foreach ($sliders as $i => $slide): ?>
          <?php
            $parts = $heroParseTitle($slide['title'] ?? $siteName);
            $style = $slide['content_style'] ?? 'standard';
            $showDesc = ($slide['show_description'] ?? 1) && !empty($slide['description']);
            $paneClass = 'hq-hero__pane' . ($style !== 'standard' ? ' hq-hero__pane--' . e($style) : '');
          ?>
          <div class="<?= $paneClass ?><?= $i === 0 ? ' is-active' : '' ?>" data-hero-pane aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
            <div class="hq-hero__eyebrow">
              <span class="hq-hero__eyebrow-line"></span>
              <span><?= e($slide['subtitle'] ?? 'Premium Construction & Architecture') ?></span>
            </div>
            <h1 class="hq-hero__title">
              <?php if ($parts['main'] !== ''): ?><span class="hq-hero__title-main"><?= e($parts['main']) ?></span><?php endif; ?>
              <?php if ($parts['accent'] !== ''): ?><span class="hq-hero__title-accent"><?= e($parts['accent']) ?></span><?php endif; ?>
            </h1>
            <?php if ($showDesc): ?>
            <p class="hq-hero__desc"><?= e(truncate($slide['description'], 130)) ?></p>
            <?php endif; ?>
            <div class="hq-hero__actions">
              <a href="<?= e($homeHref($normalizeCta($slide['button_link'] ?? $quoteHref))) ?>"<?= $homeTarget($slide['button_link'] ?? $quoteHref) ?> class="hq-btn hq-btn--orange hq-btn--lg hq-hero__btn-primary">
                <span><i class="bi bi-chat-dots-fill"></i> <?= e($slide['button_text'] ?? 'Get a Free Quote') ?></span>
              </a>
              <a href="<?= e($homeHref($normalizeCta($slide['button_link_2'] ?? '/projects'))) ?>" class="hq-btn hq-btn--outline hq-btn--lg">
                <?= e($slide['button_text_2'] ?? 'View Our Work') ?> <i class="bi bi-arrow-up-right"></i>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="hq-hero__trust">
          <?php foreach (array_slice($trustBarItems, 0, 3) as $trustLabel): ?>
          <div class="hq-hero__trust-item"><i class="bi bi-check2-circle"></i><span><?= e(is_array($trustLabel) ? ($trustLabel['label'] ?? $trustLabel['title'] ?? '') : $trustLabel) ?></span></div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="hq-hero__showcase" data-anim="right" data-delay="100">
        <div class="hq-hero__ring" aria-hidden="true"></div>
        <div class="hq-hero__panes hq-hero__panes--media">
          <?php foreach ($sliders as $i => $slide): ?>
          <?php
            $sImg = !empty($slide['image']) ? (str_starts_with($slide['image'], 'http') ? $slide['image'] : uploadUrl($slide['image'])) : 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80';
            $focus = $imageFocusMap[$slide['image_focus'] ?? 'center'] ?? 'center';
            $badge = trim((string)($slide['badge_text'] ?? '')) ?: $defaultHeroTag;
          ?>
          <figure class="hq-hero__photo hq-hero__pane<?= $i === 0 ? ' is-active' : '' ?>" data-hero-pane aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
            <img src="<?= e($sImg) ?>" alt="<?= e($slide['title'] ?? $siteName) ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" fetchpriority="<?= $i === 0 ? 'high' : 'auto' ?>" style="object-position:<?= e($focus) ?>">
            <figcaption class="hq-hero__photo-cap">
              <span class="hq-hero__photo-dot"></span>
              <span><?= e($badge) ?></span>
            </figcaption>
          </figure>
          <?php endforeach; ?>
        </div>

        <div class="hq-hero__metrics">
          <?php foreach ($heroMetrics as $i => $m): ?>
          <article class="hq-hero__metric" style="--metric-i: <?= $i ?>">
            <span class="hq-hero__metric-icon"><i class="bi <?= e($m['icon']) ?>"></i></span>
            <div>
              <strong data-counter data-target="<?= e($m['num']) ?>" data-suffix="<?= e($m['suffix']) ?>"><?= e($m['num']) ?><?= e($m['suffix']) ?></strong>
              <span><?= e($m['label']) ?></span>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <div class="hq-hero__badge-live">
          <i class="bi bi-lightning-charge-fill"></i>
          <div>
            <strong>Trusted Builder</strong>
            <span>Since <?= e($settings['stat_years'] ?? '4') ?>+ years</span>
          </div>
        </div>
      </div>
    </div>

    <?php if ($heroMulti): ?>
    <div class="hq-hero__controls" aria-label="Hero carousel controls">
      <div class="hq-hero__counter" aria-live="polite">
        <span class="hq-hero__counter-current" data-hero-current>01</span>
        <span class="hq-hero__counter-sep">/</span>
        <span class="hq-hero__counter-total"><?= str_pad((string)count($sliders), 2, '0', STR_PAD_LEFT) ?></span>
      </div>
      <?php if ($heroCarouselOpts['dots']): ?>
      <div class="hq-hero__dots" role="tablist" aria-label="Hero slides">
        <?php foreach ($sliders as $i => $slide): ?>
        <button type="button" class="hq-hero__dot<?= $i === 0 ? ' is-active' : '' ?>" data-hero-dot="<?= $i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i + 1 ?>: <?= e($slide['title']) ?>">
          <span class="hq-hero__dot-fill" data-hero-dot-fill></span>
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="hq-hero__nav">
        <button type="button" class="hq-hero__nav-btn" data-hero-prev aria-label="Previous slide"><i class="bi bi-arrow-left"></i></button>
        <button type="button" class="hq-hero__nav-btn" data-hero-next aria-label="Next slide"><i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
    <?php endif; ?>

    <div class="hq-hero__band" data-anim="up" data-delay="180">
      <?php foreach ($heroBandStats as $i => $bs): ?>
      <div class="hq-hero__band-item">
        <strong data-counter data-target="<?= e($bs['num']) ?>" data-suffix="<?= e($bs['suffix']) ?>"><?= e($bs['num']) ?><?= e($bs['suffix']) ?></strong>
        <span><?= e($bs['label']) ?></span>
      </div>
      <?php if ($i < count($heroBandStats) - 1): ?><span class="hq-hero__band-div" aria-hidden="true"></span><?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="hq-hero__slant" aria-hidden="true"></div>
</section>

<!-- TRUST BAR -->
<div class="hq-trustbar" aria-label="Company credentials">
  <div class="hq-trustbar__track">
    <?php for ($t = 0; $t < 2; $t++): foreach ($trustBarItems as $item): ?>
    <span class="hq-trustbar__item"><i class="bi bi-check-circle-fill"></i> <?= e($item) ?></span>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- ABOUT -->
<?php if ($showAbout): ?>
<section class="hq-section hq-about-premium" id="about">
  <div class="container-site">
    <div class="hq-about-premium__shell">
      <div class="hq-about-premium__media" data-anim="left">
        <div class="hq-about-premium__frame">
          <img src="<?= e($aboutImg) ?>" alt="<?= e($siteName) ?> team at work" loading="lazy">
          <div class="hq-about-premium__badge">
            <strong><?= e($settings['stat_years'] ?? '4') ?>+</strong>
            <span>Years Building<br>With Excellence</span>
          </div>
        </div>
        <div class="hq-about-premium__chip"><i class="bi bi-shield-check"></i> Trusted Construction Partner</div>
      </div>

      <div class="hq-about-premium__content" data-anim="right">
        <p class="hq-eyebrow hq-about-premium__eyebrow">Who We Are</p>
        <h2 class="hq-title hq-about-premium__title">Built on Integrity.<br>Delivered with Precision.</h2>
        <p class="hq-lead hq-about-premium__lead"><?= e($aboutText ?: 'HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces — with disciplined planning and craftsmanship you can see in every detail.') ?></p>

        <div class="hq-about-premium__highlights">
          <?php foreach ($aboutHighlights as $i => $h): ?>
          <article class="hq-about-premium__highlight" data-anim="up" data-delay="<?= $i * 50 ?>">
            <span class="hq-about-premium__highlight-icon"><i class="bi <?= e($h['icon']) ?>"></i></span>
            <div>
              <h3><?= e($h['title']) ?></h3>
              <p><?= e($h['text']) ?></p>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <?php if ($mission || $vision): ?>
        <div class="hq-about-premium__mv">
          <?php if ($mission): ?><div class="hq-about-premium__mv-item"><strong>Mission</strong><p><?= e(truncate($mission, 120)) ?></p></div><?php endif; ?>
          <?php if ($vision): ?><div class="hq-about-premium__mv-item"><strong>Vision</strong><p><?= e(truncate($vision, 120)) ?></p></div><?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="hq-about-premium__actions">
          <a href="<?= url('about') ?>" class="hq-btn hq-btn--navy">Our Story <i class="bi bi-arrow-right"></i></a>
          <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange"><i class="bi bi-whatsapp"></i> Start a Project</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CAPABILITIES -->
<section class="hq-section hq-section--soft" id="capabilities">
  <div class="container-site">
    <?php View::partial('home/section-head', [
      'kicker' => 'What We Build',
      'title' => 'Spaces Crafted With Purpose',
      'desc' => 'From family homes to commercial landmarks — every project is planned, built, and finished to premium standards.',
      'ctaHref' => url('projects'),
      'ctaLabel' => 'Explore Portfolio',
      'id' => 'hp-capabilities-title',
    ]); ?>
    <div class="hq-bento">
      <?php foreach ($capabilities as $i => $cap): ?>
      <a href="<?= e($cap['href']) ?>" class="hq-bento__card<?= $cap['mod'] !== '' ? ' hq-bento__card--' . e($cap['mod']) : '' ?>" data-anim="up" data-delay="<?= $i * 60 ?>">
        <img src="<?= e($cap['img']) ?>" alt="" loading="lazy" aria-hidden="true">
        <div class="hq-bento__shade"></div>
        <div class="hq-bento__body">
          <span class="hq-bento__icon"><i class="bi <?= e($cap['icon']) ?>"></i></span>
          <h3><?= e($cap['title']) ?></h3>
          <p><?= e($cap['text']) ?></p>
          <span class="hq-bento__link">Learn more <i class="bi bi-arrow-up-right"></i></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PORTFOLIO -->
<?php if ($showProjects && (!empty($featuredProjects) || !empty($latestProjects))): ?>
<?php
$portfolioFeatured = array_values(array_slice($featuredProjects, 0, 5));
$portfolioHero  = $portfolioFeatured[0] ?? null;
$portfolioSide  = array_values(array_slice($portfolioFeatured, 1, 2));
$portfolioTiles = array_values(array_slice($portfolioFeatured, 3, 2));
$portfolioRecent = array_values(array_slice($latestProjects, 0, 8));
$portfolioIntroImg = $portfolioHero
  ? projectImageUrl($portfolioHero)
  : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80';
$portfolioIntroHref = $portfolioHero
  ? url('projects/' . $portfolioHero['slug'])
  : url('projects');
$portfolioIntroLabel = $portfolioHero
  ? projectCategoryLabel($portfolioHero['category'] ?? null)
  : 'Featured Build';
?>
<section class="hq-hp-portfolio" id="portfolio" aria-labelledby="hp-portfolio-title">
  <div class="container-site">
    <header class="hq-port-intro" data-anim="up">
      <div class="hq-port-intro__grid">
        <div class="hq-port-intro__copy">
          <p class="hq-port-intro__kicker">Portfolio</p>
          <h2 class="hq-port-intro__title" id="hp-portfolio-title">
            Signature Work That
            <span class="hq-port-intro__accent">Defines Our Standard</span>
          </h2>
          <p class="hq-port-intro__desc">Explore featured builds and recent completions — each project reflects our commitment to quality, clarity, and premium finish.</p>
          <div class="hq-port-intro__actions">
            <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange">Full Portfolio <i class="bi bi-arrow-up-right"></i></a>
            <a href="<?= url('gallery') ?>" class="hq-btn hq-btn--outline">View Gallery</a>
          </div>
        </div>

        <a href="<?= e($portfolioIntroHref) ?>" class="hq-port-intro__photo" data-anim="right" data-delay="80">
          <img src="<?= e($portfolioIntroImg) ?>" alt="HighQ Homes construction project sample" loading="eager">
          <span class="hq-port-intro__photo-label"><?= e($portfolioIntroLabel) ?></span>
        </a>
      </div>
    </header>

    <?php if ($portfolioHero || !empty($portfolioSide) || !empty($portfolioTiles)): ?>
    <div class="hq-hp-portfolio__mosaic">
      <?php if ($portfolioHero): ?>
      <div class="hq-hp-portfolio__hero-col">
        <?php View::partial('home/portfolio-showcase-card', ['project' => $portfolioHero, 'variant' => 'hero', 'index' => 0, 'delay' => 0]); ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($portfolioSide)): ?>
      <div class="hq-hp-portfolio__side-col">
        <?php foreach ($portfolioSide as $i => $proj): ?>
        <?php View::partial('home/portfolio-showcase-card', ['project' => $proj, 'variant' => 'side', 'index' => $i + 1, 'delay' => ($i + 1) * 50]); ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($portfolioTiles)): ?>
      <div class="hq-hp-portfolio__tiles">
        <?php foreach ($portfolioTiles as $i => $proj): ?>
        <?php View::partial('home/portfolio-showcase-card', ['project' => $proj, 'variant' => 'tile', 'index' => $i + 3, 'delay' => ($i + 3) * 45]); ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($portfolioRecent)): ?>
    <div class="hq-hp-portfolio__recent">
      <div class="hq-hp-portfolio__recent-head">
        <div>
          <p class="hq-hp-portfolio__recent-kicker"><i class="bi bi-hammer"></i> Recent Sites</p>
          <h3 class="hq-hp-portfolio__recent-title">Fresh Completions & Active Builds</h3>
        </div>
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--outline">Browse All Projects</a>
      </div>
      <div class="hq-hp-portfolio__track" tabindex="0" role="region" aria-label="Recent projects">
        <?php foreach ($portfolioRecent as $i => $proj): ?>
        <?php View::partial('home/portfolio-showcase-card', ['project' => $proj, 'variant' => 'strip', 'index' => $i, 'delay' => $i * 40]); ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="hq-hp-portfolio__band" data-anim="up">
      <div class="hq-hp-portfolio__band-copy">
        <strong>Ready to start your next build?</strong>
        <span>Share your vision and receive a structured consultation from our team.</span>
      </div>
      <div class="hq-hp-portfolio__band-actions">
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange"><i class="bi bi-whatsapp"></i> Get a Quote</a>
        <a href="<?= url('gallery') ?>" class="hq-btn hq-btn--outline">View Gallery</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- WHY CHOOSE US -->
<section class="hq-section hq-section--soft hq-why-section">
  <div class="container-site">
    <?php View::partial('home/section-head', ['kicker' => 'Why Choose Us', 'title' => 'The HighQ Homes Difference', 'desc' => 'Six principles that guide every project — from first consultation to final handover.']); ?>
    <div class="hq-why__grid hq-why__grid--premium">
      <?php foreach ($whyItems as $i => $item): ?>
      <article class="hq-card hq-why hq-why--premium" data-anim="up" data-delay="<?= $i * 50 ?>">
        <span class="hq-why__index"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
        <span class="hq-why__icon"><i class="bi <?= e($item['icon']) ?>"></i></span>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PROCESS -->
<section class="hq-section hq-process-section">
  <div class="container-site">
    <?php View::partial('home/section-head', ['kicker' => 'Our Process', 'title' => 'From Vision to Handover', 'desc' => 'A structured, transparent path designed to reduce risk and deliver exceptional results.']); ?>
    <div class="hq-process__timeline">
      <?php foreach ($steps as $i => $step): ?>
      <article class="hq-step hq-step--timeline" data-anim="up" data-delay="<?= $i * 60 ?>">
        <span class="hq-step__num"><?= e($step['num']) ?></span>
        <div class="hq-step__body">
          <h3><?= e($step['title']) ?></h3>
          <p><?= e($step['text']) ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- EXCELLENCE -->
<section class="hq-section hq-section--soft hq-excellence" id="excellence">
  <div class="container-site">
    <div class="hq-excellence__shell">
      <div class="hq-excellence__intro" data-anim="left">
        <p class="hq-eyebrow">Built to Last</p>
        <h2 class="hq-title">Construction Excellence You Can Measure</h2>
        <p class="hq-lead">Every HighQ Homes project is managed with the same standard — rigorous planning, accountable execution, and finishes that stand up to daily use and time.</p>
        <ul class="hq-excellence__list">
          <li><i class="bi bi-check2"></i> End-to-end project accountability</li>
          <li><i class="bi bi-check2"></i> Premium materials & skilled trades</li>
          <li><i class="bi bi-check2"></i> Milestone-based progress reporting</li>
          <li><i class="bi bi-check2"></i> Clean, organized, professional sites</li>
        </ul>
        <a href="<?= url('gallery') ?>" class="hq-btn hq-btn--outline">View Gallery <i class="bi bi-images"></i></a>
      </div>
      <div class="hq-excellence__grid" data-anim="right">
        <?php foreach ($excellencePillars as $i => $pillar): ?>
        <article class="hq-excellence__card" data-anim="up" data-delay="<?= $i * 55 ?>">
          <span class="hq-excellence__icon"><i class="bi <?= e($pillar['icon']) ?>"></i></span>
          <h3><?= e($pillar['title']) ?></h3>
          <p><?= e($pillar['text']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- CONNECT -->
<section class="hq-section hq-connect" id="connect">
  <div class="container-site">
    <div class="hq-connect__shell">
      <div class="hq-connect__copy" data-anim="left">
        <p class="hq-eyebrow">Start With Confidence</p>
        <h2 class="hq-title">Your Project Deserves a Builder You Can Trust</h2>
        <p class="hq-lead">HighQ Homes combines disciplined project management, skilled craftsmanship, and transparent communication — so you stay informed from the first conversation to final handover.</p>
        <ul class="hq-connect__promises">
          <?php foreach ($promiseItems as $item): ?>
          <li>
            <span class="hq-connect__promise-icon"><i class="bi <?= e($item['icon']) ?>"></i></span>
            <div>
              <strong><?= e($item['title']) ?></strong>
              <span><?= e($item['text']) ?></span>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="<?= url('about') ?>" class="hq-btn hq-btn--outline">Learn About Us <i class="bi bi-arrow-right"></i></a>
      </div>

      <div class="hq-connect__cards" data-anim="right">
        <a href="tel:<?= e($phoneHref) ?>" class="hq-connect__card">
          <span class="hq-connect__card-icon"><i class="bi bi-telephone-fill"></i></span>
          <div>
            <strong>Call Us</strong>
            <span><?= e($phone) ?></span>
          </div>
          <i class="bi bi-arrow-up-right hq-connect__card-arrow"></i>
        </a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-connect__card hq-connect__card--accent">
          <span class="hq-connect__card-icon"><i class="bi bi-whatsapp"></i></span>
          <div>
            <strong>WhatsApp Quote</strong>
            <span>Fast response · Free consultation</span>
          </div>
          <i class="bi bi-arrow-up-right hq-connect__card-arrow"></i>
        </a>
        <a href="mailto:<?= e($email) ?>" class="hq-connect__card">
          <span class="hq-connect__card-icon"><i class="bi bi-envelope-fill"></i></span>
          <div>
            <strong>Email Us</strong>
            <span><?= e($email) ?></span>
          </div>
          <i class="bi bi-arrow-up-right hq-connect__card-arrow"></i>
        </a>
        <?php if ($address !== ''): ?>
        <div class="hq-connect__card hq-connect__card--static">
          <span class="hq-connect__card-icon"><i class="bi bi-geo-alt-fill"></i></span>
          <div>
            <strong>Visit Us</strong>
            <span><?= e($address) ?></span>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="hq-section hq-section--navy">
  <div class="container-site">
    <?php View::partial('home/section-head', ['kicker' => 'Achievements', 'title' => 'Proof of Excellence', 'desc' => 'Real outcomes from real projects.', 'light' => true, 'id' => 'hp-stats-title']); ?>
    <div class="hq-stats__grid">
      <?php foreach ($statItems as $i => $stat): ?>
      <article class="hq-stat" data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-stat__icon"><i class="bi <?= e($stat['icon']) ?>"></i></span>
        <strong data-counter data-target="<?= e($stat['num']) ?>" data-suffix="<?= e($stat['suffix']) ?>"><?= e(rtrim((string)$stat['value'], '+')) ?><?= e($stat['suffix']) ?></strong>
        <span><?= e($stat['label']) ?></span>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<?php if ($showTestimonials): ?>
<?php
  $reviewCount = count($testimonials);
  $avgRating = $reviewCount
    ? round(array_sum(array_map(static fn($t) => (int)($t['rating'] ?? 5), $testimonials)) / $reviewCount, 1)
    : 5.0;
  $clientMeta = static function (array $t): string {
    return trim(($t['position'] ?? '') . (!empty($t['company']) ? ', ' . $t['company'] : ''));
  };
?>
<section class="hq-testimonials-premium" id="testimonials">
  <div class="hq-testimonials-premium__bg" aria-hidden="true"></div>
  <div class="container-site">
    <div class="hq-testimonials-premium__shell">
      <aside class="hq-testimonials-premium__aside" data-anim="left">
        <p class="hq-testimonials-premium__kicker">Testimonials</p>
        <h2 class="hq-testimonials-premium__title">What Our Clients Say</h2>
        <p class="hq-testimonials-premium__lead">Trusted by owners, developers, and community partners across every project type.</p>
        <ul class="hq-testimonials-premium__chips">
          <li><i class="bi bi-star-fill"></i> Client-rated excellence</li>
          <li><i class="bi bi-shield-check"></i> Verified project delivery</li>
          <li><i class="bi bi-buildings"></i> Residential &amp; commercial</li>
        </ul>
        <div class="hq-testimonials-premium__score">
          <div class="hq-testimonials-premium__score-main">
            <strong><?= number_format($avgRating, 1) ?></strong>
            <span class="hq-testimonials-premium__stars"><?= stars((int)round($avgRating)) ?></span>
          </div>
          <p>Based on <?= $reviewCount === 1 ? '1 client review' : number_format($reviewCount) . ' client reviews' ?></p>
        </div>
      </aside>

      <div class="hq-testimonials-premium__stage" data-anim="right">
        <div class="hq-testimonials-premium__panel">
          <div class="hq-testimonials-premium__carousel" data-testimonials-carousel>
            <?php foreach ($testimonials as $i => $t): ?>
            <?php $meta = $clientMeta($t); ?>
            <article class="hq-testimonial-card<?= $i === 0 ? ' is-active' : '' ?>" data-testimonial-slide aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
              <div class="hq-testimonial-card__quote-mark" aria-hidden="true"><i class="bi bi-quote"></i></div>
              <div class="hq-testimonial-card__rating"><?= stars((int)($t['rating'] ?? 5)) ?></div>
              <blockquote class="hq-testimonial-card__text">&ldquo;<?= e($t['content']) ?>&rdquo;</blockquote>
              <footer class="hq-testimonial-card__author">
                <?php if (!empty($t['image'])): ?>
                <img src="<?= e(uploadUrl($t['image'])) ?>" alt="<?= e($t['client_name']) ?>" loading="lazy" width="56" height="56">
                <?php else: ?>
                <span class="hq-testimonial-card__avatar"><?= mb_strtoupper(mb_substr($t['client_name'], 0, 1)) ?></span>
                <?php endif; ?>
                <div>
                  <cite><?= e($t['client_name']) ?></cite>
                  <?php if ($meta !== ''): ?><span><?= e($meta) ?></span><?php endif; ?>
                </div>
                <span class="hq-testimonial-card__verified"><i class="bi bi-patch-check-fill"></i> Verified client</span>
              </footer>
            </article>
            <?php endforeach; ?>
          </div>

          <?php if ($reviewCount > 1): ?>
          <div class="hq-testimonials-premium__controls">
            <button type="button" class="hq-testimonials-premium__arrow" data-testimonial-prev aria-label="Previous testimonial">
              <i class="bi bi-arrow-left"></i>
            </button>
            <div class="hq-testimonials-premium__dots" role="tablist" aria-label="Testimonials">
              <?php foreach ($testimonials as $i => $t): ?>
              <button
                type="button"
                class="hq-testimonials-premium__dot<?= $i === 0 ? ' is-active' : '' ?>"
                data-testimonial-dot
                aria-label="Show review from <?= e($t['client_name']) ?>"
                aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
              ></button>
              <?php endforeach; ?>
            </div>
            <button type="button" class="hq-testimonials-premium__arrow" data-testimonial-next aria-label="Next testimonial">
              <i class="bi bi-arrow-right"></i>
            </button>
          </div>
          <?php endif; ?>
        </div>

        <?php if ($reviewCount > 1): ?>
        <div class="hq-testimonials-premium__thumbs">
          <?php foreach ($testimonials as $i => $t): ?>
          <?php $meta = $clientMeta($t); ?>
          <button
            type="button"
            class="hq-testimonial-thumb<?= $i === 0 ? ' is-active' : '' ?>"
            data-testimonial-thumb
            aria-label="Read review from <?= e($t['client_name']) ?>"
          >
            <?php if (!empty($t['image'])): ?>
            <img src="<?= e(uploadUrl($t['image'])) ?>" alt="" loading="lazy" width="40" height="40">
            <?php else: ?>
            <span class="hq-testimonial-thumb__avatar"><?= mb_strtoupper(mb_substr($t['client_name'], 0, 1)) ?></span>
            <?php endif; ?>
            <span class="hq-testimonial-thumb__copy">
              <strong><?= e($t['client_name']) ?></strong>
              <span><?= e($meta !== '' ? $meta : truncate($t['content'], 42)) ?></span>
            </span>
          </button>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQ -->
<section class="hq-section hq-faq-premium" id="faq">
  <div class="hq-faq-premium__bg" aria-hidden="true"></div>
  <div class="container-site">
    <div class="hq-faq-premium__shell">
      <aside class="hq-faq-premium__aside" data-anim="left">
        <p class="hq-faq-premium__kicker"><?= e($cms['faq']['title'] ?? 'Questions & Answers') ?></p>
        <h2 class="hq-faq-premium__title"><?= e($cms['faq']['subtitle'] ?? 'Everything You Need to Know') ?> <em>Before You Build</em></h2>
        <p class="hq-faq-premium__lead"><?= e($cms['faq']['content'] ?? 'Clear answers to the questions clients ask most — so you can plan your project with confidence.') ?></p>
        <ul class="hq-faq-premium__chips">
          <li><i class="bi bi-lightning-charge"></i> Quick answers</li>
          <li><i class="bi bi-shield-check"></i> Expert guidance</li>
          <li><i class="bi bi-chat-square-text"></i> Free consultation</li>
        </ul>
        <div class="hq-faq-premium__ask" id="ask-our-team">
          <div class="hq-faq-premium__ask-glow" aria-hidden="true"></div>
          <div class="hq-faq-premium__ask-head">
            <span class="hq-faq-premium__ask-icon"><i class="bi bi-headset"></i></span>
            <div>
              <strong>Ask Our Team</strong>
              <span>Still have questions? Talk to us directly.</span>
            </div>
          </div>
          <div class="hq-faq-premium__ask-actions">
            <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--block">
              WhatsApp Us <i class="bi bi-whatsapp"></i>
            </a>
            <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--block">
              Contact Form <i class="bi bi-envelope"></i>
            </a>
          </div>
          <a href="tel:<?= e($phoneHref) ?>" class="hq-faq-premium__ask-phone">
            <i class="bi bi-telephone-fill"></i> <?= e($phone) ?>
          </a>
        </div>
      </aside>
      <div class="hq-faq-premium__panel" data-anim="right">
        <div class="hq-faq-premium__panel-head">
          <span class="hq-faq-premium__count"><?= count($faqItems) ?> topics</span>
          <span class="hq-faq-premium__hint">Tap a question to expand</span>
        </div>
        <div class="hq-faq__list">
          <?php foreach ($faqItems as $i => $faq): ?>
          <article class="hq-faq__item<?= $i === 0 ? ' is-open' : '' ?>">
            <button type="button" class="hq-faq__toggle" data-faq-toggle aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
              <span class="hq-faq__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="hq-faq__icon"><i class="bi <?= e($faq['icon']) ?>"></i></span>
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
  </div>
</section>

<!-- FINAL CTA -->
<?php if ($showCta): ?>
<section class="hq-final" style="background-image:url('<?= e($ctaImg) ?>')">
  <div class="hq-final__overlay"></div>
  <div class="container-site hq-final__inner" data-anim="up">
    <p class="hq-eyebrow hq-eyebrow--light">Ready When You Are</p>
    <h2 class="hq-title hq-title--light"><?= e($settings['home_cta_title'] ?? 'Let\'s Build Something Exceptional') ?></h2>
    <p class="hq-lead hq-lead--light"><?= e($settings['home_cta_text'] ?? 'Share your vision with our team for confident planning from groundbreaking to handover.') ?></p>
    <div class="hq-actions hq-mt-xl">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> WhatsApp Us</a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg">Contact Us</a>
    </div>
    <div class="hq-final__meta">
      <a href="tel:<?= e($phoneHref) ?>"><i class="bi bi-telephone"></i> <?= e($phone) ?></a>
      <a href="mailto:<?= e($email) ?>"><i class="bi bi-envelope"></i> <?= e($email) ?></a>
      <?php if ($address !== ''): ?><span><i class="bi bi-geo-alt"></i> <?= e($address) ?></span><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
