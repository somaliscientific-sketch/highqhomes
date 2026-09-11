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
$aboutImgDefault = asset('images/builds/about-residence.jpg');
$aboutImg  = !empty($settings['home_about_image']) ? uploadUrl($settings['home_about_image']) : $aboutImgDefault;
$ctaImg    = !empty($settings['home_cta_image']) ? uploadUrl($settings['home_cta_image']) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80';

$heroDefaults = array_map(static function (array $slide) use ($quoteHref): array {
  $slide['button_link'] = $quoteHref;
  return $slide;
}, heroSliderCatalog());
if (empty($sliders)) {
  $sliders = $heroDefaults;
}

$homeHref = static fn (string $link): string => str_starts_with($link, 'http') ? $link : url(ltrim($link, '/'));
$homeTarget = static fn (string $link): string => str_starts_with($link, 'http') ? ' target="_blank" rel="noopener"' : '';
$normalizeCta = static function (?string $link) use ($quoteHref): string {
  $link = trim((string)$link);
  return trim(strtolower(parse_url($link, PHP_URL_PATH) ?: $link), '/') === 'contact' ? $quoteHref : ($link ?: $quoteHref);
};

$hero = $sliders[0] ?? [];
$heroImg = heroSlideImageUrl($hero, 0);

$statItems = [
  ['value' => $settings['stat_years'] ?? '10', 'num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '+', 'label' => 'Years Operating', 'icon' => 'bi-award'],
  ['value' => $settings['stat_projects'] ?? '8', 'num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '+', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
  ['value' => $settings['stat_clients'] ?? '3', 'num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '+', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
  ['value' => $settings['stat_satisfaction'] ?? '5', 'num' => statNumber((string)($settings['stat_satisfaction'] ?? '5')), 'suffix' => '+', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
  ['value' => $settings['stat_awards'] ?? '2016', 'num' => statNumber((string)($settings['stat_awards'] ?? '2016')), 'suffix' => '', 'label' => 'Established', 'icon' => 'bi-calendar2-check'],
];

$whyItems = [
  ['icon' => 'bi-house-check', 'title' => 'Visit a finished home', 'text' => 'Completed Garowe houses you can walk before you decide.'],
  ['icon' => 'bi-camera', 'title' => 'Job-site photos', 'text' => 'The villas here were shot on our sites — not a stock library.'],
  ['icon' => 'bi-broadcast', 'title' => 'Progress you can follow', 'text' => 'Updates from the crew on the plot, not a sales desk.'],
  ['icon' => 'bi-key', 'title' => 'After the keys', 'text' => 'We stay reachable after handover, not only until the last invoice.'],
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
    'img' => asset('images/builds/grey-villa-evening.jpg'),
    'href' => url('projects'),
    'mod' => 'feature',
  ],
  [
    'title' => 'Commercial Spaces',
    'text' => 'Offices and business environments built for performance and presence.',
    'icon' => 'bi-building',
    'img' => asset('images/builds/twin-residences.jpg'),
    'href' => url('projects'),
    'mod' => '',
  ],
  [
    'title' => 'Premium Finishing',
    'text' => 'Refined interiors, paints, and detail work that elevate every space.',
    'icon' => 'bi-brush',
    'img' => asset('images/builds/modern-villa.jpg'),
    'href' => url('paints'),
    'mod' => '',
  ],
  [
    'title' => 'Design & Planning',
    'text' => 'Concept-to-blueprint support with clear scope, budget, and timelines.',
    'icon' => 'bi-rulers',
    'img' => asset('images/builds/stone-residence.jpg'),
    'href' => url('services'),
    'mod' => 'wide',
  ],
];
$capabilityDefaults = $capabilities;
$buildGallery = [
  ['src' => asset('images/builds/yellow-residence.jpg'), 'alt' => 'Completed family residence by HighQ Homes'],
  ['src' => asset('images/builds/green-roof-residence.jpg'), 'alt' => 'Two-storey residence with premium exterior finishes'],
  ['src' => asset('images/builds/active-build.jpg'), 'alt' => 'Residential project under construction'],
  ['src' => asset('images/builds/grey-villa.jpg'), 'alt' => 'Modern villa with custom gate and finishing'],
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
  ['icon' => 'bi-geo-alt', 'title' => 'Based in Garowe', 'text' => 'We build for Puntland sites, climate, and family living — with local accountability from first meeting to handover.'],
  ['icon' => 'bi-people', 'title' => 'One Accountable Team', 'text' => 'Design, construction, and finishing stay under one standard so nothing is lost between trades.'],
  ['icon' => 'bi-clipboard-check', 'title' => 'Clear Scope & Timeline', 'text' => 'Transparent quotes, milestone updates, and a finish you can inspect before the keys are handed over.'],
  ['icon' => 'bi-house-heart', 'title' => 'Homes Built to Last', 'text' => 'Durable materials and careful workmanship chosen for long-term performance, not a short-lived look.'],
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
$heroSec          = cmsRow($cms, 'hero');
$heroTrustSec     = cmsRow($cms, 'hero_trust');
$aboutSec         = cmsRow($cms, 'about_highlights');
$capabilitiesSec  = cmsRow($cms, 'capabilities');
$projectsSec      = cmsRow($cms, 'projects');
$whySec           = cmsRow($cms, 'why_us');
$processSec       = cmsRow($cms, 'process');
$excellenceSec    = cmsRow($cms, 'excellence');
$connectSec       = cmsRow($cms, 'connect');
$statsSec         = cmsRow($cms, 'stats');
$testimonialsSec  = cmsRow($cms, 'testimonials');
$faqSec           = cmsRow($cms, 'faq');
$ctaSec           = cmsRow($cms, 'cta');
$projectsMeta     = cmsMap($projectsSec);
$testimonialsMeta = cmsMap($testimonialsSec);
$ctaMeta          = cmsMap($ctaSec);
$excellenceMeta   = cmsMap($excellenceSec);
$faqMeta          = cmsMap($faqSec);
$heroMetricsCms   = [];

$excellenceChecklist = [
  'End-to-end project accountability',
  'Premium materials & skilled trades',
  'Milestone-based progress reporting',
  'Clean, organized, professional sites',
];
if (!empty($excellenceMeta['checklist']) && is_array($excellenceMeta['checklist'])) {
  $excellenceChecklist = $excellenceMeta['checklist'];
}

$faqChips = ['Quick answers', 'Expert guidance', 'Free consultation'];
if (!empty($faqMeta['chips']) && is_array($faqMeta['chips'])) {
  $faqChips = $faqMeta['chips'];
}

$trustList = cmsList($heroTrustSec);
if ($trustList !== []) {
  $trustBarItems = $trustList;
} elseif (!empty($heroTrustSec['data']) && is_array($heroTrustSec['data'])) {
  $trustBarItems = $heroTrustSec['data'];
}

$capList = cmsList($capabilitiesSec);
if ($capList !== []) {
  $capabilities = [];
  foreach (array_values($capList) as $i => $row) {
    if (!is_array($row)) {
      continue;
    }
    if (empty($row['img']) && !empty($row['image_url'])) {
      $row['img'] = cmsMediaUrl((string)$row['image_url']);
    } elseif (!empty($row['img'])) {
      $row['img'] = cmsMediaUrl((string)$row['img'], (string)$row['img']);
    }
    $img = (string)($row['img'] ?? '');
    if ($img === '' || str_contains($img, 'unsplash.com')) {
      $row['img'] = $capabilityDefaults[$i]['img'] ?? $img;
    }
    $capabilities[] = $row;
  }
}

$aboutList = cmsList($aboutSec);
if ($aboutList !== []) {
  $aboutHighlights = $aboutList;
}
$whyList = cmsList($whySec);
if ($whyList !== []) {
  $whyTitles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $whyList);
  $staleWhySets = [
    ['Quality Assured', 'Clear Timelines', 'Premium Finishes', 'Documented Process', 'Integrated Delivery', 'Client Support'],
    ['Exceptional Quality', 'Sustainable Practices', 'Customer Experience', 'Lasting Relationships'],
    ['Based in Garowe', 'One accountable team', 'Written scope & timeline', 'Finish built to last', 'Real homes, not renders', 'Direct communication'],
  ];
  $keepCmsWhy = true;
  foreach ($staleWhySets as $seedWhy) {
    if (array_intersect($seedWhy, $whyTitles) === $seedWhy) {
      $keepCmsWhy = false;
      break;
    }
  }
  if ($keepCmsWhy) {
    $whyItems = $whyList;
  }
}
$processList = cmsList($processSec);
if ($processList !== []) {
  $steps = $processList;
}
$connectList = cmsList($connectSec);
if ($connectList !== []) {
  $promiseItems = $connectList;
}
$excellenceList = cmsList($excellenceSec);
if ($excellenceList !== []) {
  $excellencePillars = $excellenceList;
}
$faqList = cmsList($faqSec);
if ($faqList !== []) {
  $faqItems = $faqList;
}
$statsList = cmsList($statsSec);
if ($statsList !== []) {
  $mappedStats = [];
  foreach ($statsList as $row) {
    if (!is_array($row)) continue;
    $value = (string)($row['value'] ?? $row['num'] ?? '');
    $label = (string)($row['label'] ?? $row['title'] ?? $row['text'] ?? '');
    if ($value === '' && $label === '') continue;
    $mappedStats[] = [
      'value'  => $value !== '' ? $value : $label,
      'num'    => statNumber((string)($row['num'] ?? $value)),
      'suffix' => (string)($row['suffix'] ?? ''),
      'label'  => $label !== '' ? $label : $value,
      'icon'   => (string)($row['icon'] ?? 'bi-award'),
    ];
  }
  if ($mappedStats !== []) {
    $statItems = $mappedStats;
  }
}
$heroList = cmsList($heroSec);
if ($heroList !== []) {
  $mappedHeroMetrics = [];
  foreach ($heroList as $row) {
    if (!is_array($row)) continue;
    $mappedHeroMetrics[] = [
      'num'    => statNumber((string)($row['num'] ?? $row['value'] ?? '0')),
      'suffix' => (string)($row['suffix'] ?? ''),
      'label'  => (string)($row['label'] ?? $row['title'] ?? ''),
      'icon'   => (string)($row['icon'] ?? 'bi-award'),
    ];
  }
  if ($mappedHeroMetrics !== []) {
    $heroMetricsCms = $mappedHeroMetrics;
  }
}

$aboutImg = $aboutImgDefault;
$ctaImg   = cmsMediaUrl($ctaSec['image_url'] ?? '', $ctaImg);

$aboutTitle = cmsText($aboutSec, 'title', 'Who We Are');
$aboutSubtitle = cmsText($aboutSec, 'subtitle', 'A Trusted Builder for Homes That Last.');
$aboutLead = cmsText($aboutSec, 'content', $aboutText ?: 'HighQ Homes plans, builds, and finishes residential and commercial spaces in Garowe and across Puntland. Clients work with one accountable team — clear scope, honest timelines, and craftsmanship you can see in the completed home.');
$oldAboutSubtitles = [
  'Built on Integrity. Delivered with Precision.',
  'Built on integrity. Delivered with precision.',
];
$oldAboutLeads = [
  'HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces.',
  'HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces — with disciplined planning and craftsmanship you can see in every detail.',
];
if (in_array($aboutSubtitle, $oldAboutSubtitles, true)) {
  $aboutSubtitle = 'A Trusted Builder for Homes That Last.';
}
if (in_array($aboutLead, $oldAboutLeads, true) || $aboutLead === '') {
  $aboutLead = 'HighQ Homes plans, builds, and finishes residential and commercial spaces in Garowe and across Puntland. Clients work with one accountable team — clear scope, honest timelines, and craftsmanship you can see in the completed home.';
}
$oldAboutTitles = ['Proven Track Record', 'Value-Driven Builds', 'Client-First Approach'];
$aboutHighlightTitles = array_map(static fn($row) => (string)($row['title'] ?? ''), $aboutHighlights);
if (array_intersect($oldAboutTitles, $aboutHighlightTitles) !== []) {
  $aboutHighlights = [
    ['icon' => 'bi-geo-alt', 'title' => 'Based in Garowe', 'text' => 'We build for Puntland sites, climate, and family living — with local accountability from first meeting to handover.'],
    ['icon' => 'bi-people', 'title' => 'One Accountable Team', 'text' => 'Design, construction, and finishing stay under one standard so nothing is lost between trades.'],
    ['icon' => 'bi-clipboard-check', 'title' => 'Clear Scope & Timeline', 'text' => 'Transparent quotes, milestone updates, and a finish you can inspect before the keys are handed over.'],
    ['icon' => 'bi-house-heart', 'title' => 'Homes Built to Last', 'text' => 'Durable materials and careful workmanship chosen for long-term performance, not a short-lived look.'],
  ];
}

$showHero         = cmsRowEnabled($cms, 'hero', true);
$showHeroTrust    = cmsRowEnabled($cms, 'hero_trust', true);
$showCapabilities = cmsRowEnabled($cms, 'capabilities', true);
$showAbout        = cmsRowEnabled($cms, 'about_highlights', ($settings['home_about_enabled'] ?? '1') === '1');
$showProjects     = cmsRowEnabled($cms, 'projects', ($settings['home_projects_enabled'] ?? '1') === '1');
$showWhyUs        = cmsRowEnabled($cms, 'why_us', true);
$showProcess      = cmsRowEnabled($cms, 'process', true);
$showExcellence   = false;
$showConnect      = false;
$showStats        = false;
$showTestimonials = cmsRowEnabled($cms, 'testimonials', ($settings['home_testimonials_enabled'] ?? '1') === '1') && !empty($testimonials);
$showFaq          = cmsRowEnabled($cms, 'faq', true);
$showCta          = false;
?>

<!-- HERO -->
<?php
$heroParseTitle = static function (string $raw): array {
  $words = preg_split('/\s+/u', trim($raw)) ?: [$raw];
  $words = array_values(array_filter($words, static fn($w) => $w !== ''));
  if (count($words) >= 3) {
    $accentWords = array_splice($words, -2);
    $glue = ['with', 'in', 'for', 'and', 'of', 'to', 'the', 'a', 'at', 'on', 'your'];
    if ($words && in_array(strtolower((string) end($words)), $glue, true)) {
      array_unshift($accentWords, (string) array_pop($words));
    }
    return ['main' => implode(' ', $words), 'accent' => implode(' ', $accentWords)];
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
  'transition'  => in_array(($settings['hero_carousel_transition'] ?? 'kenburns'), ['fade', 'slide', 'kenburns'], true)
    ? ($settings['hero_carousel_transition'] ?? 'kenburns')
    : 'kenburns',
];
$heroMulti = count($sliders) > 1;

$heroMetrics = [
  ['num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '+', 'label' => 'Projects Profiled', 'icon' => 'bi-buildings'],
  ['num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '+', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
  ['num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '+', 'label' => 'Years Experience', 'icon' => 'bi-award'],
];
if (!empty($heroMetricsCms)) {
  $heroMetrics = $heroMetricsCms;
}

$heroBandSource = !empty($heroMetricsCms) ? $heroMetrics : array_slice($statItems, 0, 4);
$heroBandStats = array_map(static fn(array $stat): array => [
  'num'    => $stat['num'],
  'suffix' => $stat['suffix'] ?? '',
  'label'  => $stat['label'],
  'icon'   => $stat['icon'] ?? 'bi-award',
], array_slice($heroBandSource, 0, 4));
?>
<?php if ($showHero): ?>
<section
  class="hq-hero hq-hero--cinematic<?= $heroMulti ? ' hq-hero--carousel' : '' ?>"
  id="hero"
  data-hero-carousel="<?= $heroMulti ? '1' : '0' ?>"
  data-autoplay="<?= $heroCarouselOpts['autoplay'] ? '1' : '0' ?>"
  data-interval="<?= (int)$heroCarouselOpts['interval'] * 1000 ?>"
  data-dots="<?= $heroCarouselOpts['dots'] ? '1' : '0' ?>"
  data-pause-hover="<?= $heroCarouselOpts['pause_hover'] ? '1' : '0' ?>"
  data-transition="<?= e($heroCarouselOpts['transition']) ?>"
  aria-label="<?= e($siteName) ?> featured introduction"
  <?= $heroMulti ? 'aria-roledescription="carousel"' : '' ?>
>
  <div class="hq-hero__stage">
    <div class="hq-hero__panes hq-hero__panes--media">
      <?php foreach ($sliders as $i => $slide): ?>
      <?php
        $sImg = heroSlideImageUrl($slide, (int)$i);
        $sVideo = heroSlideVideoUrl($slide);
        $sAspect = heroSlideAspect($slide);
        $sMobile = !empty($slide['mobile_image']) && !isStaleHeroSlideImage((string)$slide['mobile_image'])
          ? mediaPathUrl((string)$slide['mobile_image'])
          : '';
        $focus = heroSlideFocus($slide, (int)$i);
        $badge = trim((string)($slide['badge_text'] ?? '')) ?: $defaultHeroTag;
        $slideTitle = (string)($slide['title'] ?? $siteName);
        $slideOverlay = min(0.18, max(0, (float)($slide['overlay_opacity'] ?? 0.6) * 0.28));
        $slideTransition = in_array(($slide['transition_type'] ?? 'inherit'), ['fade', 'slide', 'kenburns'], true)
          ? $slide['transition_type']
          : $heroCarouselOpts['transition'];
        $slideDuration = (int)($slide['autoplay_duration'] ?? 0);
        if ($slideDuration < 3 || $slideDuration > 20) {
          $slideDuration = (int)$heroCarouselOpts['interval'];
        }
      ?>
      <figure
        class="hq-hero__shot hq-hero__pane hq-hero__shot--fit<?= $sVideo !== '' ? ' hq-hero__shot--video' : '' ?><?= $i === 0 ? ' is-active' : '' ?>"
        data-hero-pane
        data-hero-media="<?= $sVideo !== '' ? 'video' : 'image' ?>"
        data-hero-aspect="<?= e($sAspect) ?>"
        data-hero-transition="<?= e($slideTransition) ?>"
        data-hero-duration="<?= (int)$slideDuration * 1000 ?>"
        style="--hero-focus: <?= e($focus['desk']) ?>; --hero-focus-mobile: <?= e($focus['mobile']) ?>;"
        aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"
      >
        <span class="hq-hero__media-fill" aria-hidden="true">
          <img src="<?= e($sImg) ?>" alt="" width="1920" height="1080" decoding="async">
        </span>
        <?php if ($sVideo !== ''): ?>
        <video
          class="hq-hero__video hq-hero__media-fit"
          poster="<?= e($sImg) ?>"
          muted
          loop
          playsinline
          preload="<?= $i === 0 ? 'auto' : 'metadata' ?>"
          <?= $i === 0 ? 'autoplay' : '' ?>
        >
          <source src="<?= e($sVideo) ?>" type="video/mp4">
        </video>
        <?php else: ?>
        <picture class="hq-hero__media-fit">
          <?php if ($sMobile !== ''): ?>
          <source media="(max-width: 767px)" srcset="<?= e($sMobile) ?>">
          <?php endif; ?>
          <img
            src="<?= e($sImg) ?>"
            alt="<?= e($slideTitle) ?>"
            width="1920"
            height="1080"
            sizes="100vw"
            loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
            decoding="async"
            fetchpriority="<?= $i === 0 ? 'high' : 'low' ?>"
          >
        </picture>
        <?php endif; ?>
        <span class="hq-hero__shot-dim" style="opacity:<?= e((string)$slideOverlay) ?>" aria-hidden="true"></span>
        <figcaption class="hq-hero__caption">
          <span class="hq-hero__caption-tag"><?= $sVideo !== '' ? 'Video' : 'Featured' ?></span>
          <span class="hq-hero__caption-title"><?= e($badge) ?></span>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
    <div class="hq-hero__wash" aria-hidden="true"></div>
    <div class="hq-hero__scrim" aria-hidden="true"></div>
  </div>

  <div class="container-site hq-hero__wrap">
    <div class="hq-hero__copy" data-anim="up">
      <div class="hq-hero__panes hq-hero__panes--copy">
        <?php foreach ($sliders as $i => $slide): ?>
        <?php
          $parts = $heroParseTitle($slide['title'] ?? $siteName);
          $style = $slide['content_style'] ?? 'standard';
          $showDesc = ($slide['show_description'] ?? 1) && !empty($slide['description']);
          $paneClass = 'hq-hero__pane' . ($style !== 'standard' ? ' hq-hero__pane--' . e($style) : '');
          $align = $slide['text_align'] ?? 'center';
          if (in_array($align, ['left', 'right'], true)) {
            $paneClass .= ' hq-hero__pane--align-' . $align;
          }
          $primaryHref = $homeHref($normalizeCta($slide['button_link'] ?? $quoteHref));
          $secondaryHref = $homeHref($normalizeCta($slide['button_link_2'] ?? '/projects'));
        ?>
        <div class="<?= $paneClass ?><?= $i === 0 ? ' is-active' : '' ?>" data-hero-pane aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
          <div class="hq-hero__eyebrow">
            <span class="hq-hero__eyebrow-pill">
              <span class="hq-hero__pulse-dot" aria-hidden="true"></span>
              <span class="hq-hero__eyebrow-label"><?= e($slide['subtitle'] ?? 'Premium Construction & Architecture') ?></span>
            </span>
            <span class="hq-hero__eyebrow-tag"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?= e($heroLocation !== '' ? $heroLocation : 'Garowe · Somalia') ?></span>
          </div>
          <?php $headingTag = $i === 0 ? 'h1' : 'p'; ?>
          <<?= $headingTag ?> class="hq-hero__title">
            <?php if ($parts['main'] !== ''): ?><span class="hq-hero__title-main"><?= e($parts['main']) ?></span><?php endif; ?>
            <?php if ($parts['accent'] !== ''): ?><span class="hq-hero__title-accent"><?= e($parts['accent']) ?></span><?php endif; ?>
          </<?= $headingTag ?>>
          <?php if ($showDesc): ?>
          <p class="hq-hero__desc"><?= e($slide['description']) ?></p>
          <?php endif; ?>
          <div class="hq-hero__actions">
            <a href="<?= e($primaryHref) ?>"<?= $homeTarget($slide['button_link'] ?? $quoteHref) ?> class="hq-btn hq-btn--orange hq-btn--lg hq-hero__btn-primary">
              <span class="hq-btn__icon" aria-hidden="true"><i class="bi bi-whatsapp"></i></span>
              <span><?= e($slide['button_text'] ?? 'Get a Free Quote') ?></span>
              <i class="bi bi-arrow-right hq-btn__arrow" aria-hidden="true"></i>
            </a>
            <a href="<?= e($secondaryHref) ?>" class="hq-btn hq-btn--lg hq-hero__btn-secondary">
              <span><?= e($slide['button_text_2'] ?? 'View Our Work') ?></span>
              <i class="bi bi-arrow-up-right hq-btn__arrow" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php if ($heroMulti): ?>
      <div class="hq-hero__chrome">
        <div class="hq-hero__controls" aria-label="Hero carousel controls">
          <div class="hq-hero__counter" aria-live="polite">
            <span class="hq-hero__counter-current" data-hero-current>01</span>
            <span class="hq-hero__counter-sep">/</span>
            <span class="hq-hero__counter-total"><?= str_pad((string)count($sliders), 2, '0', STR_PAD_LEFT) ?></span>
          </div>
          <?php if ($heroCarouselOpts['dots']): ?>
          <div class="hq-hero__dots" role="tablist" aria-label="Hero slides">
            <?php foreach ($sliders as $i => $slide): ?>
            <button type="button" class="hq-hero__dot<?= $i === 0 ? ' is-active' : '' ?>" data-hero-dot="<?= $i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="hero" aria-label="Slide <?= $i + 1 ?>: <?= e($slide['title']) ?>">
              <span class="hq-hero__dot-fill" data-hero-dot-fill></span>
            </button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <div class="hq-hero__nav">
            <button type="button" class="hq-hero__nav-btn" data-hero-prev aria-label="Previous slide"><i class="bi bi-arrow-left" aria-hidden="true"></i></button>
            <button type="button" class="hq-hero__nav-btn" data-hero-next aria-label="Next slide"><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($heroBandStats)): ?>
  <div class="hq-hero__band" data-anim="up" data-delay="180">
    <div class="container-site">
      <div class="hq-hero__band-inner">
        <?php foreach ($heroBandStats as $i => $bs): ?>
        <div class="hq-hero__band-item">
          <span class="hq-hero__band-icon" aria-hidden="true"><i class="bi <?= e($bs['icon'] ?? 'bi-award') ?>"></i></span>
          <div class="hq-hero__band-content">
            <strong data-counter data-target="<?= e($bs['num']) ?>" data-suffix="<?= e($bs['suffix']) ?>"><?= e($bs['num']) ?><?= e($bs['suffix']) ?></strong>
            <span><?= e($bs['label']) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</section>
<?php endif; ?>

<!-- TRUST BAR -->
<?php if ($showHeroTrust && !empty($trustBarItems)): ?>
<div class="hq-trustbar" aria-label="Company credentials">
  <div class="hq-trustbar__track">
    <?php for ($t = 0; $t < 2; $t++): foreach ($trustBarItems as $item): ?>
    <span class="hq-trustbar__item"><i class="bi bi-check-circle-fill"></i> <?= e(is_array($item) ? ($item['label'] ?? $item['title'] ?? '') : $item) ?></span>
    <?php endforeach; endfor; ?>
  </div>
</div>
<?php endif; ?>

<!-- ABOUT -->
<?php if ($showAbout): ?>
<section class="hq-section hq-about-premium" id="about">
  <div class="container-site">
    <div class="hq-about-premium__shell">
      <div class="hq-about-premium__media" data-anim="left">
        <figure class="hq-about-premium__frame">
          <img src="<?= e($aboutImg) ?>" alt="Completed HighQ Homes residence in Garowe, Puntland" loading="lazy">
          <span class="hq-about-premium__shade" aria-hidden="true"></span>
          <figcaption class="hq-about-premium__badge">
            <strong><?= e($settings['stat_years'] ?? '4') ?>+</strong>
            <span>Years Building<br>With Excellence</span>
          </figcaption>
          <span class="hq-about-premium__chip"><i class="bi bi-house-check" aria-hidden="true"></i> Completed Residential Project</span>
        </figure>
      </div>

      <div class="hq-about-premium__content" data-anim="right">
        <p class="hq-eyebrow hq-about-premium__eyebrow"><?= e($aboutTitle) ?></p>
        <h2 class="hq-title hq-about-premium__title"><?= e($aboutSubtitle) ?></h2>
        <p class="hq-lead hq-about-premium__lead"><?= e($aboutLead) ?></p>

        <div class="hq-about-premium__highlights">
          <?php foreach ($aboutHighlights as $i => $h): ?>
          <article class="hq-about-premium__highlight" data-anim="up" data-delay="<?= $i * 50 ?>">
            <span class="hq-about-premium__highlight-icon" aria-hidden="true"><i class="bi <?= e($h['icon']) ?>"></i></span>
            <div class="hq-about-premium__highlight-copy">
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
          <a href="<?= url('about') ?>" class="hq-btn hq-btn--navy hq-about-premium__btn">Our Story <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
          <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-about-premium__btn"><i class="bi bi-whatsapp" aria-hidden="true"></i> Start a Project</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CAPABILITIES -->
<?php if ($showCapabilities): ?>
<section class="hq-section hq-section--soft" id="capabilities">
  <div class="container-site">
    <?php View::partial('home/section-head', [
      'kicker' => cmsText($capabilitiesSec, 'title', 'What We Build'),
      'title' => cmsText($capabilitiesSec, 'subtitle', 'Spaces Crafted With Purpose'),
      'desc' => cmsText($capabilitiesSec, 'content', 'From family homes to commercial landmarks — every project is planned, built, and finished to premium standards.'),
      'ctaHref' => url('projects'),
      'ctaLabel' => 'Explore Portfolio',
      'id' => 'hp-capabilities-title',
    ]); ?>
    <div class="hq-bento">
      <?php foreach ($capabilities as $i => $cap): ?>
      <a href="<?= e(menuUrl($cap['href'] ?? '/projects')) ?>" class="hq-bento__card<?= !empty($cap['mod']) ? ' hq-bento__card--' . e($cap['mod']) : '' ?>" data-anim="up" data-delay="<?= $i * 60 ?>">
        <img src="<?= e($cap['img'] ?? '') ?>" alt="<?= e($cap['title'] ?? 'HighQ Homes project') ?>" loading="lazy">
        <div class="hq-bento__shade"></div>
        <div class="hq-bento__body">
          <span class="hq-bento__icon"><i class="bi <?= e($cap['icon'] ?? 'bi-building') ?>"></i></span>
          <h3><?= e($cap['title'] ?? '') ?></h3>
          <p><?= e($cap['text'] ?? '') ?></p>
          <span class="hq-bento__link">Learn more <i class="bi bi-arrow-up-right"></i></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if (!empty($buildGallery)): ?>
    <div class="hq-bento-gallery" aria-label="Recent HighQ Homes projects">
      <?php foreach ($buildGallery as $g): ?>
      <a href="<?= url('projects') ?>" class="hq-bento-gallery__item">
        <img src="<?= e($g['src']) ?>" alt="<?= e($g['alt']) ?>" loading="lazy">
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

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
          <p class="hq-port-intro__kicker"><?= e(cmsText($projectsSec, 'title', 'Portfolio')) ?></p>
          <?php
            $projectsHeading = cmsText($projectsSec, 'subtitle', 'Signature Work That Defines Our Standard');
            $projectsAccent = '';
            if (preg_match('/\s(Defines Our Standard|Our Standard)$/i', $projectsHeading, $accentMatch)) {
              $projectsAccent = trim($accentMatch[1]);
              $projectsHeading = trim(substr($projectsHeading, 0, -strlen($accentMatch[0])));
            }
          ?>
          <h2 class="hq-port-intro__title" id="hp-portfolio-title">
            <?= e($projectsHeading) ?>
            <?php if ($projectsAccent !== ''): ?>
            <span class="hq-port-intro__accent"><?= e($projectsAccent) ?></span>
            <?php endif; ?>
          </h2>
          <p class="hq-port-intro__desc"><?= e(cmsText($projectsSec, 'content', 'Explore featured builds and recent completions — each project reflects our commitment to quality, clarity, and premium finish.')) ?></p>
          <div class="hq-port-intro__actions">
            <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange"><?= e($projectsMeta['cta_primary'] ?? 'Full Portfolio') ?> <i class="bi bi-arrow-up-right"></i></a>
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
          <p class="hq-hp-portfolio__recent-kicker"><i class="bi bi-hammer"></i> <?= e($projectsMeta['recent_kicker'] ?? 'Recent Sites') ?></p>
          <h3 class="hq-hp-portfolio__recent-title"><?= e($projectsMeta['recent_title'] ?? 'Fresh Completions & Active Builds') ?></h3>
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
        <strong><?= e($projectsMeta['band_title'] ?? 'Ready to start your next build?') ?></strong>
        <span><?= e($projectsMeta['band_text'] ?? 'Share your vision and receive a structured consultation from our team.') ?></span>
      </div>
      <div class="hq-hp-portfolio__band-actions">
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange"><i class="bi bi-whatsapp"></i> Get a Quote</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- WHY CHOOSE US -->
<?php if ($showWhyUs && !empty($whyItems)): ?>
<?php
  $whyNorm = static function (string $current, array $stale, string $fresh): string {
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
  $whyKicker = $whyNorm(cmsText($whySec, 'title', ''), ['Why Choose Us', 'Why choose us', 'Why HighQ Homes'], 'Why us');
  $whyTitle  = $whyNorm(cmsText($whySec, 'subtitle', ''), [
    'The HighQ Homes Difference',
    'The HighQ Homes difference',
    'Built on Trust & Craft',
    'Quality, trust, and accountable delivery.',
  ], 'A builder you can inspect, not just trust.');
  $whyLead   = $whyNorm(cmsText($whySec, 'content', ''), [
    'Six principles that guide every project — from first consultation to final handover.',
    'We create durable homes and commercial spaces that meet practical needs and enhance the Garowe community.',
    'Clients pick HighQ Homes for one team, a written scope, and homes photographed on site in Garowe.',
  ], 'Walk a finished house in Garowe. The photos on this site are from our jobs — you can inspect the work, not a brochure.');
  $whyImg = asset('images/builds/yellow-residence.jpg');
  $whyChips = ['On-site photos', 'Written quotes', 'After-care'];
?>
<section class="hq-section hq-section--soft hq-why-section" id="why-us" aria-labelledby="hp-why-title">
  <div class="container-site">
    <div class="hq-why__shell">
      <figure class="hq-why__photo" data-anim="left">
        <img src="<?= e($whyImg) ?>" alt="Completed HighQ Homes family residence in Garowe" loading="lazy">
        <span class="hq-why__photo-shade" aria-hidden="true"></span>
        <figcaption>Finished family home · Garowe</figcaption>
      </figure>
      <div class="hq-why__intro" data-anim="right">
        <p class="hq-eyebrow"><?= e($whyKicker) ?></p>
        <h2 class="hq-title" id="hp-why-title"><?= e($whyTitle) ?></h2>
        <p class="hq-lead"><?= e($whyLead) ?></p>
        <ul class="hq-why__chips">
          <?php foreach ($whyChips as $chip): ?>
          <li><?= e($chip) ?></li>
          <?php endforeach; ?>
        </ul>
        <ul class="hq-why__list">
          <?php foreach ($whyItems as $i => $item): ?>
          <li class="hq-why-row" data-anim="up" data-delay="<?= $i * 40 ?>">
            <span class="hq-why-row__icon" aria-hidden="true"><i class="bi <?= e($item['icon'] ?? 'bi-check-circle') ?>"></i></span>
            <div class="hq-why-row__copy">
              <h3><?= e($item['title'] ?? '') ?></h3>
              <p><?= e($item['text'] ?? '') ?></p>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <div class="hq-why__actions">
          <a href="<?= url('projects') ?>" class="hq-btn hq-btn--navy">View our work <i class="bi bi-arrow-right"></i></a>
          <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange"><i class="bi bi-whatsapp"></i> Get a quote</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- PROCESS -->
<?php if ($showProcess && !empty($steps)): ?>
<section class="hq-section hq-process-section">
  <div class="container-site">
    <?php View::partial('home/section-head', [
      'kicker' => cmsText($processSec, 'title', 'Our Process'),
      'title' => cmsText($processSec, 'subtitle', 'From Vision to Handover'),
      'desc' => cmsText($processSec, 'content', 'A structured, transparent path designed to reduce risk and deliver exceptional results.')
    ]); ?>
    <div class="hq-process__timeline">
      <?php foreach ($steps as $i => $step): ?>
      <article class="hq-step hq-step--timeline" data-anim="up" data-delay="<?= $i * 60 ?>">
        <span class="hq-step__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
        <div class="hq-step__body">
          <h3><?= e($step['title'] ?? '') ?></h3>
          <p><?= e($step['text'] ?? '') ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

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
        <p class="hq-testimonials-premium__kicker"><?= e(cmsText($testimonialsSec, 'title', 'Testimonials')) ?></p>
        <h2 class="hq-testimonials-premium__title"><?= e(cmsText($testimonialsSec, 'subtitle', 'What Our Clients Say')) ?></h2>
        <p class="hq-testimonials-premium__lead"><?= e(cmsText($testimonialsSec, 'content', 'Trusted by owners, developers, and community partners across every project type.')) ?></p>
        <?php
          $testimonialChips = $testimonialsMeta['chips'] ?? ['Client-rated excellence', 'Verified project delivery', 'Residential & commercial'];
          $testimonialChipIcons = ['bi-star-fill', 'bi-shield-check', 'bi-buildings'];
        ?>
        <ul class="hq-testimonials-premium__chips">
          <?php foreach (array_slice($testimonialChips, 0, 4) as $i => $chip): ?>
          <li><i class="bi <?= e($testimonialChipIcons[$i] ?? 'bi-check2') ?>"></i> <?= e(is_array($chip) ? ($chip['label'] ?? $chip['title'] ?? '') : $chip) ?></li>
          <?php endforeach; ?>
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
<?php if ($showFaq && !empty($faqItems)): ?>
<section class="hq-section hq-faq-premium" id="faq">
  <div class="hq-faq-premium__bg" aria-hidden="true"></div>
  <div class="container-site">
    <div class="hq-faq-premium__shell">
      <aside class="hq-faq-premium__aside" data-anim="left">
        <p class="hq-faq-premium__kicker"><?= e(cmsText($faqSec, 'title', 'Questions & Answers')) ?></p>
        <?php
          $faqHeading = cmsText($faqSec, 'subtitle', 'Everything You Need to Know');
          $faqHasAccent = (bool)preg_match('/before you build/i', $faqHeading);
        ?>
        <h2 class="hq-faq-premium__title"><?= e($faqHeading) ?><?php if (!$faqHasAccent): ?> <em>Before You Build</em><?php endif; ?></h2>
        <p class="hq-faq-premium__lead"><?= e(cmsText($faqSec, 'content', 'Clear answers to the questions clients ask most — so you can plan your project with confidence.')) ?></p>
        <?php $faqChipIcons = ['bi-lightning-charge', 'bi-shield-check', 'bi-chat-square-text']; ?>
        <ul class="hq-faq-premium__chips">
          <?php foreach (array_slice($faqChips, 0, 4) as $i => $chip): ?>
          <li><i class="bi <?= e(is_array($chip) ? ($chip['icon'] ?? $faqChipIcons[$i] ?? 'bi-check2') : ($faqChipIcons[$i] ?? 'bi-check2')) ?>"></i> <?= e(is_array($chip) ? (string)($chip['label'] ?? $chip['title'] ?? '') : (string)$chip) ?></li>
          <?php endforeach; ?>
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
<?php endif; ?>
