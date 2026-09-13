<?php $pageTitle = 'Menus'; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta">Manage public navigation and footer links.</p>
  <a href="<?= url('admin/menus/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Menu Item</a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>Label</th><th>URL</th><th class="hide-mobile">Location</th><th class="hide-mobile">Order</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
      <?php foreach ($menus as $menu): ?>
      <tr>
        <td class="admin-table-title"><?= e($menu['label']) ?></td>
        <td class="admin-table-desc"><?= e($menu['url']) ?></td>
        <td class="hide-mobile"><?= e(ucfirst($menu['location'])) ?></td>
        <td class="hide-mobile"><span class="badge badge-primary"><?= (int)$menu['sort_order'] ?></span></td>
        <td><span class="badge <?= $menu['is_published'] ? 'badge-success' : 'badge-warning' ?>"><?= $menu['is_published'] ? 'Published' : 'Draft' ?></span></td>
        <td>
          <div class="admin-table-actions">
            <a href="<?= url('admin/menus/' . $menu['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i></a>
            <form action="<?= url('admin/menus/' . $menu['id'] . '/toggle') ?>" method="POST"><?= csrf() ?><button class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-<?= $menu['is_published'] ? 'eye-slash' : 'eye' ?>"></i></button></form>
            <form action="<?= url('admin/menus/' . $menu['id'] . '/delete') ?>" method="POST"><?= csrf() ?><button data-confirm="Delete menu item?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button></form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
