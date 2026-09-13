<?php $pageTitle = $menu ? 'Edit Menu Item' : 'Add Menu Item'; ?>

<div class="admin-form-wrap">
  <a href="<?= url('admin/menus') ?>" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Menus</a>

  <form action="<?= e($action) ?>" method="POST">
    <?= csrf() ?>
    <div class="admin-card admin-card-body">
      <div class="admin-form-group">
        <label class="admin-label">Label *</label>
        <input class="admin-input" required name="label" value="<?= e($menu['label'] ?? '') ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">URL *</label>
        <input class="admin-input" required name="url" value="<?= e($menu['url'] ?? '/') ?>" placeholder="/services or https://example.com">
      </div>
      <div class="grid sm:grid-cols-3 gap-4">
        <div class="admin-form-group">
          <label class="admin-label">Location</label>
          <select class="admin-input" name="location">
            <option value="primary" <?= ($menu['location'] ?? '') === 'primary' ? 'selected' : '' ?>>Primary</option>
            <option value="footer" <?= ($menu['location'] ?? '') === 'footer' ? 'selected' : '' ?>>Footer</option>
          </select>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Target</label>
          <select class="admin-input" name="target">
            <option value="_self" <?= ($menu['target'] ?? '') !== '_blank' ? 'selected' : '' ?>>Same tab</option>
            <option value="_blank" <?= ($menu['target'] ?? '') === '_blank' ? 'selected' : '' ?>>New tab</option>
          </select>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Sort Order</label>
          <input class="admin-input" type="number" name="sort_order" value="<?= e($menu['sort_order'] ?? 0) ?>">
        </div>
      </div>
      <div class="admin-form-row">
        <label class="toggle-switch">
          <input type="checkbox" name="is_published" <?= ($menu['is_published'] ?? 1) ? 'checked' : '' ?>>
          <span class="toggle-slider"></span>
        </label>
        <span class="admin-form-row-label">Published</span>
      </div>
    </div>
    <div class="admin-quick-actions" style="margin-top:1rem">
      <button class="admin-btn admin-btn-primary"><i class="bi bi-check-lg"></i> Save Menu Item</button>
      <a href="<?= url('admin/menus') ?>" class="admin-btn admin-btn-secondary">Cancel</a>
    </div>
  </form>
</div>

