<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <?php
  $settings = $settings ?? [];
  $seo = $seo ?? [];
  $seoTitle = $seo['meta_title'] ?? ($pageTitle ?? APP_NAME);
  $seoDesc  = $seo['meta_description'] ?? ($settings['tagline'] ?? 'Premium construction and architecture in Garowe, Puntland.');
  $canonicalUrl = canonicalUrl();
  $ogImage = !empty($seo['og_image']) ? uploadUrl($seo['og_image']) : '';
  ?>
  <title><?= e($seoTitle) ?></title>
  <meta name="description" content="<?= e($seoDesc) ?>">
  <?php if (!empty($seo['meta_keywords'])): ?>
  <meta name="keywords" content="<?= e($seo['meta_keywords']) ?>">
  <?php endif; ?>
  <meta name="theme-color" content="<?= e($settings['primary_color'] ?? '#021D45') ?>">
  <meta property="og:site_name" content="<?= e($settings['site_name'] ?? APP_NAME) ?>">
  <meta property="og:locale" content="en_US">
  <meta property="og:title" content="<?= e($seo['og_title'] ?? $seoTitle) ?>">
  <meta property="og:description" content="<?= e($seo['og_description'] ?? $seoDesc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e($canonicalUrl) ?>">
  <?php if ($ogImage !== ''): ?>
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($seo['og_title'] ?? $seoTitle) ?>">
  <meta name="twitter:description" content="<?= e($seo['og_description'] ?? $seoDesc) ?>">
  <?php if ($ogImage !== ''): ?>
  <meta name="twitter:image" content="<?= e($ogImage) ?>">
  <?php endif; ?>
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">
  <link rel="icon" href="<?= e(faviconHref($settings ?? [])) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= asset('css/hq.css') ?>?v=<?= @filemtime(PUBLIC_PATH . '/css/hq.css') ?: time() ?>&hc=12">
  <?php if (!empty($themeCss = brandThemeStyle($settings ?? []))): ?>
  <style id="hq-brand-theme"><?= $themeCss ?></style>
  <?php endif; ?>
  <?php if (!empty($seo['schema_markup'])): ?>
  <script type="application/ld+json"><?= $seo['schema_markup'] ?></script>
  <?php else: ?>
  <script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $settings['site_name'] ?? APP_NAME,
    'url' => APP_URL,
    'telephone' => $settings['phone'] ?? '',
    'email' => $settings['email'] ?? '',
    'address' => [
      '@type' => 'PostalAddress',
      'streetAddress' => $settings['address'] ?? 'Garowe, Puntland, Somalia',
      'addressLocality' => 'Garowe',
      'addressRegion' => 'Puntland',
      'addressCountry' => 'SO',
    ],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
  <?php endif; ?>
  <?php if (!empty($settings['google_analytics'])): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($settings['google_analytics']) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', <?= json_encode($settings['google_analytics']) ?>);
  </script>
  <?php endif; ?>
</head>
<body data-page="<?= e($bodyPage ?? '') ?>">
<a href="#main-content" class="skip-link">Skip to content</a>

<?php View::partial('navbar', ['settings' => $settings, 'bodyPage' => $bodyPage ?? '']); ?>
<?php if (($bodyPage ?? '') !== 'contact'): ?>
<?php View::partial('flash') ?>
<?php endif; ?>

<main id="main-content">
  <?= $content ?>
</main>

<?php View::partial('footer', compact('settings') + ['bodyPage' => $bodyPage ?? '']); ?>

<?php if (($bodyPage ?? '') !== 'contact'): ?>
<?php $waNum = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '+252907734667'); ?>
<a href="https://wa.me/<?= e($waNum) ?>?text=Hello%20HighQ%20Homes" target="_blank" rel="noopener" class="whatsapp-float" title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
  <i class="bi bi-whatsapp"></i>
</a>
<?php endif; ?>

<div id="lightbox-modal" class="lightbox-modal" role="dialog" aria-modal="true" aria-label="Image preview">
  <button class="lightbox-close" aria-label="Close">&times;</button>
  <button class="lightbox-prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
  <img class="lightbox-img" src="" alt="">
  <button class="lightbox-next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
</div>

<button type="button" id="hq-back-to-top" class="hq-back-to-top" aria-label="Back to top" title="Back to top">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="<?= asset('js/app.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/app.js') ?: time() ?>"></script>
</body>
</html>
