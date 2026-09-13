<?php
/**
 * Homepage premium portfolio card
 * @var array  $project
 * @var string $variant  hero|side|tile|strip
 * @var int    $index
 * @var int    $delay
 */
$variant = $variant ?? 'tile';
$index   = $index ?? 0;
$delay   = $delay ?? 0;

$pImg = projectImageUrl($project);
$pCat = projectCategoryLabel($project['category'] ?? null);
$pDesc = trim((string)($project['short_description'] ?? ''));
$pYear = trim((string)($project['project_year'] ?? ''));
$pLocation = trim((string)($project['location'] ?? ''));
$statusClass = match ($project['status'] ?? '') {
    'completed' => 'done', 'in_progress' => 'active', 'planned' => 'planned', default => 'done',
};
?>
<article class="hq-hp-portfolio-card hq-hp-portfolio-card--<?= e($variant) ?>" data-anim="up" data-delay="<?= (int)$delay ?>">
  <a href="<?= url('projects/' . $project['slug']) ?>" class="hq-hp-portfolio-card__link">
    <figure class="hq-hp-portfolio-card__media">
      <img src="<?= e($pImg) ?>" alt="<?= e($project['title']) ?>" loading="<?= $index < 2 ? 'eager' : 'lazy' ?>">
      <span class="hq-hp-portfolio-card__shade" aria-hidden="true"></span>
      <?php if ($variant === 'strip'): ?>
      <span class="hq-hp-portfolio-card__index"><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
      <?php endif; ?>
      <span class="hq-hp-portfolio-card__view" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
      <figcaption class="hq-hp-portfolio-card__tags">
        <span class="hq-tag hq-tag--cat"><?= e($pCat) ?></span>
        <span class="hq-tag hq-tag--<?= e($statusClass) ?>"><?= e(projectStatusLabel($project['status'] ?? null)) ?></span>
      </figcaption>
    </figure>
    <div class="hq-hp-portfolio-card__body">
      <?php if ($pYear !== '' || $pLocation !== ''): ?>
      <div class="hq-hp-portfolio-card__meta">
        <?php if ($pYear !== ''): ?><span><i class="bi bi-calendar3"></i> <?= e($pYear) ?></span><?php endif; ?>
        <?php if ($pLocation !== ''): ?><span><i class="bi bi-geo-alt"></i> <?= e(truncate($pLocation, 28)) ?></span><?php endif; ?>
      </div>
      <?php endif; ?>
      <h3><?= e($project['title']) ?></h3>
      <?php if ($pDesc !== '' && $variant !== 'strip'): ?>
      <p><?= e(truncate($pDesc, $variant === 'hero' ? 140 : 90)) ?></p>
      <?php elseif ($pDesc !== '' && $variant === 'strip'): ?>
      <p><?= e(truncate($pDesc, 72)) ?></p>
      <?php endif; ?>
      <span class="hq-hp-portfolio-card__more">View project <i class="bi bi-arrow-right"></i></span>
    </div>
  </a>
</article>
