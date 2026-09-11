<?php $pageTitle = $project ? 'Edit Project' : 'Add Project'; $isEdit = !empty($project); ?>

<div style="max-width:900px">
  <a href="<?= url('admin/projects') ?>" style="color:#6b7280;font-size:0.875rem;text-decoration:none;display:inline-block;margin-bottom:1.5rem"><i class="bi bi-arrow-left"></i> Back to Projects</a>

  <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 flex flex-col gap-5">

        <!-- Basic info -->
        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.5rem">Project Info</h3>

          <div style="margin-bottom:1rem">
            <label class="admin-label">Project Title *</label>
            <input type="text" name="title" value="<?= e($project['title'] ?? '') ?>" required class="admin-input">
          </div>

          <div style="margin-bottom:1rem">
            <label class="admin-label">Short Description</label>
            <textarea name="short_description" rows="2" class="admin-input"><?= e($project['short_description'] ?? '') ?></textarea>
          </div>

          <div style="margin-bottom:1rem">
            <label class="admin-label">Full Description</label>
            <textarea name="description" rows="6" class="admin-input"><?= e($project['description'] ?? '') ?></textarea>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Category</label>
              <select name="category" class="admin-input">
                <?php foreach (['residential','commercial','industrial','mosque','infrastructure','other'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($project['category'] ?? 'residential') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="admin-label">Status</label>
              <select name="status" class="admin-input">
                <?php foreach (['completed'=>'Completed','in_progress'=>'In Progress','planned'=>'Upcoming'] as $val=>$lbl): ?>
                <option value="<?= $val ?>" <?= ($project['status'] ?? 'completed') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="admin-label">Location</label>
              <input type="text" name="location" value="<?= e($project['location'] ?? '') ?>" class="admin-input" placeholder="City, Country">
            </div>
            <div>
              <label class="admin-label">Client Name</label>
              <input type="text" name="client_name" value="<?= e($project['client_name'] ?? '') ?>" class="admin-input">
            </div>
            <div>
              <label class="admin-label">Project Year</label>
              <input type="number" name="project_year" value="<?= e($project['project_year'] ?? date('Y')) ?>" class="admin-input" min="2000" max="2040">
            </div>
            <div>
              <label class="admin-label">Project Area (e.g. 500 m²)</label>
              <input type="text" name="project_area" value="<?= e($project['project_area'] ?? '') ?>" class="admin-input">
            </div>
          </div>
        </div>

        <!-- Images -->
        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Featured Image</h3>
          <?php if (!empty($project['featured_image'])): ?>
          <img id="feat-preview" src="<?= e(projectImageUrl($project)) ?>" style="width:100%;height:220px;object-fit:cover;border-radius:0.5rem;margin-bottom:0.75rem">
          <?php else: ?>
          <div style="height:180px;border:2px dashed #e5e7eb;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;background:#f9fafb">
            <img id="feat-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:0.5rem">
            <span style="color:#9ca3af">No image</span>
          </div>
          <?php endif; ?>
          <input type="file" name="featured_image" accept="image/*" id="feat-file" data-preview-for="feat-preview" style="display:none">
          <label for="feat-file" class="admin-btn" style="admin-btn admin-btn-secondary;cursor:pointer"><i class="bi bi-upload"></i> Upload Featured Image</label>
        </div>

        <!-- Gallery -->
        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Gallery Images</h3>
          <?php if (!empty($project['gallery_images'])): ?>
          <div class="grid grid-cols-3 gap-2 mb-4">
            <?php foreach ($project['gallery_images'] as $gimg): ?>
            <div data-gallery-item style="position:relative;border-radius:0.375rem;overflow:hidden;height:100px">
              <img src="<?= e(str_starts_with($gimg, 'http') ? $gimg : uploadUrl($gimg)) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <input type="file" name="gallery[]" accept="image/*" multiple style="display:block;width:100%" class="admin-input">
          <p style="color:#9ca3af;font-size:0.78rem;margin-top:0.375rem">Select multiple images to add to gallery</p>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="flex flex-col gap-5">
        <div class="admin-card p-5">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Settings</h3>
          <div style="margin-bottom:1rem">
            <label class="admin-label">Sort Order</label>
            <input type="number" name="sort_order" value="<?= e($project['sort_order'] ?? 0) ?>" class="admin-input">
          </div>
          <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 0;border-top:1px solid #f1f5f9">
            <label class="toggle-switch">
              <input type="checkbox" name="is_featured" <?= ($project['is_featured'] ?? 0) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
            <span style="font-weight:600;color:#374151;font-size:0.875rem">Featured on Home</span>
          </div>
          <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 0;border-top:1px solid #f1f5f9">
            <label class="toggle-switch">
              <input type="checkbox" name="is_published" <?= ($project['is_published'] ?? 1) ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
            <span style="font-weight:600;color:#374151;font-size:0.875rem">Published</span>
          </div>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:0.75rem">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Create' ?> Project
        </button>
        <a href="<?= url('admin/projects') ?>" class="admin-btn admin-btn-sm text-center" style="admin-btn admin-btn-secondary;justify-content:center">Cancel</a>
      </div>
    </div>
  </form>
</div>

