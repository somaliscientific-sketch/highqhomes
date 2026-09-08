<?php $pageTitle = 'Media Library'; ?>

<div class="admin-media-layout">
  <aside class="admin-media-sidebar admin-card admin-card-body">
    <h3 class="admin-section-title">Folders</h3>
    <nav class="admin-media-folders">
      <a href="<?= url('admin/media') ?>" class="admin-media-folder<?= ($folder ?? '') === '' ? ' is-active' : '' ?>">All files</a>
      <?php foreach ($folders as $f): ?>
      <a href="<?= url('admin/media?folder=' . urlencode($f['folder'])) ?>" class="admin-media-folder<?= ($folder ?? '') === $f['folder'] ? ' is-active' : '' ?>">
        <?= e($f['folder']) ?> <span><?= (int)$f['total'] ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
  </aside>

  <div class="admin-media-main">
    <div class="admin-card admin-card-body admin-media-upload">
      <form action="<?= url('admin/media/upload') ?>" method="POST" enctype="multipart/form-data" class="admin-media-upload-form">
        <?= csrf() ?>
        <div class="admin-form-group">
          <label class="admin-label">Upload files</label>
          <input class="admin-input" type="file" name="files[]" multiple required accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
          <p class="admin-help">Images, videos, PDFs, documents, and icons supported.</p>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Folder</label>
          <input class="admin-input" name="folder" value="<?= e($folder ?: 'media') ?>" placeholder="media">
        </div>
        <button class="admin-btn admin-btn-primary"><i class="bi bi-cloud-upload"></i> Upload</button>
      </form>
    </div>

    <div class="admin-page-toolbar">
      <form method="GET" class="admin-search-bar">
        <?php if (!empty($folder)): ?><input type="hidden" name="folder" value="<?= e($folder) ?>"><?php endif; ?>
        <input class="admin-input" name="q" value="<?= e($q ?? '') ?>" placeholder="Search title, alt, caption, filename...">
        <select class="admin-input" name="type">
          <option value="">All types</option>
          <option value="image" <?= ($type ?? '') === 'image' ? 'selected' : '' ?>>Images</option>
          <option value="video" <?= ($type ?? '') === 'video' ? 'selected' : '' ?>>Videos</option>
          <option value="document" <?= ($type ?? '') === 'document' ? 'selected' : '' ?>>Documents</option>
        </select>
        <button class="admin-btn admin-btn-secondary"><i class="bi bi-search"></i></button>
      </form>
    </div>

    <div class="admin-media-grid admin-media-grid--pro">
      <?php foreach ($items as $item): ?>
      <?php
        $mime = (string)($item['mime_type'] ?? $item['file_type'] ?? '');
        $isImage = str_starts_with($mime, 'image/');
        $isVideo = str_starts_with($mime, 'video/');
      ?>
      <article class="admin-media-card">
        <a href="<?= url('admin/media/' . $item['id']) ?>" class="admin-media-card__preview">
          <?php if ($isImage): ?>
          <img src="<?= e(uploadUrl($item['file_path'])) ?>" alt="<?= e($item['alt_text'] ?? $item['title'] ?? '') ?>" loading="lazy">
          <?php elseif ($isVideo): ?>
          <div class="admin-media-card__icon"><i class="bi bi-play-circle"></i><span>Video</span></div>
          <?php else: ?>
          <div class="admin-media-card__icon"><i class="bi bi-file-earmark-text"></i><span><?= e(strtoupper(pathinfo($item['file_path'], PATHINFO_EXTENSION))) ?></span></div>
          <?php endif; ?>
        </a>
        <div class="admin-media-card__body">
          <strong><?= e($item['title'] ?? 'Untitled') ?></strong>
          <span><?= e($item['folder'] ?? 'media') ?> · <?= number_format(((int)($item['file_size'] ?? 0)) / 1024, 1) ?> KB</span>
          <div class="admin-media-card__actions">
            <a href="<?= url('admin/media/' . $item['id']) ?>" class="admin-btn admin-btn-sm admin-btn-secondary">Details</a>
            <button type="button" class="admin-btn admin-btn-sm admin-btn-ghost" data-copy="<?= e(uploadUrl($item['file_path'])) ?>">Copy URL</button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <?php if (empty($items)): ?>
    <div class="admin-empty">No media files found.</div>
    <?php endif; ?>

    <?= paginate(compact('total','per_page','current','last_page'), url('admin/media') . '?' . http_build_query(array_filter(['q' => $q ?? '', 'folder' => $folder ?? '', 'type' => $type ?? '']))) ?>
  </div>
</div>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
