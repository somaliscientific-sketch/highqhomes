<?php
$pageTitle = 'Messages';
$filters = [
    'all'     => ['label' => 'All', 'icon' => 'bi-inbox', 'count' => $stats['total']],
    'unread'  => ['label' => 'Unread', 'icon' => 'bi-envelope', 'count' => $stats['unread']],
    'starred' => ['label' => 'Starred', 'icon' => 'bi-star', 'count' => $stats['starred']],
];
$queryBase = array_filter(['filter' => $filter !== 'all' ? $filter : null, 'q' => $q !== '' ? $q : null]);
$listUrl = static function (array $extra = []) use ($queryBase): string {
    $params = array_filter(array_merge($queryBase, $extra), static fn($v) => $v !== null && $v !== '');
    $qs = http_build_query($params);
    return url('admin/messages') . ($qs !== '' ? '?' . $qs : '');
};
?>

<header class="admin-messages-head">
  <div>
    <span class="admin-report-kicker">Inbox</span>
    <h2>Contact messages</h2>
    <p>Leads and inquiries from the website contact form — review, star, and reply.</p>
  </div>
  <div class="admin-messages-head__stats">
    <div class="admin-messages-head__stat">
      <strong><?= number_format($stats['total']) ?></strong>
      <span>Total</span>
    </div>
    <div class="admin-messages-head__stat admin-messages-head__stat--unread">
      <strong><?= number_format($stats['unread']) ?></strong>
      <span>Unread</span>
    </div>
    <div class="admin-messages-head__stat admin-messages-head__stat--star">
      <strong><?= number_format($stats['starred']) ?></strong>
      <span>Starred</span>
    </div>
  </div>
</header>

<div class="admin-messages-toolbar admin-card admin-card-body">
  <form method="GET" action="<?= url('admin/messages') ?>" class="admin-messages-toolbar__search">
    <?php if ($filter !== 'all'): ?>
    <input type="hidden" name="filter" value="<?= e($filter) ?>">
    <?php endif; ?>
    <i class="bi bi-search" aria-hidden="true"></i>
    <input type="search" name="q" value="<?= e($q) ?>" class="admin-input admin-messages-toolbar__input" placeholder="Search name, email, subject, or message…" autocomplete="off">
    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm">Search</button>
    <?php if ($q !== ''): ?>
    <a href="<?= e($listUrl(['q' => null, 'page' => null])) ?>" class="admin-btn admin-btn-secondary admin-btn-sm">Clear</a>
    <?php endif; ?>
  </form>
  <div class="admin-messages-toolbar__filters" role="tablist" aria-label="Message filters">
    <?php foreach ($filters as $key => $meta): ?>
    <a
      href="<?= e($listUrl(['filter' => $key === 'all' ? null : $key, 'page' => null])) ?>"
      class="admin-messages-filter<?= $filter === $key ? ' is-active' : '' ?>"
      role="tab"
      aria-selected="<?= $filter === $key ? 'true' : 'false' ?>"
    >
      <i class="bi <?= e($meta['icon']) ?>"></i>
      <?= e($meta['label']) ?>
      <span class="admin-messages-filter__count"><?= number_format($meta['count']) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="admin-card admin-messages-inbox">
  <div class="admin-card-header">
    <div>
      <h3 class="admin-card-title">
        <?php if ($filter === 'unread'): ?>Unread inbox
        <?php elseif ($filter === 'starred'): ?>Starred messages
        <?php else: ?>All messages<?php endif; ?>
      </h3>
      <p class="admin-messages-inbox__sub">
        <?= $total === 1 ? '1 message' : number_format($total) . ' messages' ?>
        <?= $q !== '' ? ' matching your search' : '' ?>
      </p>
    </div>
  </div>

  <?php if (empty($items)): ?>
  <div class="admin-empty admin-empty--pro">
    <i class="bi bi-inbox"></i>
    <p><?= $q !== '' ? 'No messages match your search.' : 'No messages in this folder yet.' ?></p>
  </div>
  <?php else: ?>
  <div class="admin-messages-list">
    <?php foreach ($items as $msg): ?>
    <?php $isUnread = empty($msg['is_read']); ?>
    <article class="admin-message-item<?= $isUnread ? ' admin-message-item--unread' : '' ?><?= !empty($msg['is_starred']) ? ' admin-message-item--starred' : '' ?>">
      <a href="<?= url('admin/messages/' . $msg['id']) ?>" class="admin-message-item__link">
        <span class="admin-message-item__avatar" aria-hidden="true"><?= strtoupper(substr($msg['name'], 0, 1)) ?></span>
        <span class="admin-message-item__body">
          <span class="admin-message-item__top">
            <span class="admin-message-item__name">
              <?= e($msg['name']) ?>
              <?php if ($isUnread): ?><span class="admin-message-item__badge">New</span><?php endif; ?>
            </span>
            <time class="admin-message-item__time" datetime="<?= e($msg['created_at']) ?>"><?= e(timeAgo($msg['created_at'])) ?></time>
          </span>
          <?php if (!empty($msg['subject'])): ?>
          <span class="admin-message-item__subject"><?= e($msg['subject']) ?></span>
          <?php endif; ?>
          <span class="admin-message-item__preview"><?= e(truncate($msg['message'], 120)) ?></span>
          <span class="admin-message-item__meta">
            <span><i class="bi bi-envelope"></i> <?= e($msg['email']) ?></span>
            <?php if (!empty($msg['phone'])): ?>
            <span><i class="bi bi-telephone"></i> <?= e($msg['phone']) ?></span>
            <?php endif; ?>
          </span>
        </span>
      </a>
      <div class="admin-message-item__actions">
        <form action="<?= url('admin/messages/' . $msg['id'] . '/star') ?>" method="POST" class="admin-inline-form">
          <?= csrf() ?>
          <button type="submit" class="admin-message-star<?= !empty($msg['is_starred']) ? ' is-active' : '' ?>" title="<?= !empty($msg['is_starred']) ? 'Unstar' : 'Star' ?>" aria-label="<?= !empty($msg['is_starred']) ? 'Unstar message' : 'Star message' ?>">
            <i class="bi bi-star<?= !empty($msg['is_starred']) ? '-fill' : '' ?>"></i>
          </button>
        </form>
        <a href="<?= url('admin/messages/' . $msg['id']) ?>" class="admin-btn admin-btn-secondary admin-btn-sm" title="View message" aria-label="View message from <?= e($msg['name']) ?>">
          <i class="bi bi-eye"></i>
        </a>
        <?php if (Auth::can('messages.manage')): ?>
        <form action="<?= url('admin/messages/' . $msg['id'] . '/delete') ?>" method="POST" class="admin-inline-form">
          <?= csrf() ?>
          <button type="submit" data-confirm="Delete message from &quot;<?= e($msg['name']) ?>&quot;?" class="admin-btn admin-btn-danger admin-btn-sm" title="Delete" aria-label="Delete message">
            <i class="bi bi-trash"></i>
          </button>
        </form>
        <?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if ($last_page > 1): ?>
  <div class="admin-messages-pagination">
    <?= paginate(['last_page' => $last_page, 'current' => $current, 'total' => $total, 'per_page' => $per_page], $listUrl()) ?>
  </div>
  <?php endif; ?>
</div>
