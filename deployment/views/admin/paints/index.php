<?php $pageTitle = 'Paint Products'; ?>

<header class="admin-page-intro">
  <div>
    <span class="admin-report-kicker">Products</span>
    <h2>Paint catalogue</h2>
    <p>Manage paint products, brands, pricing, and which items stay live on the website.</p>
  </div>
</header>

<div class="admin-page-toolbar">
  <label class="admin-list-filter">
    <i class="bi bi-search" aria-hidden="true"></i>
    <input type="search" data-admin-list-filter placeholder="Filter products..." aria-label="Filter paint products">
  </label>
  <p class="admin-page-meta" data-admin-list-count data-noun="product"><?= count($paints) ?> product<?= count($paints) === 1 ? '' : 's' ?></p>
  <a href="<?= url('admin/paints/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Product</a>
</div>

<div class="admin-card-grid admin-card-grid--3">
  <?php foreach ($paints as $paint): ?>
  <div class="admin-card" data-admin-list-item>
    <div class="admin-card-thumb">
      <?php if (!empty($paint['featured_image'])): ?>
      <img src="<?= e(uploadUrl($paint['featured_image'])) ?>" alt="<?= e($paint['name']) ?>">
      <?php else: ?>
      <div class="admin-card-thumb-placeholder"><i class="bi bi-palette"></i></div>
      <?php endif; ?>
      <div class="admin-card-badges-overlay">
        <span class="badge <?= $paint['is_published'] ? 'badge-success' : 'badge-warning' ?>"><?= $paint['is_published'] ? 'Live' : 'Draft' ?></span>
      </div>
    </div>
    <div class="admin-card-content">
      <div class="admin-card-name"><?= e($paint['name']) ?></div>
      <?php if ($paint['brand']): ?><div class="admin-card-accent"><?= e($paint['brand']) ?></div><?php endif; ?>
      <?php if ($paint['price']): ?><div class="admin-card-price"><?= e($paint['price']) ?> <?= e($paint['unit'] ?? '') ?></div><?php endif; ?>
      <div class="admin-card-actions">
        <a href="<?= url('admin/paints/' . $paint['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        <form action="<?= url('admin/paints/' . $paint['id'] . '/delete') ?>" method="POST">
          <?= csrf() ?>
          <button data-confirm="Delete '<?= e($paint['name']) ?>'?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if (empty($paints)): ?>
  <div class="admin-card admin-empty" style="grid-column:1/-1">
    <i class="bi bi-palette" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:1rem"></i>
    <a href="<?= url('admin/paints/create') ?>" class="admin-btn admin-btn-primary">Add First Product</a>
  </div>
  <?php endif; ?>
</div>
