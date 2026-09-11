<?php
/**
 * Featured upcoming-project band for /projects
 * @var array  $project
 * @var string $wa
 */
$pImg     = projectImageUrl($project);
$pCat     = projectCategoryLabel($project['category'] ?? null);
$pDesc    = trim((string)($project['short_description'] ?? ''));
$location = trim((string)($project['location'] ?? ''));
$year     = trim((string)($project['project_year'] ?? ''));
$area     = trim((string)($project['project_area'] ?? ''));
$wa       = preg_replace('/[^0-9]/', '', (string)($wa ?? '252907734667')) ?: '252907734667';
$discussHref = 'https://wa.me/' . $wa . '?text=' . rawurlencode('Hello HighQ Homes, I would like to discuss a home like ' . (string)$project['title'] . '.');
?>
<section class="hq-upcoming" id="upcoming" aria-labelledby="upcoming-title">
  <div class="container-site">
    <article class="hq-upcoming__card" data-anim="up">
      <figure class="hq-upcoming__media">
        <img src="<?= e($pImg) ?>" alt="<?= e($project['title']) ?>" loading="eager">
        <div class="hq-upcoming__media-shade" aria-hidden="true"></div>
        <figcaption class="hq-upcoming__badges">
          <span class="hq-upcoming__badge"><i class="bi bi-stars"></i> Upcoming</span>
          <span class="hq-upcoming__viz">Design visualization</span>
        </figcaption>
      </figure>
      <div class="hq-upcoming__copy">
        <p class="hq-upcoming__kicker">Coming next</p>
        <h2 id="upcoming-title" class="hq-upcoming__title"><?= e($project['title']) ?></h2>
        <?php if ($pDesc !== ''): ?>
        <p class="hq-upcoming__lead"><?= e($pDesc) ?></p>
        <?php endif; ?>
        <ul class="hq-upcoming__facts">
          <li><i class="bi bi-house-heart"></i> <?= e($pCat) ?></li>
          <?php if ($location !== ''): ?>
          <li><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></li>
          <?php endif; ?>
          <?php if ($year !== ''): ?>
          <li><i class="bi bi-calendar3"></i> Target <?= e($year) ?></li>
          <?php endif; ?>
          <?php if ($area !== ''): ?>
          <li><i class="bi bi-rulers"></i> <?= e($area) ?></li>
          <?php endif; ?>
        </ul>
        <div class="hq-upcoming__actions">
          <a href="<?= url('projects/' . $project['slug']) ?>" class="hq-btn hq-btn--orange">View the design <i class="bi bi-arrow-up-right"></i></a>
          <a href="<?= e($discussHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--ghost"><i class="bi bi-whatsapp"></i> Discuss a similar build</a>
        </div>
      </div>
    </article>
  </div>
</section>
