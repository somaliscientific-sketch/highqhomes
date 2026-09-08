<?php
$pageTitle = 'Message from ' . e($message['name']);
$wa = preg_replace('/[^0-9]/', '', $message['phone'] ?? '');
$replySubject = 'Re: ' . ($message['subject'] ?: 'Your inquiry');
?>

<header class="admin-messages-head admin-messages-head--compact">
  <div>
    <a href="<?= url('admin/messages') ?>" class="admin-messages-back"><i class="bi bi-arrow-left"></i> Back to inbox</a>
    <h2>Message details</h2>
    <p>Received <?= e(date('M j, Y \a\t g:i A', strtotime($message['created_at']))) ?></p>
  </div>
  <div class="admin-messages-head__stats">
    <?php if ($stats['unread'] > 0): ?>
    <div class="admin-messages-head__stat admin-messages-head__stat--unread">
      <strong><?= number_format($stats['unread']) ?></strong>
      <span>Unread left</span>
    </div>
    <?php endif; ?>
  </div>
</header>

<div class="admin-messages-detail">
  <div class="admin-card admin-messages-detail__main">
    <div class="admin-messages-detail__head">
      <div class="admin-messages-detail__sender">
        <span class="admin-messages-detail__avatar"><?= strtoupper(substr($message['name'], 0, 1)) ?></span>
        <div>
          <h3><?= e($message['name']) ?></h3>
          <div class="admin-messages-detail__chips">
            <a href="mailto:<?= e($message['email']) ?>" class="admin-messages-chip"><i class="bi bi-envelope"></i> <?= e($message['email']) ?></a>
            <?php if (!empty($message['phone'])): ?>
            <span class="admin-messages-chip"><i class="bi bi-telephone"></i> <?= e($message['phone']) ?></span>
            <?php endif; ?>
            <?php if (!empty($message['is_starred'])): ?>
            <span class="admin-messages-chip admin-messages-chip--star"><i class="bi bi-star-fill"></i> Starred</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="admin-messages-detail__head-actions">
        <form action="<?= url('admin/messages/' . $message['id'] . '/star') ?>" method="POST" class="admin-inline-form">
          <?= csrf() ?>
          <button type="submit" class="admin-message-star admin-message-star--lg<?= !empty($message['is_starred']) ? ' is-active' : '' ?>" title="<?= !empty($message['is_starred']) ? 'Unstar' : 'Star' ?>">
            <i class="bi bi-star<?= !empty($message['is_starred']) ? '-fill' : '' ?>"></i>
          </button>
        </form>
        <?php if (Auth::can('messages.manage')): ?>
        <form action="<?= url('admin/messages/' . $message['id'] . '/delete') ?>" method="POST" class="admin-inline-form">
          <?= csrf() ?>
          <button type="submit" data-confirm="Delete this message?" class="admin-btn admin-btn-danger admin-btn-sm">
            <i class="bi bi-trash"></i> Delete
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($message['subject'])): ?>
    <div class="admin-messages-detail__subject">
      <span>Subject</span>
      <strong><?= e($message['subject']) ?></strong>
    </div>
    <?php endif; ?>

    <div class="admin-messages-detail__body">
      <?= e($message['message']) ?>
    </div>

    <div class="admin-messages-detail__reply">
      <a href="mailto:<?= e($message['email']) ?>?subject=<?= urlencode($replySubject) ?>" class="admin-btn admin-btn-primary">
        <i class="bi bi-reply"></i> Reply via email
      </a>
      <?php if ($wa !== ''): ?>
      <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode('Hello ' . $message['name'] . ', thank you for contacting HighQ Homes.') ?>" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">
        <i class="bi bi-whatsapp"></i> WhatsApp
      </a>
      <?php endif; ?>
      <button type="button" class="admin-btn admin-btn-secondary" data-copy="<?= e($message['email']) ?>">
        <i class="bi bi-clipboard"></i> Copy email
      </button>
    </div>
  </div>

  <aside class="admin-messages-detail__aside">
    <div class="admin-card admin-card-body">
      <h4 class="admin-messages-aside__title"><i class="bi bi-info-circle"></i> Message info</h4>
      <dl class="admin-messages-aside__dl">
        <div>
          <dt>Status</dt>
          <dd><span class="admin-status-pill admin-status-pill--<?= !empty($message['is_read']) ? 'active' : 'disabled' ?>"><?= !empty($message['is_read']) ? 'Read' : 'Unread' ?></span></dd>
        </div>
        <div>
          <dt>Received</dt>
          <dd><?= e(date('M j, Y g:i A', strtotime($message['created_at']))) ?></dd>
        </div>
        <?php if (!empty($message['ip_address'])): ?>
        <div>
          <dt>IP address</dt>
          <dd><code><?= e($message['ip_address']) ?></code></dd>
        </div>
        <?php endif; ?>
        <div>
          <dt>Message ID</dt>
          <dd>#<?= (int)$message['id'] ?></dd>
        </div>
      </dl>
    </div>

    <div class="admin-card admin-card-body admin-messages-tips">
      <h4 class="admin-messages-aside__title"><i class="bi bi-lightbulb"></i> Quick tips</h4>
      <ul>
        <li>Star important leads to find them quickly in the Starred folder.</li>
        <li>Reply promptly — most clients expect a response within 24 hours.</li>
        <li>Use WhatsApp for faster follow-up when a phone number is provided.</li>
      </ul>
    </div>
  </aside>
</div>
