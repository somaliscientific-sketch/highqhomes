<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found — HighQ Homes</title>
  <link rel="icon" type="image/svg+xml" href="<?= asset('favicon.svg') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/hq.css') ?>">
</head>
<body style="padding-top:0">
  <main class="hq-404">
    <p class="hq-eyebrow">404 Error</p>
    <h1>404</h1>
    <p class="hq-lead">The page you're looking for doesn't exist or may have been moved.</p>
    <div class="hq-actions">
      <a href="<?= url() ?>" class="hq-btn hq-btn--navy"><i class="bi bi-house-fill"></i> Back to Home</a>
      <a href="<?= url('contact') ?>" class="hq-btn hq-btn--outline">Contact Us</a>
    </div>
  </main>
</body>
</html>
