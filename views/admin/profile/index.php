<?php $pageTitle = 'My Account'; ?>

<div class="admin-profile-head">
  <div>
    <span class="admin-report-kicker">Account</span>
    <h2>Profile &amp; security</h2>
    <p>Update your details and password. Sessions expire after <?= (int)$sessionHours ?> hour(s) of inactivity.</p>
  </div>
</div>

<div class="admin-grid-2">
  <div class="admin-card admin-card-body">
    <h3 class="admin-section-title">Profile</h3>
    <form action="<?= url('admin/profile') ?>" method="POST">
      <?= csrf() ?>
      <div class="admin-form-group">
        <label class="admin-label">Full name</label>
        <input class="admin-input" name="name" value="<?= e($user['name']) ?>" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Email</label>
        <input class="admin-input" type="email" name="email" value="<?= e($user['email']) ?>" required>
      </div>
      <dl class="admin-dl admin-dl--inline">
        <div><dt>Role</dt><dd><span class="badge badge-primary"><?= e(Auth::roleLabel()) ?></span></dd></div>
        <div><dt>Last login</dt><dd><?= !empty($user['last_login']) ? e(date('M j, Y g:i A', strtotime($user['last_login']))) : '—' ?></dd></div>
      </dl>
      <button class="admin-btn admin-btn-primary">Save profile</button>
    </form>
  </div>

  <div class="admin-card admin-card-body">
    <h3 class="admin-section-title">Change password</h3>
    <form action="<?= url('admin/profile/password') ?>" method="POST">
      <?= csrf() ?>
      <div class="admin-form-group">
        <label class="admin-label">Current password</label>
        <input class="admin-input" type="password" name="current_password" required autocomplete="current-password">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">New password</label>
        <input class="admin-input" type="password" name="new_password" required minlength="10" autocomplete="new-password">
        <p class="admin-help">Minimum 10 characters.</p>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Confirm new password</label>
        <input class="admin-input" type="password" name="new_password_confirmation" required minlength="10" autocomplete="new-password">
      </div>
      <button class="admin-btn admin-btn-secondary">Update password</button>
    </form>
  </div>
</div>

<div class="admin-card admin-card-body admin-security-note">
  <h3 class="admin-section-title"><i class="bi bi-shield-lock"></i> Security tips</h3>
  <ul>
    <li>Use a unique password not shared with other services.</li>
    <li>Sign out when using a shared computer.</li>
    <li>Contact a Super Admin if you suspect unauthorized access.</li>
  </ul>
  <form action="<?= url('admin/logout') ?>" method="POST" class="admin-profile-logout">
    <?= csrf() ?>
    <button type="submit" class="admin-btn admin-btn-danger"><i class="bi bi-box-arrow-right"></i> Sign out</button>
  </form>
</div>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
