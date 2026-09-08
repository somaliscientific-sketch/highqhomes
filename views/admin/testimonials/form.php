<?php $pageTitle = $testimonial ? 'Edit Testimonial' : 'Add Testimonial'; $isEdit = !empty($testimonial); ?>

<div style="max-width:700px">
  <a href="<?= url('admin/testimonials') ?>" style="color:#6b7280;font-size:0.875rem;text-decoration:none;display:inline-block;margin-bottom:1.5rem"><i class="bi bi-arrow-left"></i> Back</a>

  <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 admin-card p-6 flex flex-col gap-4">
        <h3 style="font-weight:700;color:var(--admin-navy)">Testimonial Details</h3>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label class="admin-label">Client Name *</label>
            <input type="text" name="client_name" value="<?= e($testimonial['client_name'] ?? '') ?>" required class="admin-input">
          </div>
          <div>
            <label class="admin-label">Position</label>
            <input type="text" name="position" value="<?= e($testimonial['position'] ?? '') ?>" class="admin-input">
          </div>
          <div>
            <label class="admin-label">Company</label>
            <input type="text" name="company" value="<?= e($testimonial['company'] ?? '') ?>" class="admin-input">
          </div>
          <div>
            <label class="admin-label">Rating (1-5)</label>
            <select name="rating" class="admin-input">
              <?php for ($r=5;$r>=1;$r--): ?>
              <option value="<?= $r ?>" <?= ($testimonial['rating'] ?? 5) == $r ? 'selected' : '' ?>><?= $r ?> Stars</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <div>
          <label class="admin-label">Review / Content *</label>
          <textarea name="content" rows="5" required class="admin-input"><?= e($testimonial['content'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="flex flex-col gap-5">
        <div class="admin-card p-5">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Photo</h3>
          <?php if (!empty($testimonial['image'])): ?>
          <img id="img-preview" src="<?= e(uploadUrl($testimonial['image'])) ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:0.75rem">
          <?php else: ?>
          <div style="width:80px;height:80px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem">
            <img id="img-preview" src="" style="display:none;width:100%;height:100%;border-radius:50%;object-fit:cover">
            <i class="bi bi-person" style="color:#d1d5db;font-size:2rem"></i>
          </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" id="img-file" data-preview-for="img-preview" style="display:none">
          <label for="img-file" class="admin-btn admin-btn-sm" style="admin-btn admin-btn-secondary;cursor:pointer"><i class="bi bi-upload"></i> Photo</label>
        </div>

        <div class="admin-card p-5 flex flex-col gap-3">
          <div style="display:flex;align-items:center;gap:0.75rem">
            <label class="toggle-switch"><input type="checkbox" name="is_featured" <?= ($testimonial['is_featured'] ?? 0) ? 'checked' : '' ?>><span class="toggle-slider"></span></label>
            <span style="font-weight:600;font-size:0.875rem">Featured</span>
          </div>
          <div style="display:flex;align-items:center;gap:0.75rem;border-top:1px solid #f1f5f9;padding-top:0.75rem">
            <label class="toggle-switch"><input type="checkbox" name="is_published" <?= ($testimonial['is_published'] ?? 1) ? 'checked' : '' ?>><span class="toggle-slider"></span></label>
            <span style="font-weight:600;font-size:0.875rem">Published</span>
          </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:0.75rem">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Add' ?> Testimonial
        </button>
      </div>
    </div>
  </form>
</div>

