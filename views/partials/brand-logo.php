<?php
/**
 * HighQ Homes brand logo — icon + name lockup
 * @var string $siteName
 * @var array  $settings
 * @var string $variant  header|mobile|footer|icon
 * @var string|null $href
 */
$variant = $variant ?? 'header';
$href    = $href ?? url();

if ($variant === 'icon') {
    [$iconSrc, $iconClass] = brandIconMeta('footer', $settings);
} else {
    [$iconSrc, $iconClass] = brandIconMeta($variant, $settings);
}

$showName = $variant !== 'icon';
$nameParts = preg_split('/\s+/', trim($siteName), 2);
$Tag = $href ? 'a' : 'div';
?>
<<?= $Tag ?> <?= $href ? 'href="' . e($href) . '"' : '' ?> class="hq-brand hq-brand--<?= e($variant) ?>" <?= $href ? 'aria-label="' . e($siteName) . ' home"' : '' ?>>
  <span class="hq-brand__icon" aria-hidden="true">
    <img src="<?= e($iconSrc) ?>" alt="" class="<?= e($iconClass) ?>" decoding="async" fetchpriority="high">
  </span>
  <?php if ($showName): ?>
  <span class="hq-brand__text">
    <?php if (count($nameParts) === 2): ?>
    <span class="hq-brand__name">
      <span class="hq-brand__word hq-brand__word--primary"><?= e($nameParts[0]) ?></span><span class="hq-brand__word hq-brand__word--accent"><?= e($nameParts[1]) ?></span>
    </span>
    <?php else: ?>
    <span class="hq-brand__name"><?= e($siteName) ?></span>
    <?php endif; ?>
  </span>
  <?php endif; ?>
</<?= $Tag ?>>
