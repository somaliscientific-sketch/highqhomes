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

$heroKicker = cmsText($heroSec, 'title', 'Who We Are');
$heroTitle  = cmsText($heroSec, 'subtitle', 'Built on integrity. Delivered with precision.');
$heroLead   = cmsText($heroSec, 'content', $settings['tagline'] ?? 'Premium construction across Puntland, Somalia.');
$heroImage  = cmsMediaUrl($heroSec['image_url'] ?? '', 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80');

$historyTitle    = cmsText($historySec, 'title', 'Our History');
$historySubtitle = cmsText($historySec, 'subtitle', 'The HighQ Homes story');
$historyContent  = cmsText($historySec, 'content', $aboutText ?: 'HighQ Homes was founded in Garowe with a clear purpose — deliver world-class residential and commercial construction with honest service, broad vision, and great value.');
$historyImage    = cmsMediaUrl(
    $historySec['image_url'] ?? '',
    !empty($settings['home_about_image']) ? uploadUrl($settings['home_about_image']) : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80'
);

$historyFacts = $historyMeta['facts'] ?? [
  ['label' => 'Founded', 'value' => '2016'],
  ['label' => 'Headquarters', 'value' => 'Garowe, Puntland'],
  ['label' => 'Focus', 'value' => 'Residential & Commercial'],
];
$historyFactList = cmsList($historySec);
if ($historyFactList !== [] && isset($historyFactList[0]) && is_array($historyFactList[0]) && isset($historyFactList[0]['label'])) {
  $historyFacts = $historyFactList;
}

$historyTimeline = $historyMeta['timeline'] ?? [
  ['year' => '2016', 'text' => 'HighQ Homes established in Garowe with a mission to raise construction standards across Puntland.'],
  ['year' => '2021', 'text' => 'First residential and commercial projects delivered with structured quality control.'],
  ['year' => '2023', 'text' => 'Expanded integrated design, construction, and finishing services under one team.'],
  ['year' => 'Today', 'text' => 'Trusted partner for premium builds — from planning to handover.'],
];
$timelineList = cmsList($timelineSec);
if ($timelineList !== []) {
  $historyTimeline = $timelineList;
}

$valueItems = cmsList($valuesSec);
if ($valueItems === []) {
  $valueItems = [
    ['icon' => 'bi-shield-check', 'title' => 'Integrity First', 'text' => 'Honest timelines, transparent pricing, and accountable communication.'],
    ['icon' => 'bi-gem', 'title' => 'Premium Quality', 'text' => 'Materials and workmanship held to standards you can measure.'],
    ['icon' => 'bi-people', 'title' => 'Client Partnership', 'text' => 'Responsive support before, during, and after handover.'],
    ['icon' => 'bi-compass', 'title' => 'Local Expertise', 'text' => 'Deep knowledge of Puntland sites, regulations, and community needs.'],
  ];
}

$processSteps = cmsList($processSec);
if ($processSteps === []) {
  $processSteps = [
    ['num' => '01', 'title' => 'Consultation', 'text' => 'Goals, site conditions, and budget expectations.'],
    ['num' => '02', 'title' => 'Planning', 'text' => 'Drawings, materials, and a milestone schedule.'],
    ['num' => '03', 'title' => 'Construction', 'text' => 'Disciplined execution with quality checks.'],
    ['num' => '04', 'title' => 'Handover', 'text' => 'Final walkthrough and after-care support.'],
  ];
}

$mvCards = cmsList($missionSec);
if ($mvCards === []) {
  $mvCards = [
    ['icon' => 'bi-bullseye', 'eyebrow' => 'Purpose', 'title' => 'Our mission', 'text' => $mission ?: 'Deliver exceptional construction with premium materials, skilled craftsmanship, and unwavering integrity.', 'mod' => 'mission'],
    ['icon' => 'bi-compass', 'eyebrow' => 'Direction', 'title' => 'Our vision', 'text' => $vision ?: 'Lead East Africa in sustainable, innovative construction that transforms communities.', 'mod' => 'vision'],
  ];
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

$heroChips = $heroMeta['chips'] ?? [];
$foundedYear = $historyFacts[0]['value'] ?? '2016';
$hqLocation  = $historyFacts[1]['value'] ?? 'Garowe, Puntland';
if ($heroChips === []) {
  $heroChips = [
    ['icon' => 'bi-calendar2-check', 'label' => 'Established ' . $foundedYear],
    ['icon' => 'bi-geo-alt', 'label' => $hqLocation],
    ['icon' => 'bi-award', 'label' => $historyFacts[2]['value'] ?? 'Residential & commercial'],
  ];
}

$clientMeta = static function (array $t): string {
    return trim(($t['position'] ?? '') . (!empty($t['company']) ? ', ' . $t['company'] : ''));
};

$valueIcons = ['bi-heart', 'bi-gem', 'bi-clipboard-check', 'bi-shield-check', 'bi-people', 'bi-compass'];
$teamCount = is_array($team ?? null) ? count($team) : 0;
$timelineCount = count($historyTimeline);

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
?>

<?php if ($showHero): ?>
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

    <div class="hq-about-pro-hero__layout">
      <div class="hq-about-pro-hero__content" data-anim="up">
        <p class="hq-about-pro-hero__kicker"><?= e($heroKicker) ?></p>
        <h1 class="hq-about-pro-hero__title"><?= e($heroTitle) ?></h1>
        <p class="hq-about-pro-hero__lead"><?= e($heroLead) ?></p>
        <ul class="hq-about-pro-hero__chips">
          <?php foreach (array_slice($heroChips, 0, 4) as $chip): ?>
          <?php
            $chipLabel = is_array($chip) ? (string)($chip['label'] ?? $chip['title'] ?? $chip['text'] ?? '') : (string)$chip;
            $chipIcon  = is_array($chip) ? (string)($chip['icon'] ?? 'bi-check2') : 'bi-check2';
            if ($chipLabel === '') continue;
          ?>
          <li><i class="bi <?= e($chipIcon) ?>"></i> <?= e($chipLabel) ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="hq-about-pro-hero__actions">
          <a href="<?= url('projects') ?>" class="hq-btn hq-btn--orange hq-btn--lg"><?= e($heroMeta['button_text'] ?? 'View our work') ?> <i class="bi bi-arrow-up-right"></i></a>
          <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($heroMeta['button_text_2'] ?? 'Get a quote') ?></a>
        </div>
      </div>

      <aside class="hq-about-pro-hero__panel" data-anim="up" data-delay="80" aria-label="<?= e($heroMeta['snapshot_title'] ?? 'Company snapshot') ?>">
        <p><?= e($heroMeta['snapshot_title'] ?? 'Company snapshot') ?></p>
        <dl>
          <?php foreach (array_slice($historyFacts, 0, 3) as $fact): ?>
          <div>
            <dt><?= e($fact['label'] ?? '') ?></dt>
            <dd><?= e($fact['value'] ?? '') ?></dd>
          </div>
          <?php endforeach; ?>
        </dl>
        <a href="#our-story" class="hq-about-pro-hero__panel-link"><?= e($heroMeta['snapshot_link'] ?? 'Read our story') ?> <i class="bi bi-arrow-down"></i></a>
      </aside>
    </div>

    <nav class="hq-about-pro-hero__anchors" aria-label="About page sections">
      <?php if ($showHistory): ?><a href="#our-story"><i class="bi bi-book"></i> Our story</a><?php endif; ?>
      <?php if ($showMission): ?><a href="#about-purpose"><i class="bi bi-bullseye"></i> Mission</a><?php endif; ?>
      <?php if ($showValues): ?><a href="#our-values"><i class="bi bi-heart"></i> Values</a><?php endif; ?>
      <?php if ($showProcess): ?><a href="#our-process"><i class="bi bi-diagram-3"></i> Process</a><?php endif; ?>
      <?php if ($showTeam): ?><a href="#our-team"><i class="bi bi-people"></i> Team</a><?php endif; ?>
    </nav>
  </div>
</section>
<?php endif; ?>

<?php if ($showHistory): ?>
<section class="hq-about-pro-story" id="our-story">
  <div class="container-site">
    <div class="hq-about-pro-story__shell">
      <div class="hq-about-pro-story__media" data-anim="left">
        <div class="hq-about-pro-story__frame">
          <img src="<?= e($historyImage) ?>" alt="<?= e($siteName) ?> — our story" loading="eager">
        </div>
        <div class="hq-about-pro-story__badge">
          <strong><?= e($historyFacts[0]['value'] ?? '2016') ?></strong>
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
          <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline"><?= e($historyMeta['cta_label'] ?? 'Talk to our team') ?></a>
          <a href="tel:<?= e($phoneHref) ?>" class="hq-about-pro-link"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
        </div>
      </div>
    </div>

    <?php if ($showTimeline && !empty($historyTimeline)): ?>
    <ol class="hq-about-pro-timeline" data-count="<?= (int)$timelineCount ?>">
      <?php foreach ($historyTimeline as $i => $item): ?>
      <li data-anim="up" data-delay="<?= $i * 50 ?>">
        <span class="hq-about-pro-timeline__dot" aria-hidden="true"></span>
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
      <p class="hq-about-pro-eyebrow"><?= e($card['eyebrow'] ?? ($i === 0 ? 'Purpose' : 'Direction')) ?></p>
      <h3><?= e($card['title'] ?? '') ?></h3>
      <p><?= e($card['text'] ?? $card['content'] ?? '') ?></p>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($showValues): ?>
<section class="hq-about-pro-values" id="our-values">
  <div class="hq-about-pro-values__bg" aria-hidden="true"></div>
  <div class="container-site">
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e(cmsText($valuesSec, 'title', 'Our Values')) ?></p>
      <h2 class="hq-about-pro-title"><?= e(cmsText($valuesSec, 'subtitle', 'What guides every project')) ?></h2>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e(cmsText($valuesSec, 'content', 'Principles that shape how we plan, build, and support every client relationship.')) ?></p>
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
        <span class="hq-about-pro-value__index"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
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
    <header class="hq-about-pro-section-head" data-anim="up">
      <p class="hq-about-pro-eyebrow"><?= e(cmsText($processSec, 'title', 'How We Work')) ?></p>
      <h2 class="hq-about-pro-title"><?= e(cmsText($processSec, 'subtitle', 'Simple steps, clear delivery')) ?></h2>
      <?php if (cmsText($processSec, 'content') !== ''): ?>
      <p class="hq-about-pro-lead hq-about-pro-section-head__lead"><?= e(cmsText($processSec, 'content')) ?></p>
      <?php endif; ?>
    </header>
    <ol class="hq-about-pro-process__steps">
      <?php foreach ($processSteps as $i => $step): ?>
      <li data-anim="up" data-delay="<?= $i * 55 ?>">
        <span class="hq-about-pro-process__num"><?= e($step['num'] ?? str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span>
        <div>
          <h3><?= e($step['title'] ?? '') ?></h3>
          <p><?= e($step['text'] ?? '') ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
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
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-about-pro-cta" id="about-cta" aria-labelledby="about-cta-title">
  <div class="hq-about-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-about-pro-cta__box" data-anim="up">
    <div class="hq-about-pro-cta__copy">
      <p class="hq-about-pro-eyebrow hq-about-pro-eyebrow--light"><?= e(cmsText($ctaSec, 'title', 'Start your project')) ?></p>
      <h2 id="about-cta-title" class="hq-about-pro-cta__title"><?= e(cmsText($ctaSec, 'subtitle', 'Ready to build with ' . $siteName . '?')) ?></h2>
      <p class="hq-about-pro-cta__lead"><?= e(cmsText($ctaSec, 'content', 'Share your vision — we\'ll guide you from planning to handover with clarity, quality, and care.')) ?></p>
    </div>
    <div class="hq-about-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMeta['button_text'] ?? 'Get a quote') ?></a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--ghost hq-btn--lg"><?= e($ctaMeta['button_text_2'] ?? 'Contact us') ?></a>
    </div>
  </div>
</section>
<?php endif; ?>
