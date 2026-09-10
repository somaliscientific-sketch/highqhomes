<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?= e($pageTitle ?? 'Admin') ?> — <?= e(setting('site_name', 'HighQ Homes')) ?> CMS</title>
  <link rel="icon" href="<?= e(faviconHref([])) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=<?= @filemtime(PUBLIC_PATH . '/css/admin.css') ?: time() ?>">
  <?php $themeStyle = brandThemeStyle([]); if ($themeStyle !== ''): ?>
  <style><?= $themeStyle ?></style>
  <?php endif; ?>
</head>
<body class="admin-body admin-pro">
<?php $user = Auth::user(); ?>

<div id="admin-overlay"></div>

<aside id="admin-sidebar" class="admin-sidebar">
  <div class="admin-sidebar-brand">
    <div class="admin-sidebar-logo admin-sidebar-logo--img">
      <img src="<?= e(cmsLogoUrl()) ?>" alt="<?= e(setting('site_name', 'HighQ Homes')) ?>" class="admin-cms-logo admin-cms-logo--sidebar" width="36" height="36">
    </div>
    <div class="sidebar-label">
      <div class="admin-sidebar-title"><?= e(setting('site_name', 'HighQ Homes')) ?></div>
      <div class="admin-sidebar-subtitle">Content Management</div>
    </div>
    <button type="button" class="admin-sidebar-pin" data-admin-sidebar-collapse aria-label="Collapse sidebar" title="Collapse sidebar">
      <i class="bi bi-chevron-left"></i>
    </button>
  </div>

  <nav class="admin-sidebar-nav">
    <label class="admin-sidebar-search sidebar-label">
      <i class="bi bi-search" aria-hidden="true"></i>
      <input id="admin-nav-filter" type="search" placeholder="Find a page..." autocomplete="off">
    </label>
    <div class="admin-nav-section">
      <p class="admin-nav-label sidebar-label">Main</p>
      <a href="<?= url('admin/dashboard') ?>" class="admin-nav-link <?= active('/admin/dashboard', true) ?: active('/admin', true) ?>">
        <i class="bi bi-speedometer2"></i><span class="sidebar-label">Dashboard</span>
      </a>
    </div>

    <div class="admin-nav-section">
      <p class="admin-nav-label sidebar-label">Website</p>
      <?php if (Auth::can('content.view') || Auth::can('content.manage')): ?>
      <a href="<?= url('admin/sliders') ?>" class="admin-nav-link <?= active('/admin/sliders') ?>"><i class="bi bi-images"></i><span class="sidebar-label">Hero Slider</span></a>
      <a href="<?= url('admin/services') ?>" class="admin-nav-link <?= active('/admin/services') ?>"><i class="bi bi-grid-3x3-gap"></i><span class="sidebar-label">Services</span></a>
      <a href="<?= url('admin/projects') ?>" class="admin-nav-link <?= active('/admin/projects') ?>"><i class="bi bi-building"></i><span class="sidebar-label">Projects</span></a>
      <a href="<?= url('admin/gallery') ?>" class="admin-nav-link <?= active('/admin/gallery') ?>"><i class="bi bi-image"></i><span class="sidebar-label">Gallery</span></a>
      <a href="<?= url('admin/pages') ?>" class="admin-nav-link <?= active('/admin/pages') ?>"><i class="bi bi-file-earmark-text"></i><span class="sidebar-label">Pages</span></a>
      <?php if (Auth::can('sections.manage')): ?>
      <a href="<?= url('admin/sections') ?>" class="admin-nav-link <?= active('/admin/sections') ?>"><i class="bi bi-layout-text-window-reverse"></i><span class="sidebar-label">Page Sections</span></a>
      <?php endif; ?>
      <a href="<?= url('admin/paints') ?>" class="admin-nav-link <?= active('/admin/paints') ?>"><i class="bi bi-palette"></i><span class="sidebar-label">Paints</span></a>
      <?php endif; ?>
      <?php if (Auth::can('media.manage')): ?>
      <a href="<?= url('admin/media') ?>" class="admin-nav-link <?= active('/admin/media') ?>"><i class="bi bi-folder2-open"></i><span class="sidebar-label">Media Library</span></a>
      <?php endif; ?>
    </div>

    <div class="admin-nav-section">
      <p class="admin-nav-label sidebar-label">Engagement</p>
      <?php if (Auth::can('content.view') || Auth::can('content.manage')): ?>
      <a href="<?= url('admin/team') ?>" class="admin-nav-link <?= active('/admin/team') ?>"><i class="bi bi-people"></i><span class="sidebar-label">Team</span></a>
      <a href="<?= url('admin/testimonials') ?>" class="admin-nav-link <?= active('/admin/testimonials') ?>"><i class="bi bi-chat-quote"></i><span class="sidebar-label">Testimonials</span></a>
      <?php endif; ?>
      <?php if (Auth::can('messages.view')): ?>
      <a href="<?= url('admin/messages') ?>" class="admin-nav-link <?= active('/admin/messages') ?>">
        <i class="bi bi-envelope"></i><span class="sidebar-label">Messages</span>
        <?php try { $uc = (new MessageModel())->getUnreadCount(); if ($uc > 0): ?><span class="admin-nav-badge"><?= $uc ?></span><?php endif; } catch (\Throwable $e) {} ?>
      </a>
      <?php endif; ?>
    </div>

    <div class="admin-nav-section">
      <p class="admin-nav-label sidebar-label">Administration</p>
      <?php if (Auth::can('settings.manage')): ?>
      <a href="<?= url('admin/identity') ?>" class="admin-nav-link <?= active('/admin/identity') ?>"><i class="bi bi-palette2"></i><span class="sidebar-label">Brand &amp; Identity</span></a>
      <a href="<?= url('admin/settings') ?>" class="admin-nav-link <?= active('/admin/settings') ?>"><i class="bi bi-sliders"></i><span class="sidebar-label">Website Settings</span></a>
      <?php endif; ?>
      <?php if (Auth::can('security.manage')): ?>
      <a href="<?= url('admin/security') ?>" class="admin-nav-link <?= active('/admin/security') ?>"><i class="bi bi-shield-lock"></i><span class="sidebar-label">Security</span></a>
      <?php endif; ?>
      <?php if (Auth::can('logs.view')): ?>
      <a href="<?= url('admin/logs') ?>" class="admin-nav-link <?= active('/admin/logs') ?>"><i class="bi bi-journal-text"></i><span class="sidebar-label">Activity Logs</span></a>
      <?php endif; ?>
      <?php if (Auth::can('users.manage')): ?>
      <a href="<?= url('admin/users') ?>" class="admin-nav-link <?= active('/admin/users') ?>"><i class="bi bi-people"></i><span class="sidebar-label">Users</span></a>
      <?php endif; ?>
      <?php if (Auth::isSuperAdmin() || Auth::can('users.manage')): ?>
      <a href="<?= url('admin/roles') ?>" class="admin-nav-link <?= active('/admin/roles') ?>"><i class="bi bi-key"></i><span class="sidebar-label">Roles</span></a>
      <?php endif; ?>
      <?php if (Auth::can('menus.manage')): ?>
      <a href="<?= url('admin/menus') ?>" class="admin-nav-link <?= active('/admin/menus') ?>"><i class="bi bi-list-nested"></i><span class="sidebar-label">Menus</span></a>
      <?php endif; ?>
      <?php if (Auth::can('seo.manage')): ?>
      <a href="<?= url('admin/seo') ?>" class="admin-nav-link <?= active('/admin/seo') ?>"><i class="bi bi-search"></i><span class="sidebar-label">SEO</span></a>
      <?php endif; ?>
    </div>
  </nav>

</aside>

<div class="admin-main">
  <header class="admin-topbar">
    <div class="admin-topbar-start">
      <button id="admin-sidebar-toggle" class="admin-topbar-toggle" aria-label="Toggle sidebar" aria-expanded="true" aria-controls="admin-sidebar">
        <i class="bi bi-layout-sidebar-inset"></i>
      </button>
      <img src="<?= e(cmsLogoUrl()) ?>" alt="" class="admin-topbar-logo" width="32" height="32">
      <div class="admin-topbar-heading">
        <p class="admin-topbar-kicker"><?= e(setting('site_name', 'HighQ Homes')) ?> · CMS</p>
        <h1 class="admin-topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></h1>
      </div>
    </div>
    <div class="admin-topbar-end">
      <button type="button" class="admin-command-trigger" data-admin-command-open aria-haspopup="dialog" aria-controls="admin-command">
        <i class="bi bi-search" aria-hidden="true"></i>
        <span>Jump to page or action</span>
        <kbd>Ctrl</kbd><kbd>K</kbd>
      </button>
      <span class="admin-topbar-secure"><i class="bi bi-shield-check"></i> Secure session</span>
      <div class="admin-user-menu" data-user-menu>
        <button type="button" class="admin-user-menu__toggle admin-topbar-user" aria-expanded="false" aria-haspopup="true" aria-controls="admin-user-menu-panel">
          <span class="admin-topbar-user__avatar"><?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?></span>
          <span class="admin-topbar-user__meta">
            <strong><?= e($user['name'] ?? 'Admin') ?></strong>
            <em><?= e(Auth::roleLabel()) ?></em>
          </span>
          <i class="bi bi-chevron-down admin-user-menu__chevron"></i>
        </button>
        <div id="admin-user-menu-panel" class="admin-user-menu__panel" hidden>
          <div class="admin-user-menu__head">
            <strong><?= e($user['name'] ?? 'Admin') ?></strong>
            <span><?= e($user['email'] ?? '') ?></span>
            <em><?= e(Auth::roleLabel()) ?></em>
          </div>
          <div class="admin-user-menu__links">
            <a href="<?= url('admin/profile') ?>" class="admin-user-menu__item"><i class="bi bi-person-gear"></i> My account</a>
            <a href="<?= url() ?>" target="_blank" rel="noopener" class="admin-user-menu__item"><i class="bi bi-box-arrow-up-right"></i> View website</a>
          </div>
          <form action="<?= url('admin/logout') ?>" method="POST" class="admin-user-menu__logout">
            <?= csrf() ?>
            <button type="submit" class="admin-user-menu__item admin-user-menu__item--danger"><i class="bi bi-box-arrow-right"></i> Sign out</button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <?php View::partial('flash') ?>

  <div class="admin-content">
    <?= $content ?>
  </div>
</div>

<div id="admin-command" class="admin-command" hidden>
  <button type="button" class="admin-command__backdrop" data-admin-command-close aria-label="Close search"></button>
  <div class="admin-command__dialog" role="dialog" aria-modal="true" aria-labelledby="admin-command-title">
    <p id="admin-command-title" class="admin-command__title">Jump to anything</p>
    <label class="admin-command__field">
      <i class="bi bi-search" aria-hidden="true"></i>
      <input id="admin-command-input" type="search" placeholder="Search pages, sections, settings..." autocomplete="off">
    </label>
    <p class="admin-command__hint">Use the keyboard. Press Enter to open. Esc to close.</p>
    <ul id="admin-command-list" class="admin-command__list"></ul>
  </div>
</div>

<script type="application/json" id="admin-command-extras"><?= json_encode([
  ['label' => 'Edit hero slider', 'href' => url('admin/sliders'), 'group' => 'Content'],
  ['label' => 'Edit homepage sections', 'href' => url('admin/sections?page=home'), 'group' => 'Content'],
  ['label' => 'Edit about page sections', 'href' => url('admin/sections?page=about'), 'group' => 'Content'],
  ['label' => 'Edit services page sections', 'href' => url('admin/sections?page=services'), 'group' => 'Content'],
  ['label' => 'Edit projects page sections', 'href' => url('admin/sections?page=projects'), 'group' => 'Content'],
  ['label' => 'Edit gallery page sections', 'href' => url('admin/sections?page=gallery'), 'group' => 'Content'],
  ['label' => 'Edit paints page sections', 'href' => url('admin/sections?page=paints'), 'group' => 'Content'],
  ['label' => 'Edit contact page sections', 'href' => url('admin/sections?page=contact'), 'group' => 'Content'],
  ['label' => 'Edit header & top bar', 'href' => url('admin/sections?page=header'), 'group' => 'Content'],
  ['label' => 'Edit footer', 'href' => url('admin/sections?page=footer'), 'group' => 'Content'],
  ['label' => 'Preview website', 'href' => url(), 'group' => 'Actions', 'blank' => true],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
</body>
</html>
