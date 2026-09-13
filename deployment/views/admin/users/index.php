<?php
$pageTitle = 'User Management';
$totalUsers = count($users);
$activeUsers = count(array_filter($users, static fn($u) => !empty($u['is_active'])));
$disabledUsers = $totalUsers - $activeUsers;
$currentId = Auth::id();
$roleBadgeMap = [
    'super_admin' => 'admin-role-badge--super',
    'admin'       => 'admin-role-badge--admin',
    'editor'      => 'admin-role-badge--editor',
    'viewer'      => 'admin-role-badge--viewer',
];
?>

<header class="admin-users-head">
  <div>
    <span class="admin-report-kicker">Access control</span>
    <h2>Users &amp; roles</h2>
    <p>Manage admin accounts, assign roles, control status, and reset passwords.</p>
  </div>
  <div class="admin-users-head__stats">
    <div class="admin-users-head__stat">
      <strong><?= number_format($totalUsers) ?></strong>
      <span>Total users</span>
    </div>
    <div class="admin-users-head__stat admin-users-head__stat--ok">
      <strong><?= number_format($activeUsers) ?></strong>
      <span>Active</span>
    </div>
    <div class="admin-users-head__stat admin-users-head__stat--warn">
      <strong><?= number_format($disabledUsers) ?></strong>
      <span>Disabled</span>
    </div>
    <?php if (Auth::isSuperAdmin()): ?>
    <a href="<?= url('admin/roles') ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-users-head__link">
      <i class="bi bi-shield-lock"></i> Role permissions
    </a>
    <?php endif; ?>
  </div>
</header>

<div class="admin-users-toolbar admin-card admin-card-body" data-users-toolbar>
  <div class="admin-users-toolbar__search">
    <i class="bi bi-search" aria-hidden="true"></i>
    <input type="search" id="users-search" class="admin-input admin-users-toolbar__input" placeholder="Search by name or email…" autocomplete="off" data-users-search>
  </div>
  <div class="admin-users-toolbar__filters">
    <select id="users-role" class="admin-input admin-users-toolbar__select" data-users-role-filter aria-label="Filter by role">
      <option value="">All roles</option>
      <?php foreach ($roles as $role): ?>
      <option value="<?= e($role['name']) ?>"><?= e($role['label']) ?></option>
      <?php endforeach; ?>
    </select>
    <select id="users-status" class="admin-input admin-users-toolbar__select" data-users-status-filter aria-label="Filter by status">
      <option value="">All statuses</option>
      <option value="active">Active</option>
      <option value="disabled">Disabled</option>
    </select>
  </div>
</div>

<div class="admin-users-layout">
  <div class="admin-card admin-users-table-card">
    <div class="admin-card-header">
      <div>
        <h3 class="admin-card-title">All accounts</h3>
        <p class="admin-users-table-card__sub" data-users-count-label><?= $totalUsers === 1 ? '1 account' : number_format($totalUsers) . ' accounts' ?></p>
      </div>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table admin-users-table" data-users-table>
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Last login</th>
            <th>Status</th>
            <th class="admin-users-table__actions-head">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
          <?php
            $isSelf = (int)$user['id'] === $currentId;
            $roleName = (string)$user['role'];
            $roleClass = $roleBadgeMap[$roleName] ?? 'admin-role-badge--default';
            $searchBlob = strtolower($user['name'] . ' ' . $user['email']);
            $statusKey = $user['is_active'] ? 'active' : 'disabled';
          ?>
          <tr
            data-user-row
            data-search="<?= e($searchBlob) ?>"
            data-role="<?= e($roleName) ?>"
            data-status="<?= e($statusKey) ?>"
            class="<?= $isSelf ? 'admin-users-table__row--self' : '' ?>"
          >
            <td>
              <div class="admin-user-cell">
                <span class="admin-user-cell__avatar admin-user-cell__avatar--<?= e(preg_replace('/[^a-z0-9_]/', '', $roleName)) ?>"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                <div class="admin-user-cell__meta">
                  <div class="admin-table-title">
                    <?= e($user['name']) ?>
                    <?php if ($isSelf): ?><span class="admin-users-you">You</span><?php endif; ?>
                  </div>
                  <div class="admin-table-desc"><?= e($user['email']) ?></div>
                </div>
              </div>
            </td>
            <td>
              <span class="admin-role-badge <?= e($roleClass) ?>"><?= e($roleLabels[$roleName] ?? ucwords(str_replace('_', ' ', $roleName))) ?></span>
            </td>
            <td>
              <?php if (!empty($user['last_login'])): ?>
              <time class="admin-users-last-login" datetime="<?= e($user['last_login']) ?>"><?= e(date('M j, Y', strtotime($user['last_login']))) ?></time>
              <span class="admin-table-desc admin-users-last-login__time"><?= e(date('g:i A', strtotime($user['last_login']))) ?></span>
              <?php else: ?>
              <span class="admin-users-never">Never signed in</span>
              <?php endif; ?>
            </td>
            <td>
              <span class="admin-status-pill admin-status-pill--<?= $user['is_active'] ? 'active' : 'disabled' ?>">
                <i class="bi bi-<?= $user['is_active'] ? 'check-circle-fill' : 'pause-circle-fill' ?>"></i>
                <?= $user['is_active'] ? 'Active' : 'Disabled' ?>
              </span>
            </td>
            <td>
              <div class="admin-table-actions admin-users-actions">
                <button
                  type="button"
                  class="admin-btn admin-btn-secondary admin-btn-sm"
                  data-user-edit
                  data-id="<?= (int)$user['id'] ?>"
                  data-name="<?= e($user['name']) ?>"
                  data-email="<?= e($user['email']) ?>"
                  data-role="<?= e($roleName) ?>"
                  data-active="<?= $user['is_active'] ? '1' : '0' ?>"
                  data-action="<?= e(url('admin/users/' . $user['id'] . '/edit')) ?>"
                  aria-label="Edit <?= e($user['name']) ?>"
                ><i class="bi bi-pencil"></i></button>
                <?php if (!$isSelf): ?>
                <form action="<?= url('admin/users/' . $user['id'] . '/toggle') ?>" method="POST" class="admin-inline-form">
                  <?= csrf() ?>
                  <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm" title="<?= $user['is_active'] ? 'Disable account' : 'Enable account' ?>">
                    <i class="bi bi-<?= $user['is_active'] ? 'pause-circle' : 'play-circle' ?>"></i>
                  </button>
                </form>
                <form action="<?= url('admin/users/' . $user['id'] . '/delete') ?>" method="POST" class="admin-inline-form">
                  <?= csrf() ?>
                  <button type="submit" data-confirm="Delete user &quot;<?= e($user['name']) ?>&quot;? This cannot be undone." class="admin-btn admin-btn-danger admin-btn-sm" title="Delete user">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="admin-empty admin-empty--pro admin-users-empty-filter" data-users-empty-filter hidden>
      <i class="bi bi-search"></i>
      <p>No users match your filters.</p>
    </div>
    <?php if ($totalUsers === 0): ?>
    <div class="admin-empty admin-empty--pro">
      <i class="bi bi-people"></i>
      <p>No users yet. Create the first account using the form on the right.</p>
    </div>
    <?php endif; ?>
  </div>

  <aside class="admin-users-aside">
    <div class="admin-card admin-users-create">
      <div class="admin-users-create__head">
        <span class="admin-users-create__icon"><i class="bi bi-person-plus"></i></span>
        <div>
          <h3 class="admin-users-create__title">Add user</h3>
          <p>Create a new CMS account with a role and temporary password.</p>
        </div>
      </div>
      <form action="<?= url('admin/users/create') ?>" method="POST" class="admin-users-create__form">
        <?= csrf() ?>
        <div class="admin-form-group">
          <label class="admin-label" for="create-name">Full name</label>
          <input id="create-name" class="admin-input" name="name" required placeholder="Jane Doe">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="create-email">Email address</label>
          <input id="create-email" class="admin-input" type="email" name="email" required placeholder="user@example.com">
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="create-password">Temporary password</label>
          <div class="admin-users-password-wrap">
            <input id="create-password" class="admin-input" type="password" name="password" required minlength="10" autocomplete="new-password" placeholder="Min. 10 characters">
            <button type="button" class="admin-users-password-toggle" data-toggle-password="#create-password" aria-label="Show password"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="admin-form-group">
          <label class="admin-label" for="create-role">Role</label>
          <select id="create-role" class="admin-input" name="role">
            <?php foreach ($roles as $role): ?>
            <?php if ($role['name'] === 'super_admin' && !Auth::isSuperAdmin()) continue; ?>
            <option value="<?= e($role['name']) ?>"><?= e($role['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <label class="admin-checkbox-row admin-users-create__active">
          <input type="checkbox" name="is_active" checked>
          Active on create
        </label>
        <button type="submit" class="admin-btn admin-btn-primary admin-btn-block">
          <i class="bi bi-person-check"></i> Create user
        </button>
      </form>
    </div>

    <div class="admin-card admin-card-body admin-users-tips">
      <h4 class="admin-users-tips__title"><i class="bi bi-lightbulb"></i> Best practices</h4>
      <ul>
        <li>Use strong temporary passwords and ask users to change them on first login.</li>
        <li>Assign the lowest role that fits each person’s responsibilities.</li>
        <li>Disable accounts instead of deleting when someone leaves temporarily.</li>
      </ul>
    </div>
  </aside>
</div>

<div class="admin-user-drawer-backdrop" id="admin-user-drawer-backdrop" hidden></div>
<aside class="admin-user-drawer" id="admin-user-drawer" aria-hidden="true" aria-labelledby="admin-user-drawer-title">
  <div class="admin-user-drawer__head">
    <div>
      <span class="admin-report-kicker">Edit account</span>
      <h3 id="admin-user-drawer-title">User details</h3>
    </div>
    <button type="button" class="admin-user-drawer__close" data-user-drawer-close aria-label="Close">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
  <form id="admin-user-edit-form" method="POST" class="admin-user-drawer__form">
    <?= csrf() ?>
    <div class="admin-form-group">
      <label class="admin-label" for="edit-name">Full name</label>
      <input id="edit-name" class="admin-input" name="name" required>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="edit-email">Email address</label>
      <input id="edit-email" class="admin-input" name="email" type="email" required>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="edit-password">New password</label>
      <div class="admin-users-password-wrap">
        <input id="edit-password" class="admin-input" name="password" type="password" placeholder="Leave blank to keep current" minlength="10" autocomplete="new-password">
        <button type="button" class="admin-users-password-toggle" data-toggle-password="#edit-password" aria-label="Show password"><i class="bi bi-eye"></i></button>
      </div>
      <p class="admin-form-hint">Minimum 10 characters when changing.</p>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="edit-role">Role</label>
      <select id="edit-role" class="admin-input" name="role">
        <?php foreach ($roles as $role): ?>
        <?php if ($role['name'] === 'super_admin' && !Auth::isSuperAdmin()) continue; ?>
        <option value="<?= e($role['name']) ?>"><?= e($role['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <label class="admin-checkbox-row">
      <input id="edit-active" type="checkbox" name="is_active">
      Active account
    </label>
    <div class="admin-user-drawer__actions">
      <button type="button" class="admin-btn admin-btn-secondary" data-user-drawer-close>Cancel</button>
      <button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-check-lg"></i> Save changes</button>
    </div>
  </form>
</aside>

<script src="<?= asset('js/admin.js') ?>?v=<?= @filemtime(PUBLIC_PATH . '/js/admin.js') ?: time() ?>"></script>
