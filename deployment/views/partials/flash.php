<?php
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
?>
<?php if ($success): ?>
<div data-flash class="flash-success admin-flash admin-flash--success">
  <i class="bi bi-check-circle-fill"></i>
  <span><?= e($success) ?></span>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div data-flash class="flash-error admin-flash admin-flash--error">
  <i class="bi bi-exclamation-circle-fill"></i>
  <span><?= e($error) ?></span>
</div>
<?php endif; ?>
