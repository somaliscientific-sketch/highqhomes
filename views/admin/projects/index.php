<?php $pageTitle = 'Projects'; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta"><?= $total ?> project(s) total</p>
  <?php if (canManage()): ?>
  <a href="<?= url('admin/projects/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Project</a>
  <?php endif; ?>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th style="width:56px"></th>
        <th>Project</th>
        <th class="hide-mobile">Category</th>
        <th class="hide-mobile">Status</th>
        <th>Visibility</th>
        <th style="text-align:right">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $proj): ?>
      <tr>
        <td>
          <div class="admin-table-thumb">
            <?php if (!empty($proj['featured_image'])): ?>
            <img src="<?= e(projectImageUrl($proj)) ?>" alt="">
            <?php else: ?>
            <div class="admin-table-thumb-placeholder"><i class="bi bi-building"></i></div>
            <?php endif; ?>
          </div>
        </td>
        <td>
          <div class="admin-table-title"><?= e($proj['title']) ?></div>
          <div class="admin-table-desc"><?= e($proj['location'] ?? '') ?> <?= $proj['project_year'] ? '· '.$proj['project_year'] : '' ?></div>
        </td>
        <td class="hide-mobile"><span class="badge badge-primary"><?= e(ucfirst($proj['category'] ?? '')) ?></span></td>
        <td class="hide-mobile">
          <span class="badge <?= $proj['status'] === 'completed' ? 'badge-success' : 'badge-warning' ?>">
            <?= e(ucwords(str_replace('_',' ',$proj['status'] ?? ''))) ?>
          </span>
        </td>
        <td>
          <span class="badge <?= $proj['is_published'] ? 'badge-success' : 'badge-warning' ?>">
            <?= $proj['is_published'] ? 'Published' : 'Draft' ?>
          </span>
          <?php if ($proj['is_featured']): ?>
          <span class="badge badge-gold"><i class="bi bi-star-fill"></i></span>
          <?php endif; ?>
        </td>
        <td>
          <?php if (canManage()): ?>
          <div class="admin-table-actions">
            <a href="<?= url('admin/projects/' . $proj['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i></a>
            <form action="<?= url('admin/projects/' . $proj['id'] . '/toggle') ?>" method="POST">
              <?= csrf() ?>
              <button class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-<?= $proj['is_published'] ? 'eye-slash' : 'eye' ?>"></i></button>
            </form>
            <form action="<?= url('admin/projects/' . $proj['id'] . '/delete') ?>" method="POST">
              <?= csrf() ?>
              <button data-confirm="Delete '<?= e($proj['title']) ?>'?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button>
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

<?= paginate(['last_page'=>$last_page,'current'=>$current,'total'=>$total,'per_page'=>$per_page], url('admin/projects')) ?>
