<?php
$pageTitle = 'Activity Logs';
$rows = $result['rows'];
?>

<header class="admin-logs-head">
  <div>
    <span class="admin-report-kicker">Audit</span>
    <h2>Admin activity logs</h2>
    <p>Who did what, when — sign-ins, content changes, settings, and user management.</p>
  </div>
  <div class="admin-logs-head__stat">
    <strong><?= number_format($result['total']) ?></strong>
    <span>Total events</span>
  </div>
</header>

<form method="GET" action="<?= url('admin/logs') ?>" class="admin-logs-filters admin-card admin-card-body">
  <div class="admin-logs-filters__grid">
    <div class="admin-form-group">
      <label class="admin-label" for="log-q">Search</label>
      <input id="log-q" type="search" name="q" value="<?= e($filters['q']) ?>" class="admin-input" placeholder="Description, name, email…">
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="log-module">Module</label>
      <select id="log-module" name="module" class="admin-input">
        <option value="">All modules</option>
        <?php foreach ($modules as $mod): ?>
        <option value="<?= e($mod) ?>" <?= ($filters['module'] ?? '') === $mod ? 'selected' : '' ?>><?= e(ucfirst($mod)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="log-action">Action</label>
      <select id="log-action" name="action" class="admin-input">
        <option value="">All actions</option>
        <?php foreach ($actions as $act): ?>
        <option value="<?= e($act) ?>" <?= ($filters['action'] ?? '') === $act ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucfirst($act))) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="admin-form-group">
      <label class="admin-label" for="log-user">User</label>
      <select id="log-user" name="user_id" class="admin-input">
        <option value="">All users</option>
        <?php foreach ($users as $u): ?>
        <option value="<?= (int)$u['id'] ?>" <?= (string)($filters['user_id'] ?? '') === (string)$u['id'] ? 'selected' : '' ?>><?= e($u['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="admin-logs-filters__actions">
    <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-funnel"></i> Filter</button>
    <a href="<?= url('admin/logs') ?>" class="admin-btn admin-btn-secondary admin-btn-sm">Clear</a>
  </div>
</form>

<div class="admin-card">
  <div class="admin-table-wrap">
    <table class="admin-table admin-logs-table">
      <thead>
        <tr>
          <th>When</th>
          <th>User</th>
          <th>Action</th>
          <th>Module</th>
          <th>Details</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
        <tr><td colspan="6" class="admin-empty">No activity logged yet.</td></tr>
        <?php else: ?>
        <?php foreach ($rows as $log): ?>
        <tr>
          <td class="admin-logs-table__when">
            <time datetime="<?= e($log['created_at']) ?>"><?= e(date('M j, Y g:i A', strtotime($log['created_at']))) ?></time>
          </td>
          <td>
            <div class="admin-table-title"><?= e($log['user_name'] ?? 'System') ?></div>
            <?php if (!empty($log['user_email'])): ?>
            <div class="admin-table-desc"><?= e($log['user_email']) ?></div>
            <?php endif; ?>
          </td>
          <td><span class="admin-log-badge admin-log-badge--<?= e($log['action']) ?>"><?= e(str_replace('_', ' ', $log['action'])) ?></span></td>
          <td><?= e(ucfirst($log['module'])) ?></td>
          <td class="admin-logs-table__desc"><?= e($log['description'] ?? '—') ?></td>
          <td class="admin-table-desc"><?= e($log['ip_address'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($result['pages'] > 1): ?>
  <div class="admin-logs-pagination">
    <?php
      $base = url('admin/logs') . '?' . http_build_query(array_filter($filters));
      $sep = str_contains($base, '?') && !str_ends_with($base, '?') ? '&' : '';
    ?>
    <?php if ($result['page'] > 1): ?>
    <a href="<?= e($base . $sep . 'page=' . ($result['page'] - 1)) ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-chevron-left"></i></a>
    <?php endif; ?>
    <span>Page <?= (int)$result['page'] ?> of <?= (int)$result['pages'] ?></span>
    <?php if ($result['page'] < $result['pages']): ?>
    <a href="<?= e($base . $sep . 'page=' . ($result['page'] + 1)) ?>" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-chevron-right"></i></a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
