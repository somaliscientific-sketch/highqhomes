<?php
$success = Session::getFlash('success');
$error = Session::getFlash('error');
$loginEmail = (string)Session::getFlash('login_email', '');
if ($loginEmail === '') {
  $loginEmail = (string)($_POST['email'] ?? '');
}
$siteName = setting('site_name', 'HighQ Homes');
$tagline = setting('tagline', 'Premium Construction & Architecture');
$legalName = setting('legal_name', $siteName);
$cmsLogo = cmsLogoUrl();
$loginPhoto = asset('images/builds/grey-villa-evening.jpg');
$hasError = $error !== null && $error !== '';
?>

<div class="login-pro">
  <aside class="login-pro__showcase" aria-label="<?= e($siteName) ?>">
    <img class="login-pro__photo" src="<?= e($loginPhoto) ?>" alt="" width="1200" height="1600">
    <div class="login-pro__showcase-bg" aria-hidden="true"></div>
    <div class="login-pro__showcase-inner">
      <div class="login-pro__brand-mark">
        <img src="<?= e($cmsLogo) ?>" alt="" class="admin-cms-logo login-pro__logo" width="72" height="72">
        <div>
          <p class="login-pro__brand-label">Staff CMS</p>
          <p class="login-pro__brand-title"><?= e($siteName) ?></p>
        </div>
      </div>

      <h1 class="login-pro__headline">The site, from one desk.</h1>
      <p class="login-pro__brand-lead"><?= e($tagline) ?>. Edit pages, projects, and messages without touching code.</p>

      <ul class="login-pro__highlights">
        <li>
          <i class="bi bi-window" aria-hidden="true"></i>
          <div>
            <strong>Pages &amp; sections</strong>
            <span>Homepage copy, projects, and gallery.</span>
          </div>
        </li>
        <li>
          <i class="bi bi-images" aria-hidden="true"></i>
          <div>
            <strong>Media library</strong>
            <span>Photos from the Garowe jobs.</span>
          </div>
        </li>
        <li>
          <i class="bi bi-inbox" aria-hidden="true"></i>
          <div>
            <strong>Enquiries</strong>
            <span>Contact form messages in one inbox.</span>
          </div>
        </li>
      </ul>
    </div>

    <footer class="login-pro__showcase-foot">
      <span>&copy; <?= date('Y') ?> <?= e($legalName) ?></span>
      <a href="<?= url() ?>">Public website</a>
    </footer>
  </aside>

  <main class="login-pro__main">
    <a href="<?= url() ?>" class="login-pro__back"><i class="bi bi-arrow-left" aria-hidden="true"></i> Website</a>

    <div class="login-pro__panel">
      <header class="login-pro__header">
        <span class="login-pro__badge"><i class="bi bi-shield-lock" aria-hidden="true"></i> Authorized staff</span>
        <h2>Sign in</h2>
        <p>Use your HighQ Homes account to manage the website.</p>
      </header>

      <?php if ($hasError): ?>
      <div class="admin-flash admin-flash--error login-pro__flash" role="alert"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><span><?= e($error) ?></span></div>
      <?php endif; ?>
      <?php if ($success): ?>
      <div class="admin-flash admin-flash--success login-pro__flash" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span><?= e($success) ?></span></div>
      <?php endif; ?>

      <form action="<?= adminLoginUrl() ?>" method="POST" class="login-pro__form" id="login-form">
        <?= csrf() ?>
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="admin-honeypot" aria-hidden="true">

        <div class="login-pro__field">
          <label class="login-pro__label" for="login-email">Email</label>
          <div class="login-pro__input-wrap">
            <i class="bi bi-envelope" aria-hidden="true"></i>
            <input
              id="login-email"
              type="email"
              name="email"
              required
              autofocus
              class="login-pro__input<?= $hasError ? ' is-invalid' : '' ?>"
              placeholder="name@highqhomes.net"
              value="<?= e($loginEmail) ?>"
              autocomplete="username"
              inputmode="email"
              autocapitalize="none"
              autocorrect="off"
              spellcheck="false"
              maxlength="190"
              <?= $hasError ? 'aria-invalid="true"' : '' ?>
            >
          </div>
        </div>

        <div class="login-pro__field">
          <label class="login-pro__label" for="login-password">Password</label>
          <div class="login-pro__input-wrap login-pro__input-wrap--password">
            <i class="bi bi-lock" aria-hidden="true"></i>
            <input
              id="login-password"
              type="password"
              name="password"
              required
              class="login-pro__input<?= $hasError ? ' is-invalid' : '' ?>"
              placeholder="Enter your password"
              autocomplete="current-password"
              maxlength="128"
              <?= $hasError ? 'aria-invalid="true"' : '' ?>
            >
            <button type="button" class="login-pro__toggle-pw" data-toggle-password="#login-password" aria-label="Show password" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button>
          </div>
          <p class="login-pro__caps" id="login-caps" hidden>Caps Lock is on</p>
        </div>

        <button type="submit" class="login-pro__submit" id="login-submit">
          <span class="login-pro__submit-label">Sign in</span>
          <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </button>
      </form>

      <p class="login-pro__note">Your session ends after a period of inactivity.</p>
    </div>

    <p class="login-pro__legal">ICT staff only. Unauthorized access is prohibited. <a href="<?= url() ?>">Public website</a></p>
  </main>
</div>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>&ac=2"></script>
<script>
(function () {
  var form = document.getElementById('login-form');
  var password = document.getElementById('login-password');
  var caps = document.getElementById('login-caps');
  var toggle = document.querySelector('[data-toggle-password]');
  var submit = document.getElementById('login-submit');

  if (toggle && password) {
    toggle.addEventListener('click', function () {
      var shown = password.type === 'text';
      toggle.setAttribute('aria-pressed', shown ? 'true' : 'false');
      toggle.setAttribute('aria-label', shown ? 'Hide password' : 'Show password');
    });
  }

  function syncCaps(event) {
    if (!caps || !event.getModifierState) return;
    caps.hidden = !event.getModifierState('CapsLock');
  }
  if (password) {
    password.addEventListener('keydown', syncCaps);
    password.addEventListener('keyup', syncCaps);
  }

  if (form && submit) {
    form.addEventListener('submit', function () {
      if (submit.disabled) return;
      submit.disabled = true;
      submit.classList.add('is-loading');
      submit.setAttribute('aria-busy', 'true');
    });
  }
})();
</script>
