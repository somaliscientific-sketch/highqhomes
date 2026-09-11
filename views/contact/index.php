<?php
$bodyPage  = 'contact';
$pageTitle = $seo['meta_title'] ?? 'Contact Us — HighQ Homes';

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
$formSec    = cmsRow($cms, 'form');
$processSec = cmsRow($cms, 'process');
$mapSec     = cmsRow($cms, 'map');
$ctaSec     = cmsRow($cms, 'cta');
$formMap    = cmsMap($formSec);
$ctaMap     = cmsMap($ctaSec);

$phone    = $settings['phone'] ?? '+252 907 734 667';
$phone2   = $settings['phone_2'] ?? '';
$email    = $settings['email'] ?? 'info@highqhomes.net';
$address  = $copyIf((string)($settings['address'] ?? ''), [
    'Garowe City, Puntland State of Somalia',
    'Garowe, Puntland, Somalia',
], '3CCC, Garowe, Puntland, Somalia');
$hours    = $copyIf((string)($settings['hours'] ?? ''), [
    'Sat–Fri 8AM–9PM',
    'Sat-Fri 8AM–9PM',
    'Sat–Fri 8AM-9PM',
], 'Sat–Thu 8:00 AM – 8:00 PM');
$mapEmbed = trim($settings['map_embed'] ?? '');
$phoneHref  = preg_replace('/\s+/', '', $phone);
$phone2Href = preg_replace('/\s+/', '', $phone2);
$wa         = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $phone);
$waMessage  = rawurlencode($settings['contact_cta_message'] ?? 'Hello HighQ Homes, I would like to discuss a construction project.');
$quoteHref  = 'https://wa.me/' . $wa . '?text=' . $waMessage;

$heroImageDefault = asset('images/builds/stone-residence.jpg');
$heroKicker = $copyIf(cmsText($hero, 'title', ''), ['Contact Us', 'Contact'], 'Contact');
$heroTitle  = $copyIf(cmsText($hero, 'subtitle', ''), [
    "Let's plan your next project",
    'Lets plan your next project',
    'Let’s plan your next project',
], 'Tell us what you want to build.');
$heroLead   = $copyIf(cmsText($hero, 'content', ''), [
    'Share your site details, drawings, or goals — our team will respond with clear next steps.',
], 'A plot, a brief, or a drawing is enough to start. We reply with a clear next step — usually the same working day.');
$heroImage  = cmsMediaUrl($hero['image_url'] ?? '', $heroImageDefault);
if ($heroImage === '' || str_contains($heroImage, 'unsplash.com')) {
    $heroImage = $heroImageDefault;
}

$formKicker = $copyIf(cmsText($formSec, 'title', ''), ['Project inquiry', 'Project Inquiry'], 'Enquiry');
$formTitle  = $copyIf(cmsText($formSec, 'subtitle', ''), ['Send us a message', 'Send Us a Message'], 'Send a message');
$formLead   = $copyIf(cmsText($formSec, 'content', ''), [
    'Tell us about your build, renovation, or design scope. Your message goes directly to our team inbox.',
], 'Name, a way to reach you, and what you want to build. WhatsApp is fastest if you need us today.');

$processKicker = $copyIf(cmsText($processSec, 'title', ''), ['How we respond', 'How We Respond'], 'What happens next');
$processSteps = [
    ['num' => '01', 'title' => 'We read it', 'text' => 'We look at the plot, the brief, and what you need first.'],
    ['num' => '02', 'title' => 'We reply', 'text' => 'WhatsApp, email, or a call — usually the same working day.'],
    ['num' => '03', 'title' => 'Next step', 'text' => 'A site visit or a written quote before any work begins.'],
];
$cmsProcess = cmsList($processSec);
if ($cmsProcess !== []) {
    $titles = array_map(static fn(array $row): string => (string)($row['title'] ?? ''), $cmsProcess);
    $seed = ['Review', 'Clarify', 'Plan'];
    $processSteps = array_intersect($seed, $titles) === $seed ? $processSteps : $cmsProcess;
}

$mapKicker = $copyIf(cmsText($mapSec, 'title', ''), ['Visit us', 'Visit Us'], 'Visit');
$mapTitle  = $copyIf(cmsText($mapSec, 'subtitle', ''), ['Our office', 'Our Office'], 'The office in Garowe');
$mapLead   = $copyIf(cmsText($mapSec, 'content', ''), [$address, '3CCC, Garowe, Puntland, Somalia'], $address);

$ctaKicker = $copyIf(cmsText($ctaSec, 'title', ''), ['Prefer WhatsApp', 'Prefer Whatsapp'], 'WhatsApp');
$ctaTitle  = $copyIf(cmsText($ctaSec, 'subtitle', ''), ['Talk to us now', 'Talk To Us Now'], 'Need a faster first reply?');
$ctaLead   = $copyIf(cmsText($ctaSec, 'content', ''), [
    'Message the team for a faster first response on quotes and site visits.',
], 'Message us on WhatsApp with the plot and the brief. We will come back with the next step.');

$showHero    = cmsRowEnabled($cms, 'hero', true);
$showForm    = cmsRowEnabled($cms, 'form', true);
$showProcess = cmsRowEnabled($cms, 'process', true);
$showMap     = cmsRowEnabled($cms, 'map', true);
$showCta     = cmsRowEnabled($cms, 'cta', true);

$success = Session::getFlash('success');
$error   = Session::getFlash('error');

$subjects = [
    'New home / villa',
    'Architecture & planning',
    'Finishing & interiors',
    'Compound & site works',
    'Renovation',
    'General enquiry',
];

$mapQuery = rawurlencode($address ?: 'Garowe, Puntland, Somalia');
$mapFallback = 'https://www.google.com/maps?q=' . $mapQuery . '&output=embed';

$schemaJson = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'ContactPage',
    'name'     => 'Contact HighQ Homes',
    'url'      => url('contact'),
    'mainEntity' => [
        '@type' => 'LocalBusiness',
        'name'  => $settings['site_name'] ?? 'HighQ Homes',
        'telephone' => $phone,
        'email'     => $email,
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $address,
            'addressLocality' => 'Garowe',
            'addressRegion'   => 'Puntland',
            'addressCountry'  => 'SO',
        ],
        'openingHours' => 'Sa-Th 08:00-20:00',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<script type="application/ld+json"><?= $schemaJson ?></script>

<?php if ($showHero): ?>
<section class="hq-contact-pro-hero hq-contact-pro-hero--cinematic">
  <div class="hq-contact-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($heroImage) ?>" alt="" loading="eager">
  </div>
  <div class="hq-contact-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-contact-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Contact</span>
    </nav>
    <div class="hq-contact-pro-hero__content" data-anim="up">
      <p class="hq-contact-pro-hero__kicker"><?= e($heroKicker) ?></p>
      <h1 class="hq-contact-pro-hero__title"><?= e($heroTitle) ?></h1>
      <p class="hq-contact-pro-hero__lead"><?= e($heroLead) ?></p>
      <div class="hq-contact-pro-hero__actions">
        <a href="#contact-form" class="hq-btn hq-btn--orange hq-btn--lg">Send a message <i class="bi bi-arrow-down"></i></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-whatsapp"></i> WhatsApp</a>
      </div>
      <ul class="hq-contact-pro-hero__chips">
        <li><i class="bi bi-geo-alt-fill"></i> Garowe, Puntland</li>
        <li><i class="bi bi-clock-fill"></i> <?= e($hours) ?></li>
        <li><i class="bi bi-whatsapp"></i> Same-day reply</li>
      </ul>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="hq-contact-pro-main">
  <div class="container-site">
    <div class="hq-contact-pro-layout">
      <div class="hq-contact-pro-form-wrap" id="contact-form">
        <?php if ($showForm): ?>
        <header class="hq-contact-pro-form-head" data-anim="up">
          <p class="hq-contact-pro-eyebrow"><?= e($formKicker) ?></p>
          <h2 class="hq-contact-pro-title"><?= e($formTitle) ?></h2>
          <p class="hq-contact-pro-lead"><?= e($formLead) ?></p>
        </header>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="hq-contact-pro-alert hq-contact-pro-alert--success" data-flash data-anim="up">
          <i class="bi bi-check-circle-fill"></i>
          <span><?= e($success) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="hq-contact-pro-alert hq-contact-pro-alert--error" data-flash data-anim="up">
          <i class="bi bi-exclamation-circle-fill"></i>
          <span><?= e($error) ?></span>
        </div>
        <?php endif; ?>

        <form action="<?= url('contact') ?>" method="POST" class="hq-contact-pro-form hq-form" data-anim="up" data-delay="50">
          <?= csrf() ?>
          <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hq-contact-pro-honeypot" aria-hidden="true">
          <div class="hq-form-grid">
            <label for="contact-name">Full name *
              <input id="contact-name" type="text" name="name" required placeholder="Your name" autocomplete="name">
            </label>
            <label for="contact-email">Email *
              <input id="contact-email" type="email" name="email" required placeholder="name@example.com" autocomplete="email">
            </label>
          </div>
          <div class="hq-form-grid">
            <label for="contact-phone">Phone
              <input id="contact-phone" type="tel" name="phone" placeholder="+252 907 734 667" autocomplete="tel">
            </label>
            <label for="contact-subject">What is this about?
              <select id="contact-subject" name="subject">
                <?php foreach ($subjects as $option): ?>
                <option value="<?= e($option) ?>"><?= e($option) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <label for="contact-message">Message *
            <textarea id="contact-message" name="message" rows="6" required placeholder="Plot location, what you want to build, and any timing we should know."></textarea>
          </label>
          <button type="submit" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-send-fill"></i> <?= e($formMap['submit_label'] ?? 'Send message') ?></button>
          <p class="hq-contact-pro-form__note">We usually reply the same working day. For a faster first reply, use WhatsApp.</p>
        </form>
      </div>

      <aside class="hq-contact-pro-aside" data-anim="right">
        <div class="hq-contact-pro-card">
          <p class="hq-contact-pro-eyebrow">Direct</p>
          <h2 class="hq-contact-pro-card__title">Reach the team</h2>
          <ul class="hq-contact-pro-details">
            <li>
              <span class="hq-contact-pro-details__icon"><i class="bi bi-telephone-fill"></i></span>
              <div>
                <strong>Phone</strong>
                <a href="tel:<?= e($phoneHref) ?>"><?= e($phone) ?></a>
                <?php if ($phone2 !== ''): ?>
                <a href="tel:<?= e($phone2Href) ?>"><?= e($phone2) ?></a>
                <?php endif; ?>
              </div>
            </li>
            <li>
              <span class="hq-contact-pro-details__icon"><i class="bi bi-envelope-fill"></i></span>
              <div>
                <strong>Email</strong>
                <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
              </div>
            </li>
            <li>
              <span class="hq-contact-pro-details__icon"><i class="bi bi-whatsapp"></i></span>
              <div>
                <strong>WhatsApp</strong>
                <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener"><?= e($phone) ?></a>
              </div>
            </li>
            <li>
              <span class="hq-contact-pro-details__icon"><i class="bi bi-geo-alt-fill"></i></span>
              <div>
                <strong>Office</strong>
                <span><?= e($address) ?></span>
              </div>
            </li>
            <li>
              <span class="hq-contact-pro-details__icon"><i class="bi bi-clock-fill"></i></span>
              <div>
                <strong>Hours</strong>
                <span><?= e($hours) ?></span>
              </div>
            </li>
          </ul>

          <?php
            $socials = array_filter([
              'facebook'  => $settings['facebook'] ?? '',
              'instagram' => $settings['instagram'] ?? '',
              'twitter'   => $settings['twitter'] ?? '',
              'linkedin'  => $settings['linkedin'] ?? '',
              'youtube'   => $settings['youtube'] ?? '',
            ]);
          ?>
          <?php if (!empty($socials)): ?>
          <div class="hq-contact-pro-socials">
            <span>Follow</span>
            <div>
              <?php foreach (['facebook'=>'bi-facebook','instagram'=>'bi-instagram','twitter'=>'bi-twitter-x','linkedin'=>'bi-linkedin','youtube'=>'bi-youtube'] as $key => $icon): ?>
              <?php if (!empty($settings[$key])): ?>
              <a href="<?= e($settings[$key]) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($key)) ?>"><i class="bi <?= e($icon) ?>"></i></a>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <?php if ($showProcess && !empty($processSteps)): ?>
        <div class="hq-contact-pro-process">
          <h3><?= e($processKicker) ?></h3>
          <ol>
            <?php foreach ($processSteps as $step): ?>
            <li>
              <span><?= e($step['num'] ?? '') ?></span>
              <div>
                <strong><?= e($step['title'] ?? '') ?></strong>
                <p><?= e($step['text'] ?? $step['body'] ?? '') ?></p>
              </div>
            </li>
            <?php endforeach; ?>
          </ol>
        </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>

<?php if ($showMap): ?>
<section class="hq-contact-pro-map" aria-label="Office location">
  <div class="container-site">
    <header class="hq-contact-pro-map__head" data-anim="up">
      <p class="hq-contact-pro-eyebrow"><?= e($mapKicker) ?></p>
      <h2 class="hq-contact-pro-title"><?= e($mapTitle) ?></h2>
      <p class="hq-contact-pro-lead"><?= e($mapLead) ?></p>
    </header>
    <div class="hq-contact-pro-map__frame" data-anim="up" data-delay="50">
      <?php if ($mapEmbed !== ''): ?>
      <?= $mapEmbed ?>
      <?php else: ?>
      <iframe
        src="<?= e($mapFallback) ?>"
        title="HighQ Homes office in Garowe"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen
      ></iframe>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($showCta): ?>
<section class="hq-contact-pro-cta" aria-labelledby="contact-cta-title">
  <div class="hq-contact-pro-cta__bg" aria-hidden="true"></div>
  <div class="container-site hq-contact-pro-cta__box" data-anim="up">
    <div class="hq-contact-pro-cta__copy">
      <p class="hq-contact-pro-eyebrow hq-contact-pro-eyebrow--light"><?= e($ctaKicker) ?></p>
      <h2 id="contact-cta-title" class="hq-contact-pro-cta__title"><?= e($ctaTitle) ?></h2>
      <p class="hq-contact-pro-cta__lead"><?= e($ctaLead) ?></p>
    </div>
    <div class="hq-contact-pro-cta__actions">
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-whatsapp"></i> <?= e($ctaMap['cta_primary'] ?? 'WhatsApp') ?></a>
      <?php if ($phone !== ''): ?>
      <a href="tel:<?= e($phoneHref) ?>" class="hq-btn hq-btn--ghost hq-btn--lg"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
