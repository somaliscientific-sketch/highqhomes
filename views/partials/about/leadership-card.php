<?php
/**
 * @var array $member
 * @var int   $index
 * @var int   $delay
 */
$index = $index ?? 0;
$delay = $delay ?? 0;
$img = teamImageUrl($member, $index);
$bio = trim(strip_tags((string)($member['bio'] ?? '')));
$hasSocial = !empty($member['email']) || !empty($member['linkedin_url']) || !empty($member['twitter_url']) || !empty($member['phone']);
?>
<article class="hq-leader-card" data-anim="up" data-delay="<?= (int)$delay ?>">
  <div class="hq-leader-card__photo">
    <img src="<?= e($img) ?>" alt="<?= e($member['name']) ?>" loading="<?= $index < 3 ? 'eager' : 'lazy' ?>">
    <div class="hq-leader-card__overlay">
      <span class="hq-leader-card__num"><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
      <div class="hq-leader-card__identity">
        <h3><?= e($member['name']) ?></h3>
        <p><?= e($member['position']) ?></p>
      </div>
    </div>
  </div>
  <div class="hq-leader-card__content">
    <?php if ($bio !== ''): ?>
    <p class="hq-leader-card__bio"><?= e(truncate($bio, 155)) ?></p>
    <?php endif; ?>
    <?php if ($hasSocial): ?>
    <div class="hq-leader-card__links">
      <?php if (!empty($member['email'])): ?>
      <a href="mailto:<?= e($member['email']) ?>" aria-label="Email <?= e($member['name']) ?>"><i class="bi bi-envelope-fill"></i></a>
      <?php endif; ?>
      <?php if (!empty($member['phone'])): ?>
      <a href="tel:<?= e(preg_replace('/\s+/', '', $member['phone'])) ?>" aria-label="Call"><i class="bi bi-telephone-fill"></i></a>
      <?php endif; ?>
      <?php if (!empty($member['linkedin_url'])): ?>
      <a href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
      <?php endif; ?>
      <?php if (!empty($member['twitter_url'])): ?>
      <a href="<?= e($member['twitter_url']) ?>" target="_blank" rel="noopener" aria-label="X"><i class="bi bi-twitter-x"></i></a>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <span class="hq-leader-card__connect">HighQ Homes Leadership</span>
    <?php endif; ?>
  </div>
</article>
