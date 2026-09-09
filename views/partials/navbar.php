<?php
$siteName     = $settings['site_name'] ?? APP_NAME;
$primaryMenus = navMenus('primary');

// Header CMS controls
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
?>

<div class="hq-header-wrapper" id="main-header-wrapper">
  <?php if ($topbarEnabled): ?>
  <div class="hq-topbar" id="hq-topbar" aria-label="Company announcements and contact info">
    <div class="container-site hq-topbar__inner">
      <div class="hq-topbar__start">
        <span class="hq-topbar__item"><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></span>
        <span class="hq-topbar__item hq-topbar__item--hide-sm"><i class="bi bi-clock-fill"></i> <?= e($hours) ?></span>
      </div>
      <div class="hq-topbar__end">
        <a href="tel:<?= e($phoneHref) ?>" class="hq-topbar__link"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
        <a href="mailto:<?= e($email) ?>" class="hq-topbar__link hq-topbar__item--hide-md"><i class="bi bi-envelope-fill"></i> <?= e($email) ?></a>
        <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-topbar__link hq-topbar__link--highlight">
          <i class="bi bi-whatsapp"></i> <span>WhatsApp Us</span>
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <header id="main-navbar" class="hq-header" aria-label="Primary navigation">
    <div class="container-site hq-header__inner">
      <?php View::partial('brand-logo', compact('siteName', 'settings') + ['variant' => 'header', 'href' => url()]); ?>

      <nav class="hq-nav" aria-label="Main menu">
        <?php foreach ($primaryMenus as $menu): ?>
        <?php
          $href = menuUrl($menu['url']);
          $isActive = active(parse_url($menu['url'], PHP_URL_PATH) ?: '/');
        ?>
        <a href="<?= e($href) ?>" target="<?= e($menu['target'] ?? '_self') ?>" class="hq-nav__link <?= $isActive ?>">
          <?= e($menu['label']) ?>
        </a>
        <?php endforeach; ?>
      </nav>

      <div class="hq-header__actions">
        <?php if ($headerCtaEnabled): ?>
        <a href="<?= e($headerCtaHref) ?>" class="hq-btn hq-btn--orange hq-header__cta">
          <i class="bi bi-chat-dots-fill"></i> <span><?= e($headerCtaLabel) ?></span>
        </a>
        <?php endif; ?>

        <button type="button" id="mobile-menu-btn" class="hq-menu-btn" aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </header>
</div>

<div id="mobile-menu" class="hq-mobile" role="dialog" aria-modal="true" aria-label="Mobile navigation">
  <button type="button" class="hq-mobile__backdrop hq-mobile-backdrop" aria-label="Close menu"></button>
  <aside class="hq-mobile__panel">
    <div class="hq-mobile__head">
      <?php View::partial('brand-logo', compact('siteName', 'settings') + ['variant' => 'mobile', 'href' => url()]); ?>
      <button id="mobile-menu-close" class="hq-mobile__close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="hq-mobile__links" aria-label="Mobile menu">
      <?php foreach ($primaryMenus as $menu): ?>
      <a href="<?= e(menuUrl($menu['url'])) ?>" target="<?= e($menu['target'] ?? '_self') ?>">
        <span><?= e($menu['label']) ?></span><i class="bi bi-arrow-right"></i>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="hq-mobile__footer">
      <?php if ($headerCtaEnabled): ?>
      <a href="<?= e($headerCtaHref) ?>" class="hq-btn hq-btn--orange hq-btn--block">
        <i class="bi bi-chat-dots-fill"></i> <?= e($headerCtaLabel) ?>
      </a>
      <?php endif; ?>
      <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost hq-btn--block">
        <i class="bi bi-whatsapp"></i> Chat on WhatsApp
      </a>
      <div class="hq-mobile__contact-info">
        <a href="tel:<?= e($phoneHref) ?>"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
        <a href="mailto:<?= e($email) ?>"><i class="bi bi-envelope-fill"></i> <?= e($email) ?></a>
        <span><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></span>
      </div>
    </div>
  </aside>
</div>
