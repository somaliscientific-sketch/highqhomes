<?php
$pageTitle = 'Security Settings';
$loginUrl = Security::adminLoginUrl();
$idleHours = (int)($security['session_hours'] ?? 2);
?>

<header class="admin-security-head">
  <div>
    <span class="admin-report-kicker">System</span>
    <h2>Security settings</h2>
    <p>Login protection, session policy, audit retention, and maintenance mode.</p>
  </div>
</header>

<div class="admin-grid-2">
  <div class="admin-card admin-card-body admin-security-info">
    <h3 class="admin-section-title"><i class="bi bi-shield-check"></i> Active protections</h3>
    <ul class="admin-security-info__list">
      <li><i class="bi bi-check-circle-fill"></i> CSRF tokens on all admin forms</li>
      <li><i class="bi bi-check-circle-fill"></i> Session fingerprint validation</li>
      <li><i class="bi bi-check-circle-fill"></i> Secure HTTP-only cookies</li>
      <li><i class="bi bi-check-circle-fill"></i> Obfuscated admin login URL</li>
      <li><i class="bi bi-check-circle-fill"></i> Failed login rate limiting</li>
    </ul>
    <div class="admin-security-info__url">
      <span class="admin-label">Secure login URL</span>
      <code><?= e($loginUrl) ?></code>
      <p class="admin-form-hint">Set via <code>ADMIN_LOGIN_PATH</code> in <code>.env</code></p>
    </div>
  </div>

  <form action="<?= url('admin/security') ?>" method="POST" class="admin-card admin-card-body">
    <?= csrf() ?>
    <h3 class="admin-section-title">Policies</h3>

    <div class="admin-form-group">
      <label class="admin-label" for="security_max_attempts">Max login attempts</label>
      <input type="number" id="security_max_attempts" name="security_max_attempts" min="3" max="10" value="<?= (int)$security['max_attempts'] ?>" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="security_lockout_minutes">Lockout duration (minutes)</label>
      <input type="number" id="security_lockout_minutes" name="security_lockout_minutes" min="5" max="60" value="<?= (int)$security['lockout_minutes'] ?>" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="security_session_hours">Session idle timeout (hours)</label>
      <input type="number" id="security_session_hours" name="security_session_hours" min="1" max="12" value="<?= (int)$security['session_hours'] ?>" class="admin-input">
      <p class="admin-form-hint">Currently <?= $idleHours ?> hour(s) of inactivity before auto sign-out.</p>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="security_audit_retention_days">Audit log retention (days)</label>
      <input type="number" id="security_audit_retention_days" name="security_audit_retention_days" min="7" max="365" value="<?= (int)$security['retention_days'] ?>" class="admin-input">
      <p class="admin-form-hint">Older logs are purged when you save.</p>
    </div>

    <label class="admin-hero-publish-row">
      <span class="toggle-switch">
        <input type="checkbox" name="maintenance_mode" value="1" <?= !empty($security['maintenance']) ? 'checked' : '' ?>>
        <span class="toggle-slider"></span>
      </span>
      <span>
        <strong>Maintenance mode</strong>
        <small>Public site shows maintenance page; admin remains accessible.</small>
      </span>
    </label>

    <button type="submit" class="admin-btn admin-btn-primary admin-btn-block" style="margin-top:1rem">
      <i class="bi bi-check-lg"></i> Save security settings
    </button>
  </form>
</div>
