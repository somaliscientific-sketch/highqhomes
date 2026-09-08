<?php
$bodyPage  = 'contact';
$pageTitle = $seo['meta_title'] ?? 'Contact Us — HighQ Homes';

$cms      = $sections ?? [];
$hero     = $cms['hero'] ?? [];
$phone    = $settings['phone'] ?? '+252 907 734 667';
$phone2   = $settings['phone_2'] ?? '';
$email    = $settings['email'] ?? 'info@highqhomes.net';
$address  = $settings['address'] ?? '3CCC, Garowe, Puntland, Somalia';
$hours    = $settings['hours'] ?? 'Sat–Fri 8AM–9PM';
$mapEmbed = trim($settings['map_embed'] ?? '');
$phoneHref  = preg_replace('/\s+/', '', $phone);
$phone2Href = preg_replace('/\s+/', '', $phone2);
$wa         = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $phone);
$waMessage  = rawurlencode($settings['contact_cta_message'] ?? 'Hello HighQ Homes, I would like to discuss a construction project.');
$quoteHref  = 'https://wa.me/' . $wa . '?text=' . $waMessage;

$heroKicker = $hero['title'] ?? 'Contact Us';
$heroTitle  = $hero['subtitle'] ?? 'Let\'s plan your next project';
$heroLead   = $hero['content'] ?? 'Share your site details, drawings, or goals — our team will respond with clear next steps.';
$heroImage  = !empty($hero['image_url']) ? (str_starts_with($hero['image_url'], 'http') ? $hero['image_url'] : uploadUrl($hero['image_url'])) : 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1920&q=80';

$processSteps = [
    ['num' => '01', 'title' => 'Review', 'text' => 'We assess project type, location, and scope.'],
    ['num' => '02', 'title' => 'Clarify', 'text' => 'We follow up for drawings or key site details.'],
    ['num' => '03', 'title' => 'Plan', 'text' => 'You receive the next step for design or estimate.'],
];

$success = Session::getFlash('success');
$error   = Session::getFlash('error');

$mapQuery = rawurlencode($address ?: 'Garowe, Puntland, Somalia');
$mapFallback = 'https://www.google.com/maps?q=' . $mapQuery . '&output=embed';
?>

<section class="hq-contact-pro-hero">
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
    </div>
  </div>
</section>

<section class="hq-contact-pro-main">
  <div class="container-site">
    <div class="hq-contact-pro-layout">
      <div class="hq-contact-pro-form-wrap" id="contact-form">
        <header class="hq-contact-pro-form-head" data-anim="up">
          <p class="hq-contact-pro-eyebrow">Project inquiry</p>
          <h2 class="hq-contact-pro-title">Send us a message</h2>
          <p class="hq-contact-pro-lead">Tell us about your build, renovation, or design scope. Your message goes directly to our team inbox.</p>
        </header>

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
            <label>Full name *
              <input type="text" name="name" required placeholder="Your full name" autocomplete="name">
            </label>
            <label>Email *
              <input type="email" name="email" required placeholder="name@example.com" autocomplete="email">
            </label>
          </div>
          <div class="hq-form-grid">
            <label>Phone
              <input type="tel" name="phone" placeholder="+252 ..." autocomplete="tel">
            </label>
            <label>Subject
              <input type="text" name="subject" placeholder="Project consultation" value="Project Consultation">
            </label>
          </div>
          <label>Message *
            <textarea name="message" rows="6" required placeholder="Tell us about your project — location, scope, timeline, and any drawings you have..."></textarea>
          </label>
          <button type="submit" class="hq-btn hq-btn--orange hq-btn--lg"><i class="bi bi-send-fill"></i> Send message</button>
        </form>
      </div>

      <aside class="hq-contact-pro-aside" data-anim="right">
        <div class="hq-contact-pro-card">
          <p class="hq-contact-pro-eyebrow">Direct contact</p>
          <h2 class="hq-contact-pro-card__title">Reach our team</h2>
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
                <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener">Fast quotes &amp; updates</a>
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
            <span>Follow us</span>
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

        <div class="hq-contact-pro-process">
          <h3>How we respond</h3>
          <ol>
            <?php foreach ($processSteps as $step): ?>
            <li>
              <span><?= e($step['num']) ?></span>
              <div>
                <strong><?= e($step['title']) ?></strong>
                <p><?= e($step['text']) ?></p>
              </div>
            </li>
            <?php endforeach; ?>
          </ol>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="hq-contact-pro-map" aria-label="Office location">
  <div class="container-site">
    <header class="hq-contact-pro-map__head" data-anim="up">
      <p class="hq-contact-pro-eyebrow">Visit us</p>
      <h2 class="hq-contact-pro-title">Our office</h2>
      <p class="hq-contact-pro-lead"><?= e($address) ?></p>
    </header>
    <div class="hq-contact-pro-map__frame" data-anim="up" data-delay="50">
      <?php if ($mapEmbed !== ''): ?>
      <?= $mapEmbed ?>
      <?php else: ?>
      <iframe
        src="<?= e($mapFallback) ?>"
        title="HighQ Homes office location"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen
      ></iframe>
      <?php endif; ?>
    </div>
  </div>
</section>
