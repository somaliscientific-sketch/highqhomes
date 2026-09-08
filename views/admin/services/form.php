<?php $pageTitle = $service ? 'Edit Service' : 'Add Service'; $isEdit = !empty($service); ?>

<div class="admin-form-wrap">
  <a href="<?= url('admin/services') ?>" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Services</a>

  <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="admin-form-layout">
      <div class="admin-form-main">

        <div class="admin-card admin-card-body">
          <h3 class="admin-section-title">Service Details</h3>

          <div class="admin-form-group">
            <label class="admin-label">Service Title *</label>
            <input type="text" name="title" value="<?= e($service['title'] ?? '') ?>" required class="admin-input">
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Short Description</label>
            <textarea name="short_description" rows="2" class="admin-input admin-textarea"><?= e($service['short_description'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Full Description</label>
            <textarea name="description" rows="6" class="admin-input admin-textarea"><?= e($service['description'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Icon (Bootstrap Icons class)</label>
            <div class="admin-icon-grid">
              <?php
              $icons = ['bi-buildings','bi-house-door','bi-lamp','bi-tree','bi-geo','bi-palette','bi-hammer','bi-bricks','bi-building','bi-grid-3x3'];
              foreach ($icons as $icon):
              $checked = ($service['icon'] ?? 'bi-buildings') === $icon;
              ?>
              <label class="admin-icon-option <?= $checked ? 'selected' : '' ?>">
                <input type="radio" value="<?= $icon ?>" <?= $checked ? 'checked' : '' ?> onchange="document.querySelectorAll('.admin-icon-option').forEach(l=>l.classList.remove('selected'));this.closest('.admin-icon-option').classList.add('selected');document.getElementById('icon-text').value=this.value">
                <i class="bi <?= $icon ?>"></i>
              </label>
              <?php endforeach; ?>
            </div>
            <input type="text" id="icon-text" name="icon" value="<?= e($service['icon'] ?? 'bi-buildings') ?>" class="admin-input" placeholder="or type icon class: bi-building">
          </div>
        </div>

        <div class="admin-card admin-card-body">
          <h3 class="admin-section-title">Service Image</h3>
          <?php if (!empty($service['image'])): ?>
          <img id="img-preview" src="<?= e(uploadUrl($service['image'])) ?>" alt="" class="admin-image-preview--cover">
          <?php else: ?>
          <div class="admin-image-zone">
            <img id="img-preview" src="" alt="" class="admin-image-preview" style="display:none">
            <span>No image</span>
          </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" id="img-file" data-preview-for="img-preview" class="admin-file-input">
          <label for="img-file" class="admin-btn admin-btn-secondary admin-upload-btn"><i class="bi bi-upload"></i> Upload Image</label>
        </div>
      </div>

      <div class="admin-form-sidebar">
        <div class="admin-card admin-card-body">
          <h3 class="admin-section-title">Settings</h3>

          <div class="admin-form-group">
            <label class="admin-label">Sort Order</label>
            <input type="number" name="sort_order" value="<?= e($service['sort_order'] ?? 0) ?>" class="admin-input">
          </div>

          <div class="admin-form-row">
            <label class="toggle-switch">
              <input type="checkbox" name="is_featured" <?= ($service['is_featured'] ?? 0) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
            <span class="admin-form-row-label">Featured</span>
          </div>

          <div class="admin-form-row">
            <label class="toggle-switch">
              <input type="checkbox" name="is_published" <?= ($service['is_published'] ?? 1) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
            <span class="admin-form-row-label">Published</span>
          </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary admin-btn-block">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Create' ?> Service
        </button>
        <a href="<?= url('admin/services') ?>" class="admin-btn admin-btn-secondary admin-btn-block">Cancel</a>
      </div>
    </div>
  </form>
</div>

