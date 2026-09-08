<?php $pageTitle = 'Pages'; ?>

<div class="admin-page-toolbar">
  <form method="GET" class="admin-search-bar">
    <input class="admin-input" name="q" value="<?= e($q ?? '') ?>" placeholder="Search pages...">
    <button class="admin-btn admin-btn-secondary"><i class="bi bi-search"></i></button>
  </form>
  <?php if (canManage()): ?>
  <a href="<?= url('admin/pages/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Page</a>
  <?php endif; ?>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead><tr><th>Page</th><th class="hide-mobile">Slug</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
      <tr>
        <td>
          <div class="admin-table-title"><?= e($item['title']) ?></div>
          <div class="admin-table-desc"><?= e(truncate($item['excerpt'] ?? '', 80)) ?></div>
        </td>
        <td class="hide-mobile"><span class="admin-seo-slug">/<?= e($item['slug']) ?></span></td>
        <td><span class="badge <?= $item['is_published'] ? 'badge-success' : 'badge-warning' ?>"><?= $item['is_published'] ? 'Published' : 'Draft' ?></span></td>
        <td>
          <div class="admin-table-actions">
            <a href="<?= url($item['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-eye"></i></a>
            <?php if (canManage()): ?>
            <a href="<?= url('admin/pages/' . $item['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i></a>
            <form action="<?= url('admin/pages/' . $item['id'] . '/toggle') ?>" method="POST"><?= csrf() ?><button class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-<?= $item['is_published'] ? 'eye-slash' : 'eye' ?>"></i></button></form>
            <form action="<?= url('admin/pages/' . $item['id'] . '/delete') ?>" method="POST"><?= csrf() ?><button data-confirm="Delete this page?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button></form>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?= paginate(compact('total','per_page','current','last_page'), url('admin/pages') . (($q ?? '') !== '' ? '?q=' . urlencode($q) : '')) ?>
