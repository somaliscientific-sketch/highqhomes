<?php $pageTitle = 'Gallery'; ?>

<?php if (canManage()): ?>
<div class="admin-card admin-card-body mb-6">
  <h3 class="admin-section-title">Upload Images</h3>
  <form action="<?= url('admin/gallery/upload') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf() ?>
    <div class="grid sm:grid-cols-3 gap-4 items-end">
      <div class="sm:col-span-2 admin-form-group">
        <label class="admin-label">Select Images</label>
        <input type="file" name="images[]" multiple accept="image/*" required class="admin-input">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Category</label>
        <input type="text" name="category" class="admin-input" placeholder="e.g. exterior" list="cats">
        <datalist id="cats">
          <?php foreach ($categories as $cat): ?>
          <option value="<?= e($cat) ?>">
          <?php endforeach; ?>
        </datalist>
      </div>
    </div>
    <button type="submit" class="admin-btn admin-btn-primary" style="margin-top:0.5rem"><i class="bi bi-cloud-upload"></i> Upload</button>
  </form>
</div>
<?php endif; ?>

<div class="admin-page-toolbar">
  <p class="admin-page-meta"><?= $total ?> image(s)</p>
</div>

<?php if (empty($items)): ?>
<div class="admin-card admin-empty">
  <i class="bi bi-images" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:1rem"></i>
  <h3 style="color:var(--admin-muted)">No images yet — upload some above</h3>
</div>
<?php else: ?>
<div class="admin-gallery-grid">
  <?php foreach ($items as $item): ?>
  <div class="admin-gallery-item">
    <img src="<?= e(uploadUrl($item['image'])) ?>" alt="<?= e($item['title'] ?? '') ?>">
    <div class="admin-gallery-overlay">
      <?php if ($item['category']): ?>
      <span class="badge badge-primary"><?= e($item['category']) ?></span>
      <?php endif; ?>
      <?php if (canManage()): ?>
      <form action="<?= url('admin/gallery/' . $item['id'] . '/toggle') ?>" method="POST" class="admin-inline-form">
        <?= csrf() ?>
        <button class="badge <?= $item['is_published'] ? 'badge-success' : 'badge-warning' ?>" style="border:none;cursor:pointer">
          <?= $item['is_published'] ? 'Live' : 'Draft' ?>
        </button>
      </form>
      <form action="<?= url('admin/gallery/' . $item['id'] . '/delete') ?>" method="POST" class="admin-inline-form" style="margin-left:auto">
        <?= csrf() ?>
        <button data-confirm="Delete this image?" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-x"></i></button>
      </form>
      <?php else: ?>
      <span class="badge <?= $item['is_published'] ? 'badge-success' : 'badge-warning' ?>"><?= $item['is_published'] ? 'Live' : 'Draft' ?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?= paginate(['last_page'=>$last_page,'current'=>$current,'total'=>$total,'per_page'=>$per_page], url('admin/gallery')) ?>
<?php endif; ?>
