<?php $pageTitle = $page ? 'Edit Page' : 'Add Page'; $isEdit = !empty($page); ?>

<form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
  <?= csrf() ?>
  <div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 flex flex-col gap-5">
      <div class="admin-card p-6">
        <label class="admin-label">Title *</label>
        <input class="admin-input mb-4" required name="title" value="<?= e($page['title'] ?? '') ?>">
        <label class="admin-label">Slug</label>
        <input class="admin-input mb-4" name="slug" value="<?= e($page['slug'] ?? '') ?>" placeholder="auto-generated from title">
        <label class="admin-label">Excerpt</label>
        <textarea class="admin-input mb-4" name="excerpt" rows="2"><?= e($page['excerpt'] ?? '') ?></textarea>
        <label class="admin-label">Page Content</label>
        <textarea class="admin-input" name="content" rows="14"><?= e($page['content'] ?? '') ?></textarea>
      </div>
      <div class="admin-card p-6">
        <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1rem">SEO</h3>
        <label class="admin-label">Meta Title</label>
        <input class="admin-input mb-4" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>">
        <label class="admin-label">Meta Description</label>
        <textarea class="admin-input" name="meta_description" rows="3"><?= e($page['meta_description'] ?? '') ?></textarea>
      </div>
    </div>
    <div class="flex flex-col gap-5">
      <div class="admin-card p-5">
        <label class="admin-label">Featured Image</label>
        <?php if (!empty($page['featured_image'])): ?><img src="<?= e(uploadUrl($page['featured_image'])) ?>" style="width:100%;height:170px;object-fit:cover;border-radius:0.5rem;margin-bottom:0.75rem"><?php endif; ?>
        <input type="file" name="featured_image" accept="image/*" class="admin-input mb-4">
        <label class="admin-label">Sort Order</label>
        <input type="number" name="sort_order" value="<?= e($page['sort_order'] ?? 0) ?>" class="admin-input mb-4">
        <label class="admin-label">Template</label>
        <input name="template" value="<?= e($page['template'] ?? 'default') ?>" class="admin-input mb-4">
        <label style="display:flex;align-items:center;gap:0.75rem;font-weight:600;color:#374151"><input type="checkbox" name="is_published" <?= ($page['is_published'] ?? 1) ? 'checked' : '' ?>> Published</label>
      </div>
      <button class="admin-btn admin-btn-primary" style="justify-content:center"><i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Create' ?> Page</button>
      <a href="<?= url('admin/pages') ?>" class="admin-btn" style="admin-btn admin-btn-secondary;justify-content:center">Cancel</a>
    </div>
  </div>
</form>

