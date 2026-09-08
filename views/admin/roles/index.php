<?php $pageTitle = 'Roles & Permissions'; ?>

<div class="admin-card admin-card-body" style="margin-bottom:1.25rem">
  <p class="admin-help">Manage what each role can access in the CMS. Super Admin always has full access. Only Super Admin can edit role permissions.</p>
</div>

<?php foreach ($roles as $role): ?>
<?php $perms = json_decode((string)($role['permissions'] ?? '[]'), true) ?: []; ?>
<div class="admin-card" style="margin-bottom:1rem">
  <div class="admin-card-header">
    <h2 class="admin-card-title"><?= e($role['label']) ?> <span class="badge badge-primary"><?= e($role['name']) ?></span></h2>
  </div>
  <div class="admin-card-body">
    <?php if ($role['name'] === 'super_admin'): ?>
    <p class="admin-help">Full unrestricted access to all CMS modules.</p>
    <?php elseif (Auth::isSuperAdmin()): ?>
    <form action="<?= url('admin/roles/' . $role['id'] . '/edit') ?>" method="POST">
      <?= csrf() ?>
      <div class="admin-form-group">
        <label class="admin-label">Display label</label>
        <input class="admin-input" name="label" value="<?= e($role['label']) ?>">
      </div>
      <div class="admin-perm-grid">
        <?php foreach ($permissionMap as $key => $label): ?>
        <label class="admin-checkbox-row">
          <input type="checkbox" name="permissions[]" value="<?= e($key) ?>" <?= in_array($key, $perms, true) ? 'checked' : '' ?>>
          <?= e($label) ?>
        </label>
        <?php endforeach; ?>
      </div>
      <button class="admin-btn admin-btn-primary">Save permissions</button>
    </form>
    <?php else: ?>
    <ul class="admin-perm-list">
      <?php foreach ($perms as $perm): ?>
      <li><?= e($permissionMap[$perm] ?? $perm) ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
