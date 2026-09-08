<?php $pageTitle = e($page['meta_title'] ?? $page['title']) . ' — HighQ Homes'; ?>
<?php
$pageCover = !empty($page['featured_image'])
  ? uploadUrl($page['featured_image'])
  : 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1920&q=80';
?>
<?php View::partial('page-hero', [
  'kicker' => 'HighQ Homes',
  'title' => $page['title'],
  'desc' => $page['excerpt'] ?? null,
  'cover' => $pageCover,
  'breadcrumbs' => [['label' => 'Home', 'url' => url()], ['label' => $page['title'], 'url' => '']],
]); ?>

<section class="hq-page-section">
  <div class="container-site">
    <?php if (!empty($page['featured_image'])): ?>
    <img src="<?= e(uploadUrl($page['featured_image'])) ?>" alt="<?= e($page['title']) ?>" class="hq-featured-img hq-featured-img--short" loading="lazy">
    <?php endif; ?>
    <div class="hq-cms-content"><?= $page['content'] ?></div>
  </div>
</section>
