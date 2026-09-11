<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="robots" content="noindex, nofollow, noarchive">
  <meta name="theme-color" content="#021d45">
  <meta name="color-scheme" content="light">
  <title>Sign in — <?= e(setting('site_name', 'HighQ Homes')) ?> CMS</title>
  <link rel="icon" href="<?= e(faviconHref([])) ?>">
  <?php if (trim(setting('favicon', '')) === ''): ?>
  <link rel="icon" type="image/png" href="<?= e(cmsLogoUrl()) ?>">
  <?php endif; ?>
  <?php $themeStyle = brandThemeStyle([]); if ($themeStyle !== ''): ?>
  <style><?= $themeStyle ?></style>
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=<?= @filemtime(PUBLIC_PATH . '/css/admin.css') ?: time() ?>&ac=2">
</head>
<body class="auth-layout auth-layout--pro">
  <?= $content ?>
</body>
</html>
