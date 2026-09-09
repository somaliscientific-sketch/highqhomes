<?php
$phone    = $settings['phone']   ?? '+252 907 734 667';
$phone2   = $settings['phone_2'] ?? '';
$email    = $settings['email']    ?? 'info@highqhomes.net';
$address  = $settings['address']  ?? '3CCC, Garowe, Puntland, Somalia';
$siteName = $settings['site_name'] ?? APP_NAME;
$copyrightName = trim($settings['legal_name'] ?? '') ?: $siteName;
$tagline  = $settings['tagline'] ?? 'Premium Construction';

// Footer CMS sections
$footerCtaSec     = cmsSection('footer', 'cta');
$footerCtaEnabled = cmsSectionEnabled('footer', 'cta', true);
$footerCtaKicker   = cmsSectionTitle('footer', 'cta', 'Start Your Build');
$footerCtaHeading  = cmsSectionSubtitle('footer', 'cta', 'Ready to create something exceptional?');
$footerCtaBtnText  = cmsSectionContent('footer', 'cta', ($settings['contact_cta_label'] ?? 'Get a Quote'));
$footerCtaData     = is_array($footerCtaSec['data'] ?? null) ? $footerCtaSec['data'] : [];
$footerCtaHref     = menuUrl($footerCtaData['button_link'] ?? url('contact'));

$footerAboutSec    = cmsSection('footer', 'about');
$footerAboutEnabled = cmsSectionEnabled('footer', 'about', true);
$footerAboutText   = cmsSectionContent('footer', 'about', $tagline . ' — premium construction, architecture, and finishing delivered with clarity and care.');

$footerMenus = navMenus('footer');
?>
<footer class="hq-footer">
  <div class="container-site">
    <?php if ($footerCtaEnabled && ($bodyPage ?? '') !== 'contact'): ?>
    <div class="hq-footer__cta">
      <div>
        <p class="hq-eyebrow hq-eyebrow--light"><?= e($footerCtaKicker) ?></p>
        <h2><?= e($footerCtaHeading) ?></h2>
      </div>
      <a href="<?= e($footerCtaHref) ?>" class="hq-btn hq-btn--orange hq-btn--lg"><?= e($footerCtaBtnText) ?></a>
    </div>
    <?php endif; ?>

    <div class="hq-footer__grid">
      <div class="hq-footer__brand">
        <?php View::partial('brand-logo', compact('siteName', 'settings') + ['variant' => 'footer', 'href' => url()]); ?>
        <?php if ($footerAboutEnabled): ?>
        <p><?= e($footerAboutText) ?></p>
        <?php endif; ?>
        <div class="hq-socials">
          <?php foreach (['facebook'=>'bi-facebook','instagram'=>'bi-instagram','twitter'=>'bi-twitter-x','linkedin'=>'bi-linkedin','youtube'=>'bi-youtube'] as $key => $icon): ?>
          <?php if (!empty($settings[$key])): ?>
          <a href="<?= e($settings[$key]) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($key)) ?>"><i class="bi <?= e($icon) ?>"></i></a>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <h3>Explore</h3>
        <div class="hq-footer__links">
          <?php foreach ($footerMenus as $menu): ?>
          <a href="<?= e(menuUrl($menu['url'])) ?>" target="<?= e($menu['target'] ?? '_self') ?>"><?= e($menu['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <h3>Contact</h3>
        <div class="hq-footer__contact">
          <a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><i class="bi bi-telephone-fill"></i><?= e($phone) ?></a>
          <?php if ($phone2): ?>
          <a href="tel:<?= e(preg_replace('/\s+/', '', $phone2)) ?>"><i class="bi bi-telephone-fill"></i><?= e($phone2) ?></a>
          <?php endif; ?>
          <a href="mailto:<?= e($email) ?>"><i class="bi bi-envelope-fill"></i><?= e($email) ?></a>
          <span><i class="bi bi-geo-alt-fill"></i><?= e($address) ?></span>
        </div>
      </div>
    </div>

    <div class="hq-footer__bottom">
      <span>&copy; <?= date('Y') ?> <?= e($copyrightName) ?>. All rights reserved.</span>
      <span>Built with precision. Delivered with integrity.</span>
    </div>
  </div>
</footer>
