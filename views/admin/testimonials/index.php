<?php $pageTitle = 'Testimonials'; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta"><?= count($testimonials) ?> testimonial(s)</p>
  <a href="<?= url('admin/testimonials/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add Testimonial</a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Client</th>
        <th class="hide-mobile">Rating</th>
        <th class="hide-mobile">Status</th>
        <th style="text-align:right">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($testimonials as $t): ?>
      <tr>
        <td>
          <div class="admin-table-title"><?= e($t['client_name']) ?></div>
          <div class="admin-table-desc"><?= e($t['position'] ?? '') ?> <?= $t['company'] ? '· '.e($t['company']) : '' ?></div>
          <div class="admin-table-desc" style="margin-top:0.25rem"><?= e(truncate($t['content'], 70)) ?></div>
        </td>
        <td class="hide-mobile"><?= stars((int)$t['rating']) ?></td>
        <td class="hide-mobile">
          <span class="badge <?= $t['is_published'] ? 'badge-success' : 'badge-warning' ?>"><?= $t['is_published'] ? 'Published' : 'Draft' ?></span>
          <?php if ($t['is_featured']): ?><span class="badge badge-gold">Featured</span><?php endif; ?>
        </td>
        <td>
          <div class="admin-table-actions">
            <a href="<?= url('admin/testimonials/' . $t['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i></a>
            <form action="<?= url('admin/testimonials/' . $t['id'] . '/delete') ?>" method="POST">
              <?= csrf() ?>
              <button data-confirm="Delete this testimonial?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
