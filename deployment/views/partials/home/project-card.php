<?php
/**
 * @var array $project
 * @var int   $index
 * @var int   $delay
 */
$index = $index ?? 0;
$delay = $delay ?? 0;
$pImg  = projectImageUrl($project);
$pCat  = projectCategoryLabel($project['category'] ?? null);
$pDesc = trim((string)($project['short_description'] ?? ''));
$statusClass = match ($project['status'] ?? '') {
    'completed' => 'done', 'in_progress' => 'active', 'planned' => 'planned', default => 'done',
};
?>
<article class="hq-card hq-project" data-anim="up" data-delay="<?= (int)$delay ?>">
  <a href="<?= url('projects/' . $project['slug']) ?>" class="hq-project__link">
    <figure class="hq-project__img">
      <img src="<?= e($pImg) ?>" alt="<?= e($project['title']) ?>" loading="<?= $index < 2 ? 'eager' : 'lazy' ?>">
      <figcaption class="hq-project__badges">
        <span class="hq-tag hq-tag--cat"><?= e($pCat) ?></span>
        <span class="hq-tag hq-tag--<?= e($statusClass) ?>"><?= e(projectStatusLabel($project['status'] ?? null)) ?></span>
      </figcaption>
    </figure>
    <div class="hq-project__body">
      <h3><?= e($project['title']) ?></h3>
      <?php if ($pDesc !== ''): ?><p><?= e(truncate($pDesc, 100)) ?></p><?php endif; ?>
    </div>
  </a>
</article>
