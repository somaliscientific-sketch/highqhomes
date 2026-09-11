<?php
/** @var array $service @var int $index */
$index = $index ?? 0;
$delay = ($index ?? 0) * 45;
$img   = serviceImageUrl($service);
?>
<article class="hq-services-pro-card" data-anim="up" data-delay="<?= (int)$delay ?>" id="service-<?= e($service['slug']) ?>">
  <a href="<?= url('services/' . $service['slug']) ?>" class="hq-services-pro-card__media">
    <img src="<?= e($img) ?>" alt="<?= e($service['title']) ?>" loading="<?= $index < 3 ? 'eager' : 'lazy' ?>">
    <?php if (!empty($service['is_featured'])): ?>
    <span class="hq-services-pro-card__badge">Featured</span>
    <?php endif; ?>
    <span class="hq-services-pro-card__icon"><i class="bi <?= e($service['icon'] ?? 'bi-building') ?>"></i></span>
  </a>
  <div class="hq-services-pro-card__body">
    <h3><a href="<?= url('services/' . $service['slug']) ?>"><?= e($service['title']) ?></a></h3>
    <p><?= e($service['short_description'] ?: truncate(strip_tags((string)($service['description'] ?? '')), 120)) ?></p>
    <a href="<?= url('services/' . $service['slug']) ?>" class="hq-services-pro-card__link">View service <i class="bi bi-arrow-up-right"></i></a>
  </div>
</article>
