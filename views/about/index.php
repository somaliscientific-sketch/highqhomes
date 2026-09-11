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
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20learn%20more%20about%20your%20company';

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

$cms           = $sections ?? [];
$heroSec       = cmsRow($cms, 'hero');
$historySec    = cmsRow($cms, 'history');
$timelineSec   = cmsRow($cms, 'timeline');
$statsSec      = cmsRow($cms, 'stats');
$missionSec    = cmsRow($cms, 'mission');
$valuesSec     = cmsRow($cms, 'values');
$processSec    = cmsRow($cms, 'process');
$teamSec       = cmsRow($cms, 'team');
$quotesSec     = cmsRow($cms, 'testimonials');
$ctaSec        = cmsRow($cms, 'cta');
$heroMeta      = cmsMap($heroSec);
$historyMeta   = cmsMap($historySec);
$ctaMeta       = cmsMap($ctaSec);



// About FAQ data
$aboutFaqItems = [
    ['q' => 'How do I get a project quote?', 'a' => 'Share your site details, scope, and timeline via WhatsApp, phone, or our contact form. We respond with a structured consultation and transparent proposal.', 'icon' => 'bi-calculator'],
    ['q' => 'What types of projects do you handle?', 'a' => 'HighQ Homes delivers residential homes, commercial spaces, renovations, premium finishing, and design-to-build planning for clients across Somalia.', 'icon' => 'bi-buildings'],
    ['q' => 'How long does a typical build take?', 'a' => 'Timelines depend on scope, materials, and site conditions. After discovery, we provide a milestone schedule with clear dates and progress checkpoints.', 'icon' => 'bi-calendar2-week'],
    ['q' => 'Can I track progress during construction?', 'a' => 'Yes. We provide regular site updates and milestone reviews so you stay informed at every major phase of the project.', 'icon' => 'bi-camera-reels'],
    ['q' => 'Do you manage finishing and interior details?', 'a' => 'Absolutely. From structural work to paints and refined interior finishes, we offer integrated delivery for a complete, move-in-ready result.', 'icon' => 'bi-brush'],
];
$aboutFaqSec = cmsRow($cms, 'faq');
$aboutFaqList = cmsList($aboutFaqSec);
if ($aboutFaqList !== []) { $aboutFaqItems = $aboutFaqList; }
$showAboutFaq = true;
$heroImageDefault = asset('images/builds/grey-villa-evening.jpg');
$storyImageDefault = asset('images/builds/about-residence.jpg');

$heroKicker = $copyIf(cmsText($heroSec, 'title', ''), ['Who We Are', 'About Us', 'About'], 'Who we are');
$heroTitle  = $copyIf(cmsText($heroSec, 'subtitle', ''), [
    'Built on integrity. Delivered with precision.',
    'Built on Integrity. Delivered with Precision.',
], 'A trusted builder for homes that last.');
$heroLead   = $copyIf(cmsText($heroSec, 'content', ''), [
    'Premium construction across Puntland, Somalia.',
    $settings['tagline'] ?? '',
], 'HighQ Homes plans, builds, and finishes residential and commercial spaces in Garowe and across Puntland — one accountable team from first site visit to handover.');
$heroImage  = cmsMediaUrl($heroSec['image_url'] ?? '', $heroImageDefault);
if ($heroImage === '' || str_contains($heroImage, 'unsplash.com')) {
    $heroImage = $heroImageDefault;
}

$historyTitle    = $copyIf(cmsText($historySec, 'title', ''), ['Our History', 'Our story'], 'Our story');
$historySubtitle = $copyIf(cmsText($historySec, 'subtitle', ''), ['The HighQ Homes story'], 'Built in Garowe since 2016.');
$historyContent  = $copyIf(cmsText($historySec, 'content', ''), [
    'HighQ Homes was founded in Garowe with a clear purpose — deliver world-class residential and commercial construction with honest service, broad vision, and great value.',
    $aboutText,
], 'We started in Garowe with a simple brief: deliver homes and commercial spaces people can trust. We still work that way — written scope, visible progress, and a finish you can inspect before the keys are handed over.');
$historyImage    = cmsMediaUrl($historySec['image_url'] ?? '', $storyImageDefault);
if ($historyImage === '' || str_contains($historyImage, 'unsplash.com')) {
    $historyImage = $storyImageDefault;
}

$historyFacts = $historyMeta['facts'] ?? [
  ['label' => 'Founded', 'value' => '2016'],
  ['label' => 'Headquarters', 'value' => 'Garowe, Puntland'],
  ['label' => 'Focus', 'value' => 'Homes & commercial'],
];
$historyFactList = cmsList($historySec);
if ($historyFactList !== [] && isset($historyFactList[0]) && is_array($historyFactList[0]) && isset($historyFactList[0]['label'])) {
  $historyFacts = $historyFactList;
}

$historyTimeline = [
  ['year' => '2016', 'text' => 'HighQ Homes established in Garowe to raise the standard of residential and commercial construction in Puntland.'],
  ['year' => '2021', 'text' => 'Structured delivery in place — milestone plans, quality checks, and one team accountable for the finish.'],
  ['year' => '2023', 'text' => 'Design, construction, and finishing integrated so nothing is lost between drawings and handover.'],
  ['year' => 'Today', 'text' => 'Homes on this site were photographed on location — family villas, compounds, and active builds in Garowe.'],
];
$timelineList = cmsList($timelineSec);
$staleTimeline = [
    'HighQ Homes established in Garowe with a mission to raise construction standards across Puntland.',
    'First residential and commercial projects delivered with structured quality control.',
    'Expanded integrated design, construction, and finishing services under one team.',
    'Trusted partner for premium builds — from planning to handover.',
];
if ($timelineList !== []) {
    $texts = array_map(static fn(array $row): string => (string)($row['text'] ?? $row['content'] ?? ''), $timelineList);
    $historyTimeline = array_intersect($staleTimeline, $texts) !== [] ? $historyTimeline : $timelineList;
}

$valueItems = [
    ['icon' => 'bi-geo-alt', 'title' => 'Based in Garowe', 'text' => 'We build for Puntland sites, climate, and family living — with local accountability from first meeting to handover.'],
    ['icon' => 'bi-people', 'title' => 'One accountable team', 'text' => 'Design, construction, and finishing stay under one standard so nothing is lost between trades.'],
    ['icon' => 'bi-clipboard-check', 'title' => 'Clear scope & timeline', 'text' => 'Transparent quotes, milestone updates, and a finish you can inspect before the keys are handed over.'],
    ['icon' => 'bi-house-heart', 'title' => 'Homes built to last', 'text' => 'Durable materials and careful workmanship chosen for long-term performance, not a short-lived look.'],
];
$cmsValues = cmsList($valuesSec);
if ($cmsValues !== []) {
    $titles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsValues);
    $seedTitles = ['Integrity First', 'Premium Quality', 'Client Partnership', 'Local Expertise'];
    $valueItems = array_intersect($seedTitles, $titles) === $seedTitles ? $valueItems : $cmsValues;
}

$processSteps = [
    ['num' => '01', 'title' => 'Discovery', 'text' => 'We walk the site, confirm your goals, and test what the plot can support before design begins.'],
    ['num' => '02', 'title' => 'Design & planning', 'text' => 'Drawings, approvals, and a milestone schedule with checkpoints you can follow.'],
    ['num' => '03', 'title' => 'Build execution', 'text' => 'Disciplined site work with inspections before each stage is signed off.'],
    ['num' => '04', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care when you move in.'],
];
$cmsProcess = cmsList($processSec);
if ($cmsProcess !== []) {
    $titles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsProcess);
    $seedTitles = ['Consultation', 'Planning', 'Construction', 'Handover'];
    $processSteps = array_intersect($seedTitles, $titles) === $seedTitles ? $processSteps : $cmsProcess;
}

$mvCards = [
    ['icon' => 'bi-bullseye', 'eyebrow' => 'Mission', 'title' => 'What we deliver', 'text' => $mission ?: 'Plan, build, and finish spaces in Puntland with a written scope, durable materials, and craftsmanship you can inspect at handover.', 'mod' => 'mission'],
    ['icon' => 'bi-compass', 'eyebrow' => 'Vision', 'title' => 'Where we are going', 'text' => $vision ?: 'Be the builder families and businesses in Garowe call first — honest timelines, local knowledge, and homes that last.', 'mod' => 'vision'],
];
$cmsMission = cmsList($missionSec);
if ($cmsMission !== []) {
    $staleMission = ['Deliver exceptional construction with premium materials, skilled craftsmanship, and unwavering integrity.', 'Lead East Africa in sustainable, innovative construction that transforms communities.'];
    $texts = array_map(static fn(array $row): string => (string)($row['text'] ?? $row['content'] ?? ''), $cmsMission);
    $mvCards = array_intersect($staleMission, $texts) !== [] ? $mvCards : $cmsMission;
}

$statLabels = array_map(static fn(array $row): string => strtolower((string)($row['label'] ?? '')), $statItems ?? []);
$staleStat = ['profiled projects', 'current projects', 'years operating', 'completed projects'];
if (array_intersect($staleStat, $statLabels) !== []) {
    // Controller already supplies portfolio stats; CMS seed numbers are ignored.
}

$heroChips = [
    ['icon' => 'bi-calendar2-check', 'label' => 'Established 2016'],
    ['icon' => 'bi-geo-alt', 'label' => 'Garowe, Puntland'],
    ['icon' => 'bi-house-heart', 'label' => 'Residential & commercial'],
];
if (!empty($heroMeta['chips']) && is_array($heroMeta['chips'])) {
    $chipLabels = array_map(static function ($chip): string {
        return strtolower(is_array($chip) ? (string)($chip['label'] ?? $chip['title'] ?? '') : (string)$chip);
    }, $heroMeta['chips']);
    if (!in_array('established 2016', $chipLabels, true)) {
        $heroChips = $heroMeta['chips'];
    }
}

$clientMeta = static function (array $t): string {
    return trim(($t['position'] ?? '') . (!empty($t['company']) ? ', ' . $t['company'] : ''));
};

$valueIcons = ['bi-heart', 'bi-gem', 'bi-clipboard-check', 'bi-shield-check', 'bi-people', 'bi-compass'];
$teamCount = is_array($team ?? null) ? count($team) : 0;
$timelineCount = count($historyTimeline);
$foundedYear = $historyFacts[0]['value'] ?? '2016';
$hqLocation  = $historyFacts[1]['value'] ?? 'Garowe, Puntland';

$showHero         = cmsRowEnabled($cms, 'hero', true);
$showHistory      = cmsRowEnabled($cms, 'history', true);
$showTimeline     = cmsRowEnabled($cms, 'timeline', !empty($historyTimeline));
$showStats        = cmsRowEnabled($cms, 'stats', true);
$showMission      = cmsRowEnabled($cms, 'mission', true);
$showValues       = cmsRowEnabled($cms, 'values', true) && !empty($valueItems);
$showProcess      = cmsRowEnabled($cms, 'process', true) && !empty($processSteps);
$showTeam         = cmsRowEnabled($cms, 'team', true) && !empty($team);
$showTestimonials = cmsRowEnabled($cms, 'testimonials', true) && !empty($testimonials);
$showCta          = cmsRowEnabled($cms, 'cta', true);

$valuesKicker = $copyIf(cmsText($valuesSec, 'title', ''), ['Our Values'], 'What we stand for');
$valuesTitle  = $copyIf(cmsText($valuesSec, 'subtitle', ''), ['What guides every project'], 'How we work with clients');
$valuesLead   = $copyIf(cmsText($valuesSec, 'content', ''), ['Principles that shape how we plan, build, and support every client relationship.'], 'Four commitments that show up on site — not only on a values page.');

$processKicker = $copyIf(cmsText($processSec, 'title', ''), ['How We Work'], 'How we deliver');
$processTitle  = $copyIf(cmsText($processSec, 'subtitle', ''), ['Simple steps, clear delivery'], 'From first site visit to the keys');
$processLead   = $copyIf(cmsText($processSec, 'content', ''), [
    'A structured path that keeps your project transparent, controlled, and on schedule.',
], 'A clear path that keeps the build on schedule and the finish on standard.');

$ctaKicker = $copyIf(cmsText($ctaSec, 'title', ''), ['Start your project'], 'Start your build');
$ctaTitle  = $copyIf(cmsText($ctaSec, 'subtitle', ''), ['Ready to build with HighQ Homes?', 'Ready to build with ' . $siteName . '?'], 'Ready to plan a home with HighQ Homes?');
$ctaLead   = $copyIf(cmsText($ctaSec, 'content', ''), [
    'Share your vision — we\'ll guide you from planning to handover with clarity, quality, and care.',
    'Share your vision — we’ll guide you from planning to handover with clarity, quality, and care.',
], 'Tell us about your plot. We will come back with a clear plan, an honest timeline, and a quote you can trust.');
?>

<?php if ($showHero): ?>
<section class="hq-about-pro-hero hq-about-pro-hero--cinematic">
  <div class="hq-about-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-about-pro-hero__overlay" aria-hidden="true"></div>
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
        <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange hq-btn--lg"><?= e($heroMeta['button_text'] ?? 'View our work') ?> <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($heroMeta['button_text_2'] ?? 'Get a quote') ?></a>
      </div>
      <ul class="hq-about-pro-hero__chips">
        <?php foreach (array_slice($heroChips, 0, 4) as $chip): ?>
        <?php
          $chipLabel = is_array($chip) ? (string)($chip['label'] ?? $chip['title'] ?? $chip['text'] ?? '') : (string)$chip;
          $chipIcon  = is_array($chip) ? (string)($chip['icon'] ?? 'bi-check-circle-fill') : 'bi-check-circle-fill';
          if ($chipLabel === '') continue;
        ?>
        <li><i class="bi <?= e($chipIcon) ?>"></i> <?= e($chipLabel) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showHistory): ?>
<section class="hq-about-pro-story" id="our-story">
  <div class="container-site">
    <div class="hq-about-pro-story__shell">
      <div class="hq-about-pro-story__media" data-anim="left">
        <div class="hq-about-pro-story__frame">
          <img src="<?= e($historyImage) ?>" alt="<?= e($siteName) ?> completed residence in Garowe" loading="eager">
        </div>
        <div class="hq-about-pro-story__badge">
          <strong><?= e($foundedYear) ?></strong>
          <span><?= e($historyMeta['badge_label'] ?? 'Established') ?></span>
        </div>
        <div class="hq-about-pro-story__chip">
          <i class="bi bi-geo-alt-fill"></i> <?= e($hqLocation) ?>
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
          <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange"><?= e($historyMeta['cta_label'] ?? 'See our projects') ?> <i class="bi bi-arrow-up-right"></i></a>
          <a href="tel:<?= e($phoneHref) ?>" class="hq-about-pro-link"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
        </div>
      </div>
    </div>

    <?php if ($showTimeline && !empty($historyTimeline)): ?>
    <ol class="hq-about-pro-timeline" data-count="<?= (int)$timelineCount ?>">
      <?php foreach ($historyTimeline as $i => $item): ?>
      <li data-anim="up" data-delay="<?= $i * 50 ?>">
        <span class="hq-about-pro-timeline__year"><?= e($item['year'] ?? $item['title'] ?? '') ?></span>
        <p><?= e($item['text'] ?? $item['content'] ?? '') ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showStats && !empty($statItems)): ?>
<section class="hq-about-pro-stats" id="about-stats" aria-label="<?= e(cmsText($statsSec, 'title', 'Company highlights')) ?>">
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
<?php endif; ?>

<?php if ($showMission && !empty($mvCards)): ?>
<section class="hq-about-pro-mv" id="about-purpose">
  <div class="container-site hq-about-pro-mv__grid">
    <?php foreach ($mvCards as $i => $card): ?>
    <?php
      if (!is_array($card)) continue;
      $cardMod = $card['mod'] ?? ($i === 0 ? 'mission' : 'vision');
    ?>
    <article class="hq-about-pro-mv__card hq-about-pro-mv__card--<?= e($cardMod) ?>" data-anim="up"<?= $i > 0 ? ' data-delay="70"' : '' ?>>
      <span class="hq-about-pro-mv__icon"><i class="bi <?= e($card['icon'] ?? ($i === 0 ? 'bi-bullseye' : 'bi-compass')) ?>"></i></span>
      <p class="hq-about-pro-eyebrow"><?= e($card['eyebrow'] ?? ($i === 0 ? 'Mission' : 'Vision')) ?></p>
      <h3><?= e($card['title'] ?? '') ?></h3>
      <p><?= e($card['text'] ?? $card['content'] ?? '') ?></p>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showValues): ?>
<section class="hq-about-pro-values" id="our-values">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e($valuesKicker) ?></p>
      <h2 class="hq-about-pro-title"><?= e($valuesTitle) ?></h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e($valuesLead) ?></p>
    </header>
    <div class="hq-about-pro-values__grid">
      <?php foreach ($valueItems as $i => $val): ?>
      <?php
        if (!is_array($val)) continue;
        $valueTitle = $val['title'] ?? $val['label'] ?? '';
        $valueText  = $val['text'] ?? $val['content'] ?? '';
        if ($valueTitle === '' && $valueText === '') continue;
      ?>
      <article class="hq-about-pro-value" data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-about-pro-value__icon"><i class="bi <?= e($val['icon'] ?? $valueIcons[$i % count($valueIcons)]) ?>"></i></span>
        <h3><?= e($valueTitle) ?></h3>
        <p><?= e($valueText) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showProcess): ?>
<section class="hq-about-pro-process" id="our-process">
  <div class="container-site">
    <div class="hq-about-pro-process__shell">
      <header class="hq-about-pro-process__head" data-anim="left">
        <p class="hq-about-pro-eyebrow"><?= e($processKicker) ?></p>
        <h2 class="hq-about-pro-title"><?= e($processTitle) ?></h2>
        <p class="hq-about-pro-lead"><?= e($processLead) ?></p>
      </header>
      <ol class="hq-about-pro-process__steps" data-anim="right">
        <?php foreach ($processSteps as $i => $step): ?>
        <li>
          <span class="hq-about-pro-process__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
          <div>
            <h3><?= e($step['title'] ?? '') ?></h3>
            <p><?= e($step['text'] ?? '') ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showTeam): ?>
<section class="hq-about-pro-team" id="our-team">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e(cmsText($teamSec, 'title', 'Leadership')) ?></p>
      <h2 class="hq-about-pro-title"><?= e(cmsText($teamSec, 'subtitle', 'The team behind every build')) ?></h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e(cmsText($teamSec, 'content', 'Experienced leaders guiding strategy, quality, and client care on every project.')) ?></p>
    </header>
    <div class="hq-about-pro-team__grid<?= $teamCount === 2 ? ' hq-about-pro-team__grid--pair' : '' ?>">
      <?php foreach ($team as $i => $m): ?>
        <?php View::partial('about/leadership-card', ['member' => $m, 'index' => $i, 'delay' => $i * 55]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showTestimonials): ?>
<section class="hq-about-pro-quotes" id="about-quotes">
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e(cmsText($quotesSec, 'title', 'Client voices')) ?></p>
      <h2 class="hq-about-pro-title"><?= e(cmsText($quotesSec, 'subtitle', 'Trusted by owners & developers')) ?></h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e(cmsText($quotesSec, 'content', 'What clients say about working with HighQ Homes from first meeting to handover.')) ?></p>
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
<?php if ($showAboutFaq && !empty($aboutFaqItems)): ?>
<section class="hq-section hq-about-faq" id="about-faq" aria-labelledby="about-faq-title">
  <div class="container-site">
    <div class="hq-about-faq__shell">
      <!-- Left Column: Sticky Header & Direct Consultation Card -->
      <aside class="hq-about-faq__aside">
        <div class="hq-about-faq__badge">
          <i class="bi bi-question-circle-fill"></i>
          <span>Clear Answers</span>
        </div>
        <h2 class="hq-about-faq__title" id="about-faq-title">
          Questions &amp; <span class="hq-about-faq__highlight">Answers</span>
        </h2>
        <p class="hq-about-faq__lead">
          Everything you need to know about building, renovating, or investing with HighQ Homes in Garowe and across Puntland.
        </p>

        <ul class="hq-about-faq__trust-points">
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <span>Transparent, itemized BOQ quotes</span>
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <span>Licensed civil engineers on every site</span>
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            <span>Structured milestone payments &amp; reporting</span>
          </li>
        </ul>

        <div class="hq-about-faq__card">
          <div class="hq-about-faq__card-header">
            <div class="hq-about-faq__card-icon" aria-hidden="true">
              <i class="bi bi-chat-quote-fill"></i>
            </div>
            <div>
              <h3 class="hq-about-faq__card-title">Have a specific question?</h3>
              <p class="hq-about-faq__card-sub">Speak directly with our technical team.</p>
            </div>
          </div>
          <div class="hq-about-faq__card-actions">
            <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--sm hq-about-faq__wa-btn">
              <i class="bi bi-whatsapp"></i> Chat on WhatsApp
            </a>
            <a href="tel:<?= e($phoneHref) ?>" class="hq-about-faq__call-btn">
              <i class="bi bi-telephone"></i> <?= e($phone) ?>
            </a>
          </div>
        </div>
      </aside>

      <!-- Right Column: Premium Accordion -->
      <div class="hq-about-faq__main">
        <div class="hq-about-faq__meta-bar">
          <span class="hq-about-faq__count">
            <i class="bi bi-collection"></i> <?= count($aboutFaqItems) ?> Common Questions
          </span>
          <span class="hq-about-faq__hint">Click to expand</span>
        </div>

        <div class="hq-faq__list hq-about-faq__list">
          <?php foreach ($aboutFaqItems as $i => $faq): ?>
          <article class="hq-faq__item<?= $i === 0 ? ' is-open' : '' ?>">
            <button type="button" class="hq-faq__toggle" data-faq-toggle
              aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
              aria-controls="about-faq-panel-<?= $i ?>"
              id="about-faq-btn-<?= $i ?>">
              <span class="hq-faq__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="hq-faq__icon"><i class="bi <?= e($faq['icon'] ?? 'bi-buildings') ?>"></i></span>
              <span class="hq-faq__label"><?= e($faq['q'] ?? $faq['title'] ?? '') ?></span>
              <span class="hq-faq__chev" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
            </button>
            <div class="hq-faq__panel" id="about-faq-panel-<?= $i ?>" role="region" aria-labelledby="about-faq-btn-<?= $i ?>">
              <p><?= e($faq['a'] ?? $faq['text'] ?? $faq['content'] ?? '') ?></p>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-about-pro-cta" id="about-cta" aria-labelledby="about-cta-title">
  <div class="hq-about-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-about-pro-cta__box" data-anim="up">
    <div class="hq-about-pro-cta__copy">
      <p class="hq-about-pro-eyebrow hq-about-pro-eyebrow--light"><?= e($ctaKicker) ?></p>
      <h2 id="about-cta-title" class="hq-about-pro-cta__title"><?= e($ctaTitle) ?></h2>
      <p class="hq-about-pro-cta__lead"><?= e($ctaLead) ?></p>
    </div>
    <div class="hq-about-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMeta['button_text'] ?? 'Get a quote') ?></a>
    </div>
  </div>
</section>
<?php endif; ?>
