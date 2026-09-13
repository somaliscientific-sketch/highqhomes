<?php
/**
 * Premium portfolio card for /projects listing
 * @var array  $project
 * @var int    $index
 * @var int    $delay
 * @var string $size  featured|compact|standard|solo
 */
$index = $index ?? 0;
$delay = $delay ?? 0;
$size  = $size ?? 'standard';
$pImg  = projectImageUrl($project);
$pCat  = projectCategoryLabel($project['category'] ?? null);
$pDesc = trim((string)($project['short_description'] ?? ''));
$location = trim((string)($project['location'] ?? ''));
$isFeatured = !empty($project['is_featured']);
$statusClass = match ($project['status'] ?? '') {
    'completed'   => 'done',
    'in_progress' => 'active',
    'planned'     => 'planned',
    default       => 'done',
};
$statusLabel = projectStatusLabel($project['status'] ?? null);
?>
<article class="hq-projects-pro-card hq-projects-pro-card--<?= e($size) ?><?= $isFeatured ? ' is-featured' : '' ?>" data-anim="up" data-delay="<?= (int)$delay ?>">
  <a href="<?= url('projects/' . $project['slug']) ?>" class="hq-projects-pro-card__link">
    <figure class="hq-projects-pro-card__media">
      <img src="<?= e($pImg) ?>" alt="<?= e($project['title']) ?>" loading="<?= $index < 3 ? 'eager' : 'lazy' ?>">
      <figcaption class="hq-projects-pro-card__overlay">
        <div class="hq-projects-pro-card__tags">
          <span class="hq-tag hq-tag--cat"><?= e($pCat) ?></span>
          <span class="hq-tag hq-tag--<?= e($statusClass) ?>"><?= e($statusLabel) ?></span>
        </div>
        <?php if ($isFeatured): ?>
        <span class="hq-projects-pro-card__featured"><i class="bi bi-star-fill"></i> Featured</span>
        <?php endif; ?>
        <span class="hq-projects-pro-card__view">View project <i class="bi bi-arrow-up-right"></i></span>
      </figcaption>
    </figure>
    <div class="hq-projects-pro-card__body">
      <h3><?= e($project['title']) ?></h3>
      <?php if ($pDesc !== ''): ?>
      <p><?= e(truncate($pDesc, $size === 'featured' || $size === 'solo' ? 160 : 110)) ?></p>
      <?php endif; ?>
      <footer class="hq-projects-pro-card__meta">
        <?php if ($location !== ''): ?>
        <span><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></span>
        <?php endif; ?>
        <?php if (!empty($project['project_year'])): ?>
        <span><i class="bi bi-calendar3"></i> <?= e((string)$project['project_year']) ?></span>
        <?php endif; ?>
        <span class="hq-projects-pro-card__cta">Explore <i class="bi bi-arrow-right"></i></span>
      </footer>
    </div>
  </a>
</article>
