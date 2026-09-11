<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Access denied — HighQ Homes</title>
  <link rel="stylesheet" href="<?= defined('APP_URL') ? htmlspecialchars(APP_URL . '/public/css/hq.css', ENT_QUOTES, 'UTF-8') : '' ?>">
</head>
<body style="padding-top:0">
  <main class="hq-404">
    <p class="hq-eyebrow">403 Error</p>
    <h1>403</h1>
    <p class="hq-lead">You do not have permission to view this page.</p>
    <div class="hq-actions">
      <a href="<?= defined('APP_URL') ? htmlspecialchars(APP_URL, ENT_QUOTES, 'UTF-8') : '/' ?>" class="hq-btn hq-btn--navy">Back to Home</a>
      <a href="<?= e(siteQuoteHref()) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--outline">WhatsApp Us</a>
    </div>
  </main>
</body>
</html>
