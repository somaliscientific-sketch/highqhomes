<?php
$pageTitle = $paint ? 'Edit Paint Product' : 'Add Paint Product';
$isEdit = !empty($paint);

// Format features and specs for textarea
$featuresText = '';
$specsText = '';
if ($isEdit) {
    $feats = is_string($paint['features'] ?? '') ? json_decode($paint['features'], true) ?? [] : ($paint['features'] ?? []);
    $specs = is_string($paint['specifications'] ?? '') ? json_decode($paint['specifications'], true) ?? [] : ($paint['specifications'] ?? []);
    $featuresText = implode("\n", $feats);
    foreach ($specs as $k => $v) $specsText .= "$k: $v\n";
    $specsText = trim($specsText);
}
?>

<div style="max-width:800px">
  <a href="<?= url('admin/paints') ?>" style="color:#6b7280;font-size:0.875rem;text-decoration:none;display:inline-block;margin-bottom:1.5rem"><i class="bi bi-arrow-left"></i> Back to Paints</a>

  <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 flex flex-col gap-5">

        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.5rem">Product Info</h3>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Product Name *</label>
              <input type="text" name="name" value="<?= e($paint['name'] ?? '') ?>" required class="admin-input">
            </div>
            <div>
              <label class="admin-label">Brand</label>
              <input type="text" name="brand" value="<?= e($paint['brand'] ?? '') ?>" class="admin-input" placeholder="e.g. Saveto">
            </div>
            <div>
              <label class="admin-label">Category</label>
              <input type="text" name="category" value="<?= e($paint['category'] ?? '') ?>" class="admin-input" placeholder="e.g. Exterior">
            </div>
            <div>
              <label class="admin-label">Price</label>
              <input type="text" name="price" value="<?= e($paint['price'] ?? '') ?>" class="admin-input" placeholder="e.g. $25.00">
            </div>
            <div>
              <label class="admin-label">Unit</label>
              <input type="text" name="unit" value="<?= e($paint['unit'] ?? '') ?>" class="admin-input" placeholder="e.g. per 50kg bag">
            </div>
          </div>
          <div style="margin-top:1rem">
            <label class="admin-label">Short Description</label>
            <textarea name="short_description" rows="2" class="admin-input"><?= e($paint['short_description'] ?? '') ?></textarea>
          </div>
          <div style="margin-top:1rem">
            <label class="admin-label">Full Description</label>
            <textarea name="description" rows="5" class="admin-input"><?= e($paint['description'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Features &amp; Specs</h3>
          <div style="margin-bottom:1rem">
            <label class="admin-label">Key Features (one per line)</label>
            <textarea name="features_text" rows="5" class="admin-input" placeholder="Weather resistant&#10;Easy to apply&#10;Long lasting"><?= e($featuresText) ?></textarea>
          </div>
          <div>
            <label class="admin-label">Specifications (Key: Value per line)</label>
            <textarea name="specs_text" rows="6" class="admin-input" placeholder="Coverage: 18-20 m² per 50kg&#10;Dry Time: 2-4 hours&#10;Application: Steel trowel"><?= e($specsText) ?></textarea>
          </div>
        </div>

        <!-- Image -->
        <div class="admin-card p-6">
          <h3 style="font-weight:700;color:var(--admin-navy);margin-bottom:1.25rem">Product Image</h3>
          <?php if (!empty($paint['featured_image'])): ?>
          <img id="img-preview" src="<?= e(uploadUrl($paint['featured_image'])) ?>" style="width:100%;height:220px;object-fit:contain;border-radius:0.5rem;margin-bottom:0.75rem;background:#f9fafb">
          <?php else: ?>
          <div style="height:180px;border:2px dashed #e5e7eb;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;background:#f9fafb">
            <img id="img-preview" src="" style="display:none;width:100%;height:100%;object-fit:contain">
            <span style="color:#9ca3af">No image</span>
          </div>
          <?php endif; ?>
          <input type="file" name="featured_image" accept="image/*" id="img-file" data-preview-for="img-preview" style="display:none">
          <label for="img-file" class="admin-btn" style="admin-btn admin-btn-secondary;cursor:pointer"><i class="bi bi-upload"></i> Upload Image</label>
        </div>
      </div>

      <div class="flex flex-col gap-5">
        <div class="admin-card p-5">
          <div style="margin-bottom:1rem">
            <label class="admin-label">Sort Order</label>
            <input type="number" name="sort_order" value="<?= e($paint['sort_order'] ?? 0) ?>" class="admin-input">
          </div>
          <div style="display:flex;align-items:center;gap:0.75rem">
            <label class="toggle-switch"><input type="checkbox" name="is_published" <?= ($paint['is_published'] ?? 1) ? 'checked' : '' ?>><span class="toggle-slider"></span></label>
            <span style="font-weight:600;font-size:0.875rem">Published</span>
          </div>
        </div>
        <button type="submit" class="admin-btn admin-btn-primary" style="width:100%;justify-content:center;padding:0.75rem">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Update' : 'Create' ?> Product
        </button>
        <a href="<?= url('admin/paints') ?>" class="admin-btn admin-btn-sm text-center" style="admin-btn admin-btn-secondary;justify-content:center">Cancel</a>
      </div>
    </div>
  </form>
</div>

