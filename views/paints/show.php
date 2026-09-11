<?php
$bodyPage  = 'paints';
$pageTitle = e($paint['name']) . ' — HighQ Homes';

$wa        = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667');
$quoteHref = 'https://wa.me/' . $wa . '?text=Hello%20HighQ%20Homes,%20interested%20in%20' . rawurlencode($paint['name']);
$phone     = $settings['phone'] ?? '';
$phoneHref = preg_replace('/\s+/', '', $phone);

$pImg = !empty($paint['featured_image'])
  ? (str_starts_with($paint['featured_image'], 'http') ? $paint['featured_image'] : uploadUrl($paint['featured_image']))
  : 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=800&q=80';

$features = $paint['features'] ?? [];
$specs    = $paint['specifications'] ?? [];
$gallery  = $paint['gallery_images'] ?? [];

$applicationTips = [
  ['icon' => 'bi-moisture', 'title' => 'Surface prep', 'text' => 'Ensure walls are clean, dry, and free of loose material before application.'],
  ['icon' => 'bi-thermometer-half', 'title' => 'Conditions', 'text' => 'Apply in dry weather, avoiding direct midday heat for best adhesion.'],
  ['icon' => 'bi-layers', 'title' => 'Coverage', 'text' => 'Follow pack coverage rates — typically 18–20 m² per unit for textured coatings.'],
  ['icon' => 'bi-clock-history', 'title' => 'Curing', 'text' => 'Allow full cure time before exposure to heavy rain or washing.'],
];
?>

<section class="hq-paints-pro-hero hq-paints-pro-hero--product">
  <div class="hq-paints-pro-hero__bg" aria-hidden="true">
    <img src="<?= e($pImg) ?>" alt="" loading="eager">
  </div>
  <div class="hq-paints-pro-hero__overlay" aria-hidden="true"></div>
  <div class="container-site hq-paints-pro-hero__inner">
    <nav class="hq-breadcrumb hq-breadcrumb--light" aria-label="Breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= url('paints') ?>">Paints</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= e($paint['name']) ?></span>
    </nav>
    <div class="hq-paints-pro-hero__content hq-paints-pro-hero__content--product" data-anim="up">
      <?php if (!empty($paint['brand'])): ?>
      <span class="hq-paints-pro-product__brand"><?= e($paint['brand']) ?></span>
      <?php endif; ?>
      <h1 class="hq-paints-pro-hero__title"><?= e($paint['name']) ?></h1>
      <?php if (!empty($paint['short_description'])): ?>
      <p class="hq-paints-pro-hero__lead"><?= e($paint['short_description']) ?></p>
      <?php endif; ?>
      <div class="hq-paints-pro-product__chips">
        <?php if (!empty($paint['category'])): ?>
        <span><i class="bi bi-tag"></i> <?= e($paint['category']) ?></span>
        <?php endif; ?>
        <?php if (!empty($paint['price'])): ?>
        <span class="hq-paints-pro-product__price-chip"><i class="bi bi-currency-exchange"></i> <?= e($paint['price']) ?><?= !empty($paint['unit']) ? ' / ' . e($paint['unit']) : '' ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="hq-paints-pro-detail">
  <div class="container-site">
    <div class="hq-paints-pro-detail__layout">
      <div class="hq-paints-pro-detail__main">
        <div class="hq-paints-pro-detail__gallery" data-anim="up">
          <img src="<?= e($pImg) ?>" alt="<?= e($paint['name']) ?>" class="hq-paints-pro-detail__hero-img" loading="eager">
          <?php if (!empty($gallery)): ?>
          <div class="hq-paints-pro-detail__thumbs">
            <?php foreach ($gallery as $g): ?>
            <?php $gUrl = str_starts_with($g, 'http') ? $g : uploadUrl($g); ?>
            <button type="button" class="hq-paints-pro-detail__thumb" data-paint-thumb data-src="<?= e($gUrl) ?>">
              <img src="<?= e($gUrl) ?>" alt="" loading="lazy">
            </button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <article class="hq-paints-pro-detail__content" data-anim="up" data-delay="50">
          <h2 class="hq-paints-pro-detail__heading">Product overview</h2>
          <div class="hq-paints-pro-detail__prose">
            <?php if (!empty($paint['description'])): ?>
            <?= $paint['description'] ?>
            <?php else: ?>
            <p><?= e($paint['short_description'] ?? '') ?></p>
            <?php endif; ?>
          </div>
        </article>

        <?php if (!empty($features)): ?>
        <section class="hq-paints-pro-detail__features" data-anim="up" data-delay="70">
          <h2 class="hq-paints-pro-detail__heading">Key features</h2>
          <ul class="hq-paints-pro-features">
            <?php foreach ($features as $f): ?>
            <li><i class="bi bi-check-circle-fill"></i><span><?= e($f) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endif; ?>

        <?php if (!empty($specs)): ?>
        <section class="hq-paints-pro-detail__specs" data-anim="up" data-delay="90">
          <h2 class="hq-paints-pro-detail__heading">Technical specifications</h2>
          <div class="hq-paints-pro-specs-table-wrap">
            <table class="hq-paints-pro-specs-table">
              <tbody>
                <?php foreach ($specs as $label => $value): ?>
                <tr>
                  <th scope="row"><?= e((string)$label) ?></th>
                  <td><?= e((string)$value) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>
        <?php endif; ?>

        <section class="hq-paints-pro-detail__tips" data-anim="up" data-delay="110">
          <h2 class="hq-paints-pro-detail__heading">Application guidance</h2>
          <div class="hq-paints-pro-tips">
            <?php foreach ($applicationTips as $tip): ?>
            <article class="hq-paints-pro-tip">
              <span class="hq-paints-pro-tip__icon"><i class="bi <?= e($tip['icon']) ?>"></i></span>
              <div>
                <strong><?= e($tip['title']) ?></strong>
                <p><?= e($tip['text']) ?></p>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
        </section>
      </div>

      <aside class="hq-paints-pro-detail__aside">
        <div class="hq-paints-pro-enquiry" data-anim="right">
          <h3>Product enquiry</h3>
          <p>Get pricing, availability, and application advice for this product.</p>

          <?php if (!empty($paint['price'])): ?>
          <div class="hq-paints-pro-enquiry__price">
            <span>Indicative price</span>
            <strong><?= e($paint['price']) ?></strong>
            <?php if (!empty($paint['unit'])): ?><em>per <?= e($paint['unit']) ?></em><?php endif; ?>
          </div>
          <?php endif; ?>

          <div class="hq-paints-pro-enquiry__actions">
            <a href="<?= e($quoteHref) ?>" target="_blank" rel="noopener" class="hq-btn hq-btn--orange hq-btn--block"><i class="bi bi-whatsapp"></i> Enquire on WhatsApp</a>
            <?php if ($phone !== ''): ?>
            <a href="tel:<?= e($phoneHref) ?>" class="hq-paints-pro-enquiry__phone"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a>
            <?php endif; ?>
          </div>

          <?php if (!empty($specs)): ?>
          <dl class="hq-paints-pro-enquiry__quick">
            <?php foreach (array_slice($specs, 0, 4, true) as $label => $value): ?>
            <div>
              <dt><?= e((string)$label) ?></dt>
              <dd><?= e((string)$value) ?></dd>
            </div>
            <?php endforeach; ?>
          </dl>
          <?php endif; ?>
        </div>

        <div class="hq-paints-pro-aside-note">
          <h4><i class="bi bi-info-circle"></i> Supply note</h4>
          <p>Products are supplied for HighQ Homes projects and direct client orders. Minimum quantities may apply for delivery.</p>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php if (!empty($related)): ?>
<section class="hq-paints-pro-related">
  <div class="container-site">
    <header class="hq-paints-pro-section-head" data-anim="up">
      <p class="hq-paints-pro-eyebrow">Related products</p>
      <h2 class="hq-paints-pro-title">You may also need</h2>
    </header>
    <div class="hq-paints-pro-related__grid">
      <?php foreach ($related as $i => $rp): ?>
      <?php
        $rImg = !empty($rp['featured_image'])
          ? (str_starts_with($rp['featured_image'], 'http') ? $rp['featured_image'] : uploadUrl($rp['featured_image']))
          : 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=600&q=80';
      ?>
      <a href="<?= url('paints/' . $rp['slug']) ?>" class="hq-paints-pro-related__card" data-anim="up" data-delay="<?= $i * 55 ?>">
        <img src="<?= e($rImg) ?>" alt="<?= e($rp['name']) ?>" loading="lazy">
        <div>
          <?php if (!empty($rp['brand'])): ?><span><?= e($rp['brand']) ?></span><?php endif; ?>
          <strong><?= e($rp['name']) ?></strong>
        </div>
        <i class="bi bi-arrow-up-right"></i>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="hq-paints-pro-related__back">
      <a href="<?= url('paints') ?>" class="hq-btn hq-btn--outline"><i class="bi bi-arrow-left"></i> All products</a>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
document.querySelectorAll('[data-paint-thumb]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const src = btn.getAttribute('data-src');
    const hero = document.querySelector('.hq-paints-pro-detail__hero-img');
    if (src && hero) hero.src = src;
    document.querySelectorAll('[data-paint-thumb]').forEach((b) => b.classList.remove('is-active'));
    btn.classList.add('is-active');
  });
});
</script>
