<?php
/** @var array $service @var int $index @var int $delay */
$index = $index ?? 0;
$delay = $delay ?? 0;
?>
<article class="hq-card hq-service" data-anim="up" data-delay="<?= (int)$delay ?>">
  <a href="<?= url('services/' . $service['slug']) ?>" class="hq-service__link">
    <div class="hq-service__img">
      <img src="<?= e(serviceImageUrl($service)) ?>" alt="<?= e($service['title']) ?>" loading="lazy">
    </div>
    <div class="hq-service__body">
      <h3><?= e($service['title']) ?></h3>
      <p><?= e($service['short_description'] ?: truncate((string)($service['description'] ?? ''), 100)) ?></p>
      <span class="hq-service__more">Learn more <i class="bi bi-arrow-right"></i></span>
    </div>
  </a>
</article>
