<?php
$isEdit = $isEdit ?? !empty($slider['id']);
$pageTitle = $isEdit ? 'Edit hero slide' : 'Add hero slide';
$slot = (int)($slider['id'] ?? 0);
$imgSrc = !empty($slider['image']) ? mediaPathUrl((string)$slider['image']) : '';
$mobileSrc = !empty($slider['mobile_image']) ? mediaPathUrl((string)$slider['mobile_image']) : '';
$toLocal = static function (?string $value): string {
    if (!$value) {
        return '';
    }
    $ts = strtotime($value);
    return $ts ? date('Y-m-d\TH:i', $ts) : '';
};
?>

<header class="admin-page-intro">
  <div>
    <span class="admin-report-kicker">Website content · Hero Slider</span>
    <h2><?= $isEdit ? 'Edit slide' : 'Add slide' ?></h2>
    <p>Everything on this form appears on the homepage hero. Preview updates as you type.</p>
  </div>
  <a href="<?= url('admin/sliders') ?>" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i> All slides</a>
</header>

<?php require __DIR__ . '/_panel.php'; ?>
