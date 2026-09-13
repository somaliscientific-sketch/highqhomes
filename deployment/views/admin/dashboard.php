<?php
$pageTitle = 'Dashboard';
$user = Auth::user();
$hour = (int)date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

$workspaces = [
    [
        'title' => 'Build the website',
        'description' => 'Homepage, core content, pages and visual media.',
        'icon' => 'bi-window-stack',
        'permission' => 'content.view',
        'links' => [
            ['Homepage sections', 'admin/sections?page=home', 'bi-layout-text-window-reverse', 'sections.manage'],
            ['About page sections', 'admin/sections?page=about', 'bi-info-circle', 'sections.manage'],
            ['Services page sections', 'admin/sections?page=services', 'bi-grid-3x3-gap', 'sections.manage'],
            ['Projects page sections', 'admin/sections?page=projects', 'bi-building', 'sections.manage'],
            ['Gallery page sections', 'admin/sections?page=gallery', 'bi-images', 'sections.manage'],
            ['Paints page sections', 'admin/sections?page=paints', 'bi-palette', 'sections.manage'],
            ['Contact page sections', 'admin/sections?page=contact', 'bi-telephone', 'sections.manage'],
            ['Homepage Hero', 'admin/sliders', 'bi-images'],
            ['Services', 'admin/services', 'bi-grid'],
            ['Projects', 'admin/projects', 'bi-buildings'],
            ['Pages & Sections', 'admin/pages', 'bi-file-earmark-text'],
            ['Media Library', 'admin/media', 'bi-folder2-open', 'media.manage'],
        ],
    ],
    [
        'title' => 'Connect with visitors',
        'description' => 'Customer enquiries, proof of work and company people.',
        'icon' => 'bi-chat-square-heart',
        'permission' => 'messages.view',
        'links' => [
            ['Contact Messages', 'admin/messages', 'bi-envelope'],
            ['Gallery', 'admin/gallery', 'bi-image'],
            ['Testimonials', 'admin/testimonials', 'bi-chat-quote'],
            ['Team', 'admin/team', 'bi-people'],
        ],
    ],
    [
        'title' => 'Configure the website',
        'description' => 'Brand, navigation, search visibility and global settings.',
        'icon' => 'bi-sliders',
        'permission' => 'settings.manage',
        'links' => [
            ['Brand & Identity', 'admin/identity', 'bi-palette2'],
            ['Website Settings', 'admin/settings', 'bi-gear'],
            ['Navigation Menus', 'admin/menus', 'bi-list-nested', 'menus.manage'],
            ['SEO Manager', 'admin/seo', 'bi-search', 'seo.manage'],
        ],
    ],
    [
        'title' => 'Manage access',
        'description' => 'Accounts, permissions, security policy and audit history.',
        'icon' => 'bi-shield-check',
        'permission' => 'users.manage',
        'links' => [
            ['Users & Roles', 'admin/users', 'bi-person-lock'],
            ['Security', 'admin/security', 'bi-shield-lock', 'security.manage'],
            ['Activity Logs', 'admin/logs', 'bi-journal-text', 'logs.view'],
            ['My Account', 'admin/profile', 'bi-person-gear'],
        ],
    ],
];
?>

<section class="admin-report-hero admin-report-hero--v2 admin-report-hero--pro">
  <div class="admin-report-hero__main">
    <img src="<?= e(cmsLogoUrl()) ?>" alt="" class="admin-cms-logo admin-cms-logo--dashboard" width="56" height="56">
    <div>
      <span class="admin-report-kicker"><?= e($greeting) ?>, <?= e($user['name'] ?? 'Admin') ?></span>
      <h2>Your website control center</h2>
      <p>Update content, respond to enquiries, and manage the website from one place.</p>
    </div>
  </div>
  <div class="admin-report-pills">
    <span class="admin-report-pill admin-report-pill--secure"><i class="bi bi-shield-check"></i> <?= e(Auth::roleLabel()) ?></span>
    <span class="admin-report-pill"><i class="bi bi-envelope"></i> <?= (int)$stats['unread'] ?> unread</span>
    <a href="<?= url() ?>" target="_blank" rel="noopener" class="admin-report-pill"><i class="bi bi-box-arrow-up-right"></i> View website</a>
  </div>
</section>

<section class="admin-overview" aria-label="Website overview">
  <?php if (Auth::can('messages.view')): ?>
  <a href="<?= url('admin/messages?filter=unread') ?>" class="admin-overview-card admin-overview-card--attention">
    <span class="admin-overview-card__icon"><i class="bi bi-envelope-exclamation"></i></span>
    <span class="admin-overview-card__body"><strong><?= (int)$stats['unread'] ?></strong><span>Unread enquiries</span></span>
    <i class="bi bi-arrow-up-right admin-overview-card__arrow"></i>
  </a>
  <?php endif; ?>
  <?php if (Auth::can('content.view')): ?>
  <a href="<?= url('admin/projects') ?>" class="admin-overview-card">
    <span class="admin-overview-card__icon"><i class="bi bi-buildings"></i></span>
    <span class="admin-overview-card__body"><strong><?= (int)$stats['projects'] ?></strong><span>Projects in portfolio</span></span>
    <i class="bi bi-arrow-up-right admin-overview-card__arrow"></i>
  </a>
  <a href="<?= url('admin/services') ?>" class="admin-overview-card">
    <span class="admin-overview-card__icon"><i class="bi bi-grid"></i></span>
    <span class="admin-overview-card__body"><strong><?= (int)$stats['services'] ?></strong><span>Active services</span></span>
    <i class="bi bi-arrow-up-right admin-overview-card__arrow"></i>
  </a>
  <?php endif; ?>
  <?php if (Auth::can('media.manage')): ?>
  <a href="<?= url('admin/media') ?>" class="admin-overview-card">
    <span class="admin-overview-card__icon"><i class="bi bi-images"></i></span>
    <span class="admin-overview-card__body"><strong><?= (int)$stats['media'] ?></strong><span>Files in media library</span></span>
    <i class="bi bi-arrow-up-right admin-overview-card__arrow"></i>
  </a>
  <?php endif; ?>
</section>

<div class="admin-quick-strip admin-quick-strip--pro">
  <?php if (Auth::can('sections.manage')): ?>
  <a href="<?= url('admin/sections?page=home') ?>" class="admin-quick-strip__item admin-quick-strip__item--primary"><i class="bi bi-layout-text-window-reverse"></i> Edit homepage</a>
  <a href="<?= url('admin/sections?page=about') ?>" class="admin-quick-strip__item"><i class="bi bi-info-circle"></i> Edit about page</a>
  <a href="<?= url('admin/sections?page=services') ?>" class="admin-quick-strip__item"><i class="bi bi-grid-3x3-gap"></i> Edit services</a>
  <a href="<?= url('admin/sections?page=contact') ?>" class="admin-quick-strip__item"><i class="bi bi-telephone"></i> Edit contact</a>
  <a href="<?= url('admin/sections?page=header') ?>" class="admin-quick-strip__item"><i class="bi bi-window-dock"></i> Header &amp; top bar</a>
  <?php endif; ?>
  <?php if (Auth::can('content.manage')): ?>
  <a href="<?= url('admin/projects/create') ?>" class="admin-quick-strip__item"><i class="bi bi-plus-circle"></i> New project</a>
  <?php endif; ?>
  <?php if (Auth::can('media.manage')): ?>
  <a href="<?= url('admin/media') ?>" class="admin-quick-strip__item"><i class="bi bi-cloud-upload"></i> Upload media</a>
  <?php endif; ?>
  <?php if (Auth::can('messages.view')): ?>
  <a href="<?= url('admin/messages') ?>" class="admin-quick-strip__item"><i class="bi bi-envelope-open"></i> Open inbox</a>
  <?php endif; ?>
  <a href="<?= url('admin/profile') ?>" class="admin-quick-strip__item"><i class="bi bi-person-gear"></i> My account</a>
</div>

<?php if (Auth::can('logs.view')): ?>
<section class="admin-card admin-card--static admin-activity-card">
  <div class="admin-card-header">
    <div>
      <h2 class="admin-card-title">Website activity</h2>
      <p class="admin-card-caption">A short record of the latest CMS changes.</p>
    </div>
    <a href="<?= url('admin/logs') ?>" class="admin-card-link">View all activity</a>
  </div>
  <?php if (empty($recentLogs)): ?>
    <div class="admin-empty admin-empty--pro"><i class="bi bi-journal-check"></i><p>Changes made in the CMS will appear here.</p></div>
  <?php else: ?>
    <div class="admin-activity-list">
      <?php foreach ($recentLogs as $log): ?>
        <div class="admin-activity-item">
          <span class="admin-activity-item__icon"><i class="bi bi-check2"></i></span>
          <div class="admin-activity-item__body">
            <strong><?= e($log['description'] ?: ucfirst((string)$log['action']) . ' ' . (string)$log['module']) ?></strong>
            <span><?= e($log['user_name'] ?: 'System') ?> <i aria-hidden="true">&middot;</i> <?= e(timeAgo($log['created_at'])) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php endif; ?>

<header class="admin-section-head">
  <h3>Management areas</h3>
  <p>Everything is grouped by the job you want to complete.</p>
</header>

<div class="admin-control-grid">
  <?php foreach ($workspaces as $workspace): ?>
    <?php if (!Auth::can($workspace['permission'])) continue; ?>
    <section class="admin-control-card">
      <div class="admin-control-card__head">
        <span class="admin-control-card__icon"><i class="bi <?= e($workspace['icon']) ?>"></i></span>
        <div>
          <h3><?= e($workspace['title']) ?></h3>
          <p><?= e($workspace['description']) ?></p>
        </div>
      </div>
      <nav class="admin-control-card__links" aria-label="<?= e($workspace['title']) ?>">
        <?php foreach ($workspace['links'] as $link): ?>
          <?php if (isset($link[3]) && !Auth::can($link[3])) continue; ?>
          <a href="<?= url($link[1]) ?>"><i class="bi <?= e($link[2]) ?>"></i><span><?= e($link[0]) ?></span><i class="bi bi-chevron-right"></i></a>
        <?php endforeach; ?>
      </nav>
    </section>
  <?php endforeach; ?>
</div>

<div class="admin-grid-2 admin-dashboard-feed">
  <?php if (Auth::can('messages.view')): ?>
  <section class="admin-card admin-card--static">
    <div class="admin-card-header">
      <h2 class="admin-card-title">Recent messages</h2>
      <a href="<?= url('admin/messages') ?>" class="admin-card-link">View inbox</a>
    </div>
    <?php if (empty($recentMessages)): ?>
      <div class="admin-empty admin-empty--pro"><i class="bi bi-envelope"></i><p>No messages yet</p></div>
    <?php else: foreach ($recentMessages as $msg): ?>
      <a href="<?= url('admin/messages/' . $msg['id']) ?>" class="admin-list-item">
        <div class="admin-list-avatar <?= $msg['is_read'] ? 'admin-list-avatar--read' : 'admin-list-avatar--unread' ?>"><span><?= e(strtoupper(substr($msg['name'], 0, 1))) ?></span></div>
        <div class="admin-list-content">
          <div class="admin-list-meta"><strong><?= e($msg['name']) ?></strong><span class="admin-list-time"><?= e(timeAgo($msg['created_at'])) ?></span></div>
          <div class="admin-list-subtitle"><?= e(truncate($msg['message'], 70)) ?></div>
        </div>
      </a>
    <?php endforeach; endif; ?>
  </section>
  <?php endif; ?>

  <?php if (Auth::can('content.view')): ?>
  <section class="admin-card admin-card--static">
    <div class="admin-card-header">
      <h2 class="admin-card-title">Recent projects</h2>
      <a href="<?= url('admin/projects') ?>" class="admin-card-link">Manage projects</a>
    </div>
    <?php if (empty($recentProjects)): ?>
      <div class="admin-empty admin-empty--pro"><i class="bi bi-building"></i><p>No projects yet</p></div>
    <?php else: foreach ($recentProjects as $project): ?>
      <a href="<?= url('admin/projects/' . $project['id'] . '/edit') ?>" class="admin-list-item">
        <div class="admin-list-thumb">
          <?php if (!empty($project['featured_image'])): ?><img src="<?= e(uploadUrl($project['featured_image'])) ?>" alt="">
          <?php else: ?><i class="bi bi-building"></i><?php endif; ?>
        </div>
        <div class="admin-list-content">
          <strong><?= e($project['title']) ?></strong>
          <div class="admin-list-badges"><span class="badge <?= !empty($project['is_published']) ? 'badge-success' : 'badge-warning' ?>"><?= !empty($project['is_published']) ? 'Published' : 'Draft' ?></span></div>
        </div>
      </a>
    <?php endforeach; endif; ?>
  </section>
  <?php endif; ?>
</div>
