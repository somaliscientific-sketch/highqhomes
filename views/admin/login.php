<?php
$success = Session::getFlash('success');
$error = Session::getFlash('error');
$siteName = setting('site_name', 'HighQ Homes');
$tagline = setting('tagline', 'Premium Construction & Architecture');
$legalName = setting('legal_name', $siteName);
$cmsLogo = cmsLogoUrl();
?>

<div class="login-pro">
  <aside class="login-pro__showcase" aria-label="Brand">
    <div class="login-pro__showcase-bg" aria-hidden="true"></div>
    <div class="login-pro__showcase-inner">
      <div class="login-pro__brand-mark">
        <img src="<?= e($cmsLogo) ?>" alt="<?= e($siteName) ?>" class="admin-cms-logo login-pro__logo" width="100" height="100">
        <div>
          <p class="login-pro__brand-label">Content Management System</p>
          <h1 class="login-pro__brand-title"><?= e($siteName) ?></h1>
        </div>
      </div>

      <p class="login-pro__brand-lead"><?= e($tagline) ?></p>

      <div class="login-pro__highlights">
        <article class="login-pro__highlight">
          <i class="bi bi-shield-lock"></i>
          <div>
            <strong>Secure access</strong>
            <span>Encrypted sessions &amp; CSRF protection</span>
          </div>
        </article>
        <article class="login-pro__highlight">
          <i class="bi bi-person-badge"></i>
          <div>
            <strong>Role-based control</strong>
            <span>Admin, editor &amp; viewer permissions</span>
          </div>
        </article>
        <article class="login-pro__highlight">
          <i class="bi bi-journal-check"></i>
          <div>
            <strong>Audit trail</strong>
            <span>Every action logged for accountability</span>
          </div>
        </article>
      </div>
    </div>

    <footer class="login-pro__showcase-foot">
      <span>&copy; <?= date('Y') ?> <?= e($legalName) ?></span>
      <a href="<?= url() ?>"><i class="bi bi-box-arrow-up-right"></i> Public website</a>
    </footer>
  </aside>

  <main class="login-pro__main">
    <a href="<?= url() ?>" class="login-pro__back"><i class="bi bi-arrow-left"></i> Back to website</a>

    <div class="login-pro__mobile-head">
      <img src="<?= e($cmsLogo) ?>" alt="<?= e($siteName) ?>" class="admin-cms-logo login-pro__logo login-pro__logo--sm" width="64" height="64">
      <div>
        <strong><?= e($siteName) ?></strong>
        <span>CMS Sign in</span>
      </div>
    </div>

    <div class="login-pro__panel">
      <header class="login-pro__header">
        <span class="login-pro__badge"><i class="bi bi-lock-fill"></i> Authorized access only</span>
        <h2>Welcome back</h2>
        <p>Enter your credentials to manage content, settings, and users.</p>
      </header>

      <?php if ($error): ?>
      <div class="admin-flash admin-flash--error login-pro__flash" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span><?= e($error) ?></span></div>
      <?php endif; ?>
      <?php if ($success): ?>
      <div class="admin-flash admin-flash--success login-pro__flash" role="status"><i class="bi bi-check-circle-fill"></i><span><?= e($success) ?></span></div>
      <?php endif; ?>

      <form action="<?= adminLoginUrl() ?>" method="POST" class="login-pro__form" novalidate>
        <?= csrf() ?>
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="admin-honeypot" aria-hidden="true">

        <div class="login-pro__field">
          <label class="login-pro__label" for="login-email">Email address</label>
          <div class="login-pro__input-wrap">
            <i class="bi bi-envelope" aria-hidden="true"></i>
            <input id="login-email" type="email" name="email" required autofocus class="login-pro__input" placeholder="info@highqhomes.net" value="<?= e($_POST['email'] ?? '') ?>" autocomplete="username" maxlength="190">
          </div>
        </div>

        <div class="login-pro__field">
          <label class="login-pro__label" for="login-password">Password</label>
          <div class="login-pro__input-wrap login-pro__input-wrap--password">
            <i class="bi bi-lock" aria-hidden="true"></i>
            <input id="login-password" type="password" name="password" required class="login-pro__input" placeholder="Enter your password" autocomplete="current-password" maxlength="128">
            <button type="button" class="login-pro__toggle-pw" data-toggle-password="#login-password" aria-label="Show password"><i class="bi bi-eye"></i></button>
          </div>
        </div>

        <button type="submit" class="login-pro__submit">
          <span>Sign in to CMS</span>
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="login-pro__trust">
        <div><i class="bi bi-shield-check"></i><span>Rate-limited login</span></div>
        <div><i class="bi bi-clock-history"></i><span>Session timeout</span></div>
        <div><i class="bi bi-fingerprint"></i><span>Secure fingerprint</span></div>
      </div>
    </div>

    <p class="login-pro__legal">For ICT staff only. Unauthorized access is prohibited.</p>
  </main>
</div>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
