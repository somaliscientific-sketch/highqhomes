<?php $pageTitle = 'Media Details'; ?>
<?php
$mime = (string)($item['mime_type'] ?? $item['file_type'] ?? '');
$isImage = str_starts_with($mime, 'image/');
$isVideo = str_starts_with($mime, 'video/');
?>

<div class="admin-grid-2">
  <div class="admin-card admin-card-body">
    <div class="admin-media-detail-preview">
      <?php if ($isImage): ?>
      <img src="<?= e(uploadUrl($item['file_path'])) ?>" alt="<?= e($item['alt_text'] ?? '') ?>">
      <?php elseif ($isVideo): ?>
      <video controls src="<?= e(uploadUrl($item['file_path'])) ?>"></video>
      <?php else: ?>
      <div class="admin-media-card__icon admin-media-card__icon--lg"><i class="bi bi-file-earmark"></i><span><?= e($item['original_name'] ?? basename($item['file_path'])) ?></span></div>
      <?php endif; ?>
    </div>
    <p class="admin-help" style="margin-top:1rem"><code><?= e($item['file_path']) ?></code></p>
    <button type="button" class="admin-btn admin-btn-secondary" data-copy="<?= e(uploadUrl($item['file_path'])) ?>"><i class="bi bi-clipboard"></i> Copy URL</button>
  </div>

  <div class="admin-card admin-card-body">
    <form action="<?= url('admin/media/' . $item['id'] . '/update') ?>" method="POST">
      <?= csrf() ?>
      <div class="admin-form-group">
        <label class="admin-label">Title</label>
        <input class="admin-input" name="title" value="<?= e($item['title'] ?? '') ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Alt text</label>
        <input class="admin-input" name="alt_text" value="<?= e($item['alt_text'] ?? '') ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Caption</label>
        <textarea class="admin-input" name="caption" rows="2"><?= e($item['caption'] ?? '') ?></textarea>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Folder</label>
        <input class="admin-input" name="folder" value="<?= e($item['folder'] ?? 'media') ?>">
      </div>
      <button class="admin-btn admin-btn-primary">Save details</button>
    </form>

    <hr style="margin:1.5rem 0;border-color:rgba(2,29,69,.08)">

    <form action="<?= url('admin/media/' . $item['id'] . '/replace') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf() ?>
      <div class="admin-form-group">
        <label class="admin-label">Replace file</label>
        <input class="admin-input" type="file" name="file" required>
      </div>
      <button class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-repeat"></i> Replace</button>
    </form>

    <hr style="margin:1.5rem 0;border-color:rgba(2,29,69,.08)">

    <h3 class="admin-section-title">Usage tracking</h3>
    <?php if (empty($usage)): ?>
    <p class="admin-help">This file is not linked in the usage tracker yet.</p>
    <?php else: ?>
    <ul class="admin-usage-list">
      <?php foreach ($usage as $use): ?>
      <li><strong><?= e($use['entity_type']) ?></strong> #<?= (int)$use['entity_id'] ?><?= $use['field_name'] ? ' · ' . e($use['field_name']) : '' ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form action="<?= url('admin/media/' . $item['id'] . '/delete') ?>" method="POST" style="margin-top:1.5rem">
      <?= csrf() ?>
      <button data-confirm="Delete this media file permanently?" class="admin-btn admin-btn-danger"><i class="bi bi-trash"></i> Delete</button>
    </form>
  </div>
</div>

<a href="<?= url('admin/media') ?>" class="admin-btn admin-btn-ghost" style="margin-top:1rem"><i class="bi bi-arrow-left"></i> Back to library</a>
<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
