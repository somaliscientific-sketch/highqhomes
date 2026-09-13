<?php
/**
 * @var string      $kicker
 * @var string      $title
 * @var string|null $desc
 * @var string|null $ctaHref
 * @var string|null $ctaLabel
 * @var string|null $id
 * @var bool        $light
 */
$desc     = $desc ?? null;
$ctaHref  = $ctaHref ?? null;
$ctaLabel = $ctaLabel ?? null;
$id       = $id ?? null;
$light    = !empty($light);
?>
<header class="hq-head<?= $light ? ' hq-head--light' : '' ?>"<?= $id ? ' id="' . e($id) . '"' : '' ?>>
  <?php if (!empty($kicker)): ?>
  <p class="hq-eyebrow<?= $light ? ' hq-eyebrow--light' : '' ?>"><?= e($kicker) ?></p>
  <?php endif; ?>
  <h2 class="hq-title<?= $light ? ' hq-title--light' : '' ?>"><?= e($title) ?></h2>
  <?php if ($desc): ?>
  <p class="hq-lead<?= $light ? ' hq-lead--light' : '' ?>"><?= e($desc) ?></p>
  <?php endif; ?>
  <?php if ($ctaHref && $ctaLabel): ?>
  <a href="<?= e($ctaHref) ?>" class="hq-btn hq-btn--outline hq-head__cta"><?= e($ctaLabel) ?> <i class="bi bi-arrow-right"></i></a>
  <?php endif; ?>
</header>
