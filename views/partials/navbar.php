<?php
$siteName     = $settings['site_name'] ?? APP_NAME;
$primaryMenus = navMenus('primary');
?>

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

    <button id="mobile-menu-btn" class="hq-menu-btn" aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

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
  </aside>
</div>
