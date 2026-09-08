<?php $pageTitle = 'Team Members'; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta"><?= count($members) ?> member(s)</p>
  <a href="<?= url('admin/team/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Member</a>
</div>

<div class="admin-card-grid admin-card-grid--3">
  <?php foreach ($members as $m): ?>
  <div class="admin-card admin-team-card">
    <div class="admin-team-avatar">
      <?php if (!empty($m['image'])): ?>
      <img src="<?= e(uploadUrl($m['image'])) ?>" alt="">
      <?php else: ?>
      <span><?= strtoupper(substr($m['name'],0,1)) ?></span>
      <?php endif; ?>
    </div>
    <div style="flex:1;min-width:0">
      <div class="admin-card-name"><?= e($m['name']) ?></div>
      <div class="admin-card-accent"><?= e($m['position']) ?></div>
      <div class="admin-card-actions">
        <a href="<?= url('admin/team/' . $m['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        <form action="<?= url('admin/team/' . $m['id'] . '/delete') ?>" method="POST">
          <?= csrf() ?>
          <button data-confirm="Remove <?= e($m['name']) ?>?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if (empty($members)): ?>
  <div class="admin-card admin-empty" style="grid-column:1/-1">
    <i class="bi bi-people" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:1rem"></i>
    <a href="<?= url('admin/team/create') ?>" class="admin-btn admin-btn-primary">Add First Member</a>
  </div>
  <?php endif; ?>
</div>
