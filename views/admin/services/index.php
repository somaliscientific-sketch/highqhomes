<?php $pageTitle = 'Services'; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta"><?= count($services) ?> service(s)</p>
  <?php if (canManage()): ?>
  <a href="<?= url('admin/services/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Service</a>
  <?php endif; ?>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Service</th>
        <th class="hide-mobile">Icon</th>
        <th class="hide-mobile">Order</th>
        <th>Status</th>
        <th style="text-align:right">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($services as $svc): ?>
      <tr>
        <td>
          <div class="admin-table-title"><?= e($svc['title']) ?></div>
          <div class="admin-table-desc"><?= e(truncate($svc['short_description'] ?? '', 60)) ?></div>
        </td>
        <td class="hide-mobile"><i class="bi <?= e($svc['icon'] ?? 'bi-building') ?>" style="color:var(--admin-navy);font-size:1.2rem"></i></td>
        <td class="hide-mobile"><span class="badge badge-primary"><?= $svc['sort_order'] ?></span></td>
        <td>
          <span class="badge <?= $svc['is_published'] ? 'badge-success' : 'badge-warning' ?>">
            <?= $svc['is_published'] ? 'Published' : 'Draft' ?>
          </span>
        </td>
        <td>
          <?php if (canManage()): ?>
          <div class="admin-table-actions">
            <a href="<?= url('admin/services/' . $svc['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i></a>
            <form action="<?= url('admin/services/' . $svc['id'] . '/toggle') ?>" method="POST">
              <?= csrf() ?>
              <button class="admin-btn admin-btn-secondary admin-btn-sm" title="Toggle publish"><i class="bi bi-<?= $svc['is_published'] ? 'eye-slash' : 'eye' ?>"></i></button>
            </form>
            <form action="<?= url('admin/services/' . $svc['id'] . '/delete') ?>" method="POST">
              <?= csrf() ?>
              <button data-confirm="Delete '<?= e($svc['title']) ?>'?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button>
            </form>
          </div>
          <?php else: ?>
          <span class="admin-table-desc">View only</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
