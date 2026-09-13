<?php $pageTitle = $member ? 'Edit Team Member' : 'Add Team Member'; $isEdit = !empty($member); ?>

<div style="max-width:700px">
  <a href="<?= url('admin/team') ?>" style="color:#6b7280;font-size:0.875rem;text-decoration:none;display:inline-block;margin-bottom:1.5rem"><i class="bi bi-arrow-left"></i> Back to Team</a>

  <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 flex flex-col gap-5">
        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.5rem">Member Details</h3>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Full Name *</label>
              <input type="text" name="name" value="<?= e($member['name'] ?? '') ?>" required class="admin-input">
            </div>
            <div>
              <label class="admin-label">Position / Title *</label>
              <input type="text" name="position" value="<?= e($member['position'] ?? '') ?>" required class="admin-input">
            </div>
            <div>
              <label class="admin-label">Email</label>
              <input type="email" name="email" value="<?= e($member['email'] ?? '') ?>" class="admin-input">
            </div>
            <div>
              <label class="admin-label">Phone</label>
              <input type="tel" name="phone" value="<?= e($member['phone'] ?? '') ?>" class="admin-input">
            </div>
          </div>
          <div style="margin-top:1rem">
            <label class="admin-label">Bio</label>
            <textarea name="bio" rows="4" class="admin-input"><?= e($member['bio'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Social Links</h3>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label"><i class="bi bi-linkedin"></i> LinkedIn URL</label>
              <input type="url" name="linkedin_url" value="<?= e($member['linkedin_url'] ?? '') ?>" class="admin-input" placeholder="https://linkedin.com/in/...">
            </div>
            <div>
              <label class="admin-label"><i class="bi bi-twitter-x"></i> Twitter URL</label>
              <input type="url" name="twitter_url" value="<?= e($member['twitter_url'] ?? '') ?>" class="admin-input" placeholder="https://twitter.com/...">
            </div>
          </div>
        </div>
      </div>

      <div class="flex flex-col gap-5">
        <div class="admin-card p-5">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Photo</h3>
          <?php if (!empty($member['image'])): ?>
          <img id="img-preview" src="<?= e(uploadUrl($member['image'])) ?>" alt="" style="width:100%;height:160px;object-fit:cover;border-radius:0.5rem;margin-bottom:0.75rem">
          <?php else: ?>
          <div style="height:160px;border:2px dashed #e5e7eb;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;background:#f9fafb">
            <img id="img-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:0.5rem">
            <span style="color:#9ca3af;font-size:0.8rem">No photo</span>
          </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" id="img-file" data-preview-for="img-preview" style="display:none">
          <label for="img-file" class="admin-btn w-full" style="admin-btn admin-btn-secondary;cursor:pointer;justify-content:center;width:100%"><i class="bi bi-upload"></i> Upload Photo</label>
        </div>

        <div class="admin-card p-5">
          <div style="margin-bottom:1rem">
            <label class="admin-label">Sort Order</label>
            <input type="number" name="sort_order" value="<?= e($member['sort_order'] ?? 0) ?>" class="admin-input">
          </div>
          <div style="display:flex;align-items:center;gap:0.75rem">
            <label class="toggle-switch">
              <input type="checkbox" name="is_published" <?= ($member['is_published'] ?? 1) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
            <span style="font-weight:600;color:#374151;font-size:0.875rem">Published</span>
          </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:0.75rem">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Add' ?> Member
        </button>
        <a href="<?= url('admin/team') ?>" class="admin-btn admin-btn-sm text-center" style="admin-btn admin-btn-secondary;justify-content:center">Cancel</a>
      </div>
    </div>
  </form>
</div>

