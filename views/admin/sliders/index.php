<?php
$pageTitle = 'Hero Sliders';
$liveCount = count(array_filter($heroSlots, fn($s) => !empty($s['is_published'])));
$filledCount = count(array_filter($heroSlots));
$activeSlider = $heroSlots[$activeTab - 1] ?? null;
?>

<div class="admin-hero-manager">
  <header class="admin-hero-manager__head">
    <div class="admin-hero-manager__intro">
      <span class="admin-report-kicker">Homepage</span>
      <h2>Hero sections</h2>
      <p>Four carousel slides — edit content, imagery, and behaviour from one place.</p>
    </div>
    <div class="admin-hero-manager__stats">
      <div class="admin-hero-stat">
        <strong><?= $filledCount ?><span>/4</span></strong>
        <span>Configured</span>
      </div>
      <div class="admin-hero-stat admin-hero-stat--live">
        <strong><?= $liveCount ?></strong>
        <span>Live</span>
      </div>
      <div class="admin-hero-stat admin-hero-stat--muted">
        <strong><?= 4 - $filledCount ?></strong>
        <span>Empty slots</span>
      </div>
    </div>
  </header>

  <?php if (!canManage()): ?>
  <div class="admin-flash admin-flash--info" style="margin-bottom:1rem"><i class="bi bi-eye"></i><span>View-only access — you can preview hero sections but cannot edit them.</span></div>
  <?php endif; ?>

  <?php if (canManage()): ?>
  <details class="admin-hero-settings" open>
    <summary class="admin-hero-settings__toggle">
      <span><i class="bi bi-sliders"></i> Carousel settings</span>
      <i class="bi bi-chevron-down admin-hero-settings__chevron"></i>
    </summary>
    <form action="<?= url('admin/sliders/settings') ?>" method="POST" class="admin-hero-settings__body">
      <?= csrf() ?>
      <input type="hidden" name="hero_tab" value="<?= (int)$activeTab ?>">
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
            <span>Seconds per slide.</span>
          </span>
          <select name="hero_carousel_interval" class="admin-input admin-input--compact">
            <?php foreach ([5 => '5s', 6 => '6s', 8 => '8s', 10 => '10s'] as $val => $label): ?>
            <option value="<?= $val ?>" <?= (int)($heroOptions['interval'] ?? 6) === $val ? 'selected' : '' ?>><?= $label ?></option>
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
            <span>Stop when cursor is over hero.</span>
          </span>
          <span class="toggle-switch admin-hero-option__toggle">
            <input type="checkbox" name="hero_carousel_pause_hover" value="1" <?= !empty($heroOptions['pause_hover']) ? 'checked' : '' ?>>
            <span class="toggle-slider"></span>
          </span>
        </label>
      </div>
      <div class="admin-hero-settings__foot">
        <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-check-lg"></i> Save carousel settings</button>
      </div>
    </form>
  </details>
  <?php else: ?>
  <div class="admin-card admin-card-body mb-4">
    <h4 class="admin-card-title">Carousel settings</h4>
    <p class="admin-table-desc">Auto-play: <?= !empty($heroOptions['autoplay']) ? 'On' : 'Off' ?> · Interval: <?= (int)($heroOptions['interval'] ?? 6) ?>s · Dots: <?= !empty($heroOptions['dots']) ? 'On' : 'Off' ?></p>
  </div>
  <?php endif; ?>

  <div class="admin-hero-manager__shell">
    <nav class="admin-hero-tabs" aria-label="Hero sections">
      <?php for ($t = 1; $t <= 4; $t++): ?>
      <?php
        $slotSlider = $heroSlots[$t - 1] ?? null;
        $tabState = !$slotSlider ? 'empty' : (!empty($slotSlider['is_published']) ? 'live' : 'draft');
        $tabTitle = $slotSlider ? truncate($slotSlider['title'], 32) : 'Empty slot';
        $tabImg = '';
        if (!empty($slotSlider['image'])) {
            $tabImg = str_starts_with($slotSlider['image'], 'http') ? $slotSlider['image'] : uploadUrl($slotSlider['image']);
        }
      ?>
      <a
        href="<?= url('admin/sliders?tab=' . $t) ?>"
        class="admin-hero-tab<?= $activeTab === $t ? ' is-active' : '' ?> admin-hero-tab--<?= e($tabState) ?>"
        aria-current="<?= $activeTab === $t ? 'page' : 'false' ?>"
      >
        <span class="admin-hero-tab__thumb" aria-hidden="true">
          <?php if ($tabImg): ?>
          <img src="<?= e($tabImg) ?>" alt="">
          <?php else: ?>
          <i class="bi bi-image"></i>
          <?php endif; ?>
          <span class="admin-hero-tab__index"><?= $t ?></span>
        </span>
        <span class="admin-hero-tab__body">
          <span class="admin-hero-tab__label">Section <?= $t ?></span>
          <span class="admin-hero-tab__title"><?= e($tabTitle) ?></span>
          <span class="admin-hero-tab__status">
            <?php if ($tabState === 'live'): ?>
            <i class="bi bi-broadcast"></i> Live
            <?php elseif ($tabState === 'draft'): ?>
            <i class="bi bi-eye-slash"></i> Draft
            <?php else: ?>
            <i class="bi bi-plus-circle"></i> Add content
            <?php endif; ?>
          </span>
        </span>
      </a>
      <?php endfor; ?>
    </nav>

    <section class="admin-hero-tab-panel">
      <header class="admin-hero-tab-panel__head">
        <div>
          <h3>Hero section <?= (int)$activeTab ?></h3>
          <p><?= $activeSlider ? 'Edit slide content and preview how it appears on the homepage.' : 'This slot is empty — add a headline, image, and CTAs below.' ?></p>
        </div>
        <?php if ($activeSlider): ?>
        <span class="admin-hero-tab-panel__badge admin-hero-tab-panel__badge--<?= !empty($activeSlider['is_published']) ? 'live' : 'draft' ?>">
          <?= !empty($activeSlider['is_published']) ? 'Published' : 'Draft' ?>
        </span>
        <?php endif; ?>
      </header>

      <?php if (canManage()): ?>
      <?php
        $slider = $activeSlider;
        $slot = $activeTab;
        require __DIR__ . '/_panel.php';
      ?>
      <?php elseif ($activeSlider): ?>
      <div class="admin-card admin-card-body">
        <h4><?= e($activeSlider['title'] ?? 'Untitled') ?></h4>
        <p><?= e($activeSlider['subtitle'] ?? '') ?></p>
        <?php if (!empty($activeSlider['image'])): ?>
        <img src="<?= e(uploadUrl($activeSlider['image'])) ?>" alt="" style="max-width:100%;border-radius:8px;margin-top:1rem">
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="admin-empty">This hero slot is empty.</div>
      <?php endif; ?>
    </section>
  </div>
</div>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
