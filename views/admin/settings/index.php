<?php $pageTitle = 'Site Settings'; ?>

<div class="admin-settings-layout">
  <aside>
    <div class="admin-card overflow-hidden">
      <?php foreach ($groups as $g): ?>
      <?php
      $icons = ['general'=>'bi-gear','identity'=>'bi-badge-ad','contact'=>'bi-telephone','social'=>'bi-share','homepage'=>'bi-house','seo'=>'bi-search','security'=>'bi-shield-lock','appearance'=>'bi-brush'];
      $icon = $icons[$g] ?? 'bi-sliders';
      ?>
      <a href="<?= url('admin/settings') ?>?group=<?= urlencode($g) ?>" class="admin-side-nav-link <?= $group === $g ? 'active' : '' ?>">
        <i class="bi <?= $icon ?>"></i>
        <?= ucfirst($g) ?>
        <?php if ($group === $g): ?><i class="bi bi-chevron-right ml-auto" style="font-size:0.7rem"></i><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>
  </aside>

  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h2 class="admin-card-title" style="text-transform:capitalize"><?= e($group) ?> Settings</h2>
      </div>

      <form action="<?= url('admin/settings') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <input type="hidden" name="group" value="<?= e($group) ?>">

        <div class="admin-form-fields">
          <?php foreach ($fields as $field): ?>
          <div class="admin-form-group">
            <label class="admin-label" for="f_<?= e($field['key']) ?>"><?= e($field['label'] ?? ucfirst(str_replace('_',' ',$field['key']))) ?></label>

            <?php if ($field['type'] === 'textarea'): ?>
            <textarea name="<?= e($field['key']) ?>" id="f_<?= e($field['key']) ?>" rows="4" class="admin-input admin-textarea"><?= e($field['value'] ?? '') ?></textarea>

            <?php elseif ($field['type'] === 'color'): ?>
            <div class="admin-color-input">
              <input type="color" value="<?= e($field['value'] ?? '#000000') ?>" oninput="document.getElementById('c_<?= e($field['key']) ?>').value=this.value">
              <input type="text" name="<?= e($field['key']) ?>" id="c_<?= e($field['key']) ?>" value="<?= e($field['value'] ?? '#000000') ?>" class="admin-input" placeholder="#000000">
            </div>

            <?php elseif ($field['type'] === 'boolean'): ?>
            <div class="admin-form-row" style="border-top:none;padding-top:0">
              <label class="toggle-switch">
                <input type="checkbox" name="<?= e($field['key']) ?>" id="f_<?= e($field['key']) ?>" value="1" <?= ($field['value'] ?? '0') === '1' ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
              </label>
              <span class="admin-form-row-label" style="color:var(--admin-muted)">Enabled</span>
            </div>

            <?php elseif ($field['type'] === 'image'): ?>
            <div class="admin-settings-image">
              <?php if (!empty($field['value'])): ?>
              <div class="admin-settings-image-preview">
                <img src="<?= e(uploadUrl($field['value'])) ?>" alt="">
              </div>
              <?php endif; ?>
              <div>
                <input type="file" name="<?= e($field['key']) ?>" id="f_<?= e($field['key']) ?>" accept="image/*" class="admin-input" style="max-width:320px">
                <p class="admin-form-hint">Upload a new image to replace the current one</p>
              </div>
            </div>

            <?php else: ?>
            <input type="text" name="<?= e($field['key']) ?>" id="f_<?= e($field['key']) ?>" value="<?= e($field['value'] ?? '') ?>" class="admin-input">
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="admin-form-footer">
          <button type="submit" class="admin-btn admin-btn-primary">
            <i class="bi bi-check-lg"></i> Save Settings
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
