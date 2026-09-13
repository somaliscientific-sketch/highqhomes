<?php
/** @var array $service @var int $index */
$index = $index ?? 0;
$delay = ($index ?? 0) * 45;

$fallbacks = [
  'architecture'      => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80',
  'exterior-design'   => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
  'landscape-design'  => 'https://images.unsplash.com/photo-1558904541-efa843a96f01?w=800&q=80',
  'site-planning'     => 'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=800&q=80',
  'interior-design'   => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800&q=80',
  'furniture-design'  => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80',
];
$fallback = $fallbacks[$service['slug'] ?? ''] ?? 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=800&q=80';
$img      = serviceImageUrl($service, $fallback);
?>
<article class="hq-services-pro-card" data-anim="up" data-delay="<?= (int)$delay ?>" id="service-<?= e($service['slug']) ?>">
  <a href="<?= url('services/' . $service['slug']) ?>" class="hq-services-pro-card__media">
    <img src="<?= e($img) ?>" alt="<?= e($service['title']) ?>" loading="lazy">
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
