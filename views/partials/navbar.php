<?php
$siteName     = $settings['site_name'] ?? APP_NAME;
$primaryMenus = navMenus('primary');

$topbarSec     = cmsSection('header', 'topbar');
$topbarEnabled = cmsSectionEnabled('header', 'topbar', true);
$topbarData    = is_array($topbarSec['data'] ?? null) ? $topbarSec['data'] : [];

$phone         = $topbarData['phone'] ?? ($settings['phone'] ?? '+252 907 734 667');
$email         = $settings['email'] ?? 'info@highqhomes.net';
$hours         = $topbarData['hours'] ?? ($settings['hours'] ?? 'Sat–Thu 8:00 AM – 8:00 PM');
$location      = $topbarData['location'] ?? ($settings['address'] ?? 'Garowe, Puntland, Somalia');
$phoneHref     = preg_replace('/\s+/', '', $phone);
$waNum         = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $phone);
$quoteHref     = 'https://wa.me/' . $waNum . '?text=Hello%20HighQ%20Homes,%20I%20would%20like%20to%20discuss%20a%20project';

$headerCtaSec     = cmsSection('header', 'cta');
$headerCtaEnabled = cmsSectionEnabled('header', 'cta', true);
$headerCtaLabel   = cmsSectionTitle('header', 'cta', 'Get a Free Quote');
$headerCtaHref    = menuUrl(cmsSectionSubtitle('header', 'cta', '/contact'));
$ctaIsExternal    = str_starts_with($headerCtaHref, 'http');
$ctaIsWhatsApp    = str_contains($headerCtaHref, 'wa.me') || str_contains(strtolower($headerCtaHref), 'whatsapp');
$ctaIcon          = $ctaIsWhatsApp ? 'bi-whatsapp' : 'bi-chat-dots-fill';
?>

<div class="hq-header-wrapper" id="main-header-wrapper">
  <?php if ($topbarEnabled): ?>
  <div class="hq-topbar" id="hq-topbar" aria-label="Company contact">
    <div class="container-site hq-topbar__inner">
      <div class="hq-topbar__start">
        <span class="hq-topbar__item"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?= e($location) ?></span>
        <span class="hq-topbar__item hq-topbar__item--hide-sm"><i class="bi bi-clock-fill" aria-hidden="true"></i> <?= e($hours) ?></span>
      </div>
      <div class="hq-topbar__end">
        <a href="tel:<?= e($phoneHref) ?>" class="hq-topbar__link"><i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= e($phone) ?></a>
        <a href="mailto:<?= e($email) ?>" class="hq-topbar__link hq-topbar__item--hide-md"><i class="bi bi-envelope-fill" aria-hidden="true"></i> <?= e($email) ?></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-topbar__link hq-topbar__link--highlight">
          <i class="bi bi-whatsapp" aria-hidden="true"></i> <span>WhatsApp Us</span>
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <header id="main-navbar" class="hq-header">
    <div class="container-site hq-header__inner">
      <?php View::partial('brand-logo', compact('siteName', 'settings') + ['variant' => 'header', 'href' => url()]); ?>

      <nav class="hq-nav" aria-label="Primary">
        <?php foreach ($primaryMenus as $menu): ?>
        <?php
          $href = menuUrl($menu['url']);
          $isActive = active(parse_url($menu['url'], PHP_URL_PATH) ?: '/');
        ?>
        <a href="<?= e($href) ?>" target="<?= e($menu['target'] ?? '_self') ?>" class="hq-nav__link<?= $isActive ? ' is-active' : '' ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
          <span><?= e($menu['label']) ?></span>
        </a>
        <?php endforeach; ?>
      </nav>

      <div class="hq-header__actions">
        <?php if ($headerCtaEnabled): ?>
        <a
          href="<?= e($headerCtaHref) ?>"
          class="hq-header__cta"
          <?= $ctaIsExternal ? 'target="_blank" rel="noopener"' : '' ?>
          aria-label="<?= e($headerCtaLabel) ?>"
        >
          <i class="bi <?= e($ctaIcon) ?>" aria-hidden="true"></i>
          <span class="hq-header__cta-text"><?= e($headerCtaLabel) ?></span>
          <i class="bi bi-arrow-right hq-header__cta-arrow" aria-hidden="true"></i>
        </a>
        <?php endif; ?>

        <button type="button" id="mobile-menu-btn" class="hq-menu-btn" aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false" aria-haspopup="dialog">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </header>
</div>

<div id="mobile-menu" class="hq-mobile" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Site menu" inert>
  <button type="button" class="hq-mobile__backdrop hq-mobile-backdrop" tabindex="-1" aria-label="Close menu"></button>
  <aside class="hq-mobile__panel">
    <div class="hq-mobile__head">
      <?php View::partial('brand-logo', compact('siteName', 'settings') + ['variant' => 'mobile', 'href' => url()]); ?>
      <button type="button" id="mobile-menu-close" class="hq-mobile__close" aria-label="Close menu"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
    </div>
    <p class="hq-mobile__kicker">Navigate</p>
    <nav class="hq-mobile__links" aria-label="Mobile">
      <?php foreach ($primaryMenus as $menu): ?>
      <?php
        $href = menuUrl($menu['url']);
        $isActive = active(parse_url($menu['url'], PHP_URL_PATH) ?: '/');
      ?>
      <a href="<?= e($href) ?>" target="<?= e($menu['target'] ?? '_self') ?>" class="hq-mobile__link<?= $isActive ? ' is-active' : '' ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
        <span><?= e($menu['label']) ?></span><i class="bi bi-arrow-right" aria-hidden="true"></i>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="hq-mobile__footer">
      <?php if ($headerCtaEnabled): ?>
      <a href="<?= e($headerCtaHref) ?>" class="hq-btn hq-btn--orange hq-btn--block" <?= $ctaIsExternal ? 'target="_blank" rel="noopener"' : '' ?>>
        <i class="bi <?= e($ctaIcon) ?>" aria-hidden="true"></i> <?= e($headerCtaLabel) ?>
      </a>
      <?php endif; ?>
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--block">
        <i class="bi bi-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
      </a>
      <div class="hq-mobile__contact-info">
        <a href="tel:<?= e($phoneHref) ?>"><i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= e($phone) ?></a>
        <a href="mailto:<?= e($email) ?>"><i class="bi bi-envelope-fill" aria-hidden="true"></i> <?= e($email) ?></a>
        <span><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?= e($location) ?></span>
      </div>
    </div>
  </aside>
</div>
