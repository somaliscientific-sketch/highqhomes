<?php
$pageTitle = 'Hero Slider';
$liveCount = count(array_filter($sliders, static fn($s) => !empty($s['is_published'])));
$now = time();
$slideImg = static function (?string $path): string {
    $path = trim((string)$path);
    if ($path === '') {
        return '';
    }
    return str_starts_with($path, 'http') ? $path : uploadUrl($path);
};
$slideState = static function (array $slide) use ($now): string {
    if (empty($slide['is_published'])) {
        return 'draft';
    }
    $start = !empty($slide['start_date']) ? strtotime((string)$slide['start_date']) : null;
    $end = !empty($slide['end_date']) ? strtotime((string)$slide['end_date']) : null;
    if ($start && $start > $now) {
        return 'scheduled';
    }
    if ($end && $end < $now) {
        return 'expired';
    }
    return 'live';
};
?>

<div class="admin-hero-manager">
  <header class="admin-hero-manager__head">
    <div class="admin-hero-manager__intro">
      <span class="admin-report-kicker">Website content</span>
      <h2>Hero Slider</h2>
      <p>Add, edit, reorder, and publish homepage slides. The public site shows only active slides, in this order.</p>
    </div>
    <div class="admin-hero-manager__stats">
      <div class="admin-hero-stat">
        <strong><?= count($sliders) ?></strong>
        <span>Slides</span>
      </div>
      <div class="admin-hero-stat admin-hero-stat--live">
        <strong><?= $liveCount ?></strong>
        <span>Published</span>
      </div>
      <div class="admin-hero-stat admin-hero-stat--muted">
        <strong><?= max(0, count($sliders) - $liveCount) ?></strong>
        <span>Drafts</span>
      </div>
    </div>
  </header>

  <?php if (!canManage()): ?>
  <div class="admin-flash admin-flash--info" style="margin-bottom:1rem"><i class="bi bi-eye"></i><span>View-only access — you can preview slides but cannot edit them.</span></div>
  <?php endif; ?>

  <?php if (canManage()): ?>
  <details class="admin-hero-settings" open>
    <summary class="admin-hero-settings__toggle">
      <span><i class="bi bi-sliders"></i> Slider playback</span>
      <i class="bi bi-chevron-down admin-hero-settings__chevron"></i>
    </summary>
    <form action="<?= url('admin/sliders/settings') ?>" method="POST" class="admin-hero-settings__body">
      <?= csrf() ?>
      <div class="admin-hero-options__grid">
        <label class="admin-hero-option">
          <span class="admin-hero-option__icon"><i class="bi bi-play-circle"></i></span>
          <span class="admin-hero-option__copy">
            <strong>Auto-play</strong>
            <span>Rotate slides automatically.</span>
          </span>
          <span class="toggle-switch admin-hero-option__toggle">
            <input type="checkbox" name="hero_carousel_autoplay" value="1" <?= !empty($heroOptions['autoplay']) ? 'checked' : '' ?>>
            <span class="toggle-slider"></span>
          </span>
        </label>

        <label class="admin-hero-option admin-hero-option--select">
          <span class="admin-hero-option__icon"><i class="bi bi-stopwatch"></i></span>
          <span class="admin-hero-option__copy">
            <strong>Duration</strong>
            <span>Default seconds per slide.</span>
          </span>
          <select name="hero_carousel_interval" class="admin-input admin-input--compact">
            <?php foreach ([4 => '4s', 5 => '5s', 6 => '6s', 8 => '8s', 10 => '10s', 12 => '12s'] as $val => $label): ?>
            <option value="<?= $val ?>" <?= (int)($heroOptions['interval'] ?? 6) === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </label>

        <label class="admin-hero-option admin-hero-option--select">
          <span class="admin-hero-option__icon"><i class="bi bi-layers"></i></span>
          <span class="admin-hero-option__copy">
            <strong>Transition</strong>
            <span>Default animation style.</span>
          </span>
          <select name="hero_carousel_transition" class="admin-input admin-input--compact">
            <?php foreach (['kenburns' => 'Ken Burns', 'fade' => 'Fade', 'slide' => 'Slide'] as $val => $label): ?>
            <option value="<?= $val ?>" <?= ($heroOptions['transition'] ?? 'kenburns') === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </label>

        <label class="admin-hero-option">
          <span class="admin-hero-option__icon"><i class="bi bi-record-circle"></i></span>
          <span class="admin-hero-option__copy">
            <strong>Progress dots</strong>
            <span>Show slide indicators.</span>
          </span>
          <span class="toggle-switch admin-hero-option__toggle">
            <input type="checkbox" name="hero_carousel_dots" value="1" <?= !empty($heroOptions['dots']) ? 'checked' : '' ?>>
            <span class="toggle-slider"></span>
          </span>
        </label>

        <label class="admin-hero-option">
          <span class="admin-hero-option__icon"><i class="bi bi-pause-circle"></i></span>
          <span class="admin-hero-option__copy">
            <strong>Pause on hover</strong>
            <span>Stop when the cursor is over the hero.</span>
          </span>
          <span class="toggle-switch admin-hero-option__toggle">
            <input type="checkbox" name="hero_carousel_pause_hover" value="1" <?= !empty($heroOptions['pause_hover']) ? 'checked' : '' ?>>
            <span class="toggle-slider"></span>
          </span>
        </label>
      </div>
      <div class="admin-hero-settings__foot">
        <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-check-lg"></i> Save playback settings</button>
      </div>
    </form>
  </details>
  <?php endif; ?>

  <div class="admin-page-toolbar">
    <p class="admin-page-meta">Drag the handle to change homepage order. Only published slides appear on the site.</p>
    <?php if (canManage()): ?>
    <a href="<?= url('admin/sliders/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add slide</a>
    <?php endif; ?>
  </div>

  <div class="admin-card admin-hero-board">
    <?php if (!$sliders): ?>
    <div class="admin-empty">
      <p>No hero slides yet. Add the first slide to control the homepage banner from the CMS.</p>
      <?php if (canManage()): ?>
      <a href="<?= url('admin/sliders/create') ?>" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i> Add slide</a>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <table class="admin-table admin-table--pro admin-hero-table">
      <thead>
        <tr>
          <?php if (canManage()): ?><th class="admin-hero-table__grip" aria-label="Reorder"></th><?php endif; ?>
          <th>Image</th>
          <th>Title</th>
          <th class="hide-mobile">Order</th>
          <th>Status</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody data-hero-sortable <?= canManage() ? 'data-reorder-url="' . e(url('admin/sliders/reorder')) . '"' : '' ?>>
        <?php foreach ($sliders as $index => $slide): ?>
        <?php
          $img = $slideImg($slide['image'] ?? '');
          $state = $slideState($slide);
          $stateLabel = ['live' => 'Active', 'draft' => 'Draft', 'scheduled' => 'Scheduled', 'expired' => 'Ended'][$state];
        ?>
        <tr data-admin-list-item data-hero-id="<?= (int)$slide['id'] ?>">
          <?php if (canManage()): ?>
          <td class="admin-hero-table__grip">
            <button type="button" class="admin-hero-drag" data-hero-handle aria-label="Drag to reorder" title="Drag to reorder">
              <i class="bi bi-grip-vertical" aria-hidden="true"></i>
            </button>
          </td>
          <?php endif; ?>
          <td>
            <span class="admin-hero-thumb">
              <?php if ($img): ?>
              <img src="<?= e($img) ?>" alt="">
              <?php else: ?>
              <i class="bi bi-image" aria-hidden="true"></i>
              <?php endif; ?>
            </span>
          </td>
          <td>
            <div class="admin-table-title"><?= e($slide['title'] ?? 'Untitled slide') ?></div>
            <div class="admin-table-desc"><?= e(truncate($slide['subtitle'] ?? '', 72)) ?></div>
          </td>
          <td class="hide-mobile"><span class="badge badge-primary" data-hero-order><?= $index + 1 ?></span></td>
          <td>
            <span class="badge <?= $state === 'live' ? 'badge-success' : ($state === 'draft' ? 'badge-warning' : 'badge-primary') ?>">
              <?= e($stateLabel) ?>
            </span>
          </td>
          <td>
            <?php if (canManage()): ?>
            <div class="admin-table-actions">
              <a href="<?= url('admin/sliders/' . $slide['id'] . '/edit') ?>" class="admin-btn admin-btn-primary admin-btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
              <form action="<?= url('admin/sliders/' . $slide['id'] . '/duplicate') ?>" method="POST">
                <?= csrf() ?>
                <button class="admin-btn admin-btn-secondary admin-btn-sm" title="Duplicate"><i class="bi bi-copy"></i></button>
              </form>
              <form action="<?= url('admin/sliders/' . $slide['id'] . '/toggle') ?>" method="POST">
                <?= csrf() ?>
                <button class="admin-btn admin-btn-secondary admin-btn-sm" title="<?= !empty($slide['is_published']) ? 'Unpublish' : 'Publish' ?>">
                  <i class="bi bi-<?= !empty($slide['is_published']) ? 'eye-slash' : 'eye' ?>"></i>
                </button>
              </form>
              <form action="<?= url('admin/sliders/' . $slide['id'] . '/delete') ?>" method="POST">
                <?= csrf() ?>
                <button data-confirm="Delete “<?= e($slide['title'] ?? 'this slide') ?>”? This cannot be undone." class="admin-btn admin-btn-danger admin-btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
              </form>
            </div>
            <?php else: ?>
            <span class="admin-table-desc">View only</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
