<?php
/**
 * @var string      $kicker
 * @var string      $title
 * @var string|null $desc
 * @var string|null $cover
 * @var array|null  $breadcrumbs
 */
$breadcrumbs = $breadcrumbs ?? [['label' => 'Home', 'url' => url()]];
$cover = $cover ?? 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80';
?>
<section class="hq-page-hero">
  <div class="hq-page-hero__bg" aria-hidden="true"><img src="<?= e($cover) ?>" alt="" loading="eager"></div>
  <div class="hq-page-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-page-hero__content">
    <?php if (!empty($kicker)): ?>
    <p class="hq-eyebrow hq-eyebrow--light"><?= e($kicker) ?></p>
    <?php endif; ?>
    <h1 class="hq-title hq-title--light"><?= e($title) ?></h1>
    <?php if (!empty($desc)): ?>
    <p class="hq-lead hq-lead--light hq-page-hero__desc"><?= e($desc) ?></p>
    <?php endif; ?>
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <?php foreach ($breadcrumbs as $i => $crumb): ?>
      <?php if ($i > 0): ?><span class="sep"><i class="bi bi-chevron-right"></i></span><?php endif; ?>
      <?php if (!empty($crumb['url'])): ?>
      <a href="<?= e($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
      <?php else: ?>
      <span class="current"><?= e($crumb['label']) ?></span>
      <?php endif; ?>
      <?php endforeach; ?>
    </nav>
  </div>
</section>
