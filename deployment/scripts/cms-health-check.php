<?php
declare(strict_types=1);

/**
 * HighQ Homes CMS health check — run: php scripts/cms-health-check.php
 */
require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/SettingModel.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Models/RoleModel.php';
require_once APP_PATH . '/Models/AdminLogModel.php';

$errors = [];
$warnings = [];
$passed = 0;

function ok(string $msg): void
{
    global $passed;
    $passed++;
    echo "  OK  {$msg}\n";
}

function fail(string $msg): void
{
    global $errors;
    $errors[] = $msg;
    echo " FAIL {$msg}\n";
}

function warn(string $msg): void
{
    global $warnings;
    $warnings[] = $msg;
    echo " WARN {$msg}\n";
}

echo "=== HighQ Homes CMS Health Check ===\n\n";

// Database
echo "[Database]\n";
try {
    $db = Database::getInstance();
    ok('Database connection');
} catch (Throwable $e) {
    fail('Database connection: ' . $e->getMessage());
    echo "\nCannot continue without DB.\n";
    exit(1);
}

$requiredTables = [
    'users', 'roles', 'settings', 'admin_logs', 'sliders', 'services', 'projects',
    'gallery', 'team', 'testimonials', 'paints', 'messages', 'seo', 'pages',
    'menus', 'media', 'page_sections',
];

foreach ($requiredTables as $table) {
    try {
        $db->query("SELECT 1 FROM `{$table}` LIMIT 1");
        ok("Table `{$table}` exists");
    } catch (Throwable $e) {
        fail("Table `{$table}` missing or inaccessible");
    }
}

// Roles & permissions
echo "\n[Roles & Permissions]\n";
try {
    $roles = (new RoleModel())->findAll('id', 'ASC');
    $names = array_column($roles, 'name');
    foreach (['super_admin', 'admin', 'editor', 'viewer'] as $expected) {
        in_array($expected, $names, true) ? ok("Role `{$expected}` exists") : fail("Role `{$expected}` missing");
    }
    foreach ($roles as $role) {
        $perms = json_decode((string)($role['permissions'] ?? '[]'), true);
        if (!is_array($perms)) {
            fail("Role {$role['name']} has invalid permissions JSON");
        } else {
            ok("Role {$role['name']} permissions valid (" . count($perms) . " keys)");
        }
    }
    $adminRole = array_filter($roles, fn($r) => $r['name'] === 'admin');
    $adminRole = reset($adminRole);
    if ($adminRole) {
        $adminPerms = json_decode((string)$adminRole['permissions'], true) ?: [];
        foreach (['logs.view', 'security.manage', 'users.manage', 'settings.manage'] as $p) {
            in_array($p, $adminPerms, true)
                ? ok("Admin role has `{$p}`")
                : warn("Admin role missing permission `{$p}` — run apply-cms-system-upgrade.php");
        }
    }
} catch (Throwable $e) {
    fail('Roles check: ' . $e->getMessage());
}

// Admin user
echo "\n[Users]\n";
try {
    $users = (new UserModel())->findAll('id', 'ASC');
    ok(count($users) . ' user(s) in database');
    $active = array_filter($users, fn($u) => (int)($u['is_active'] ?? 0) === 1);
    count($active) > 0 ? ok(count($active) . ' active user(s)') : fail('No active admin users');
} catch (Throwable $e) {
    fail('Users check: ' . $e->getMessage());
}

// Settings groups
echo "\n[Settings]\n";
try {
    $groups = (new SettingModel())->getGroups();
    foreach (['general', 'contact', 'social', 'homepage', 'identity', 'security', 'seo'] as $g) {
        in_array($g, $groups, true) ? ok("Settings group `{$g}`") : warn("Settings group `{$g}` missing");
    }
    $manageable = (new SettingModel())->getManageableGroups();
    foreach (['identity', 'security', 'seo'] as $hidden) {
        !in_array($hidden, $manageable, true)
            ? ok("Group `{$hidden}` excluded from generic settings")
            : fail("Group `{$hidden}` should not appear in generic settings");
    }
} catch (Throwable $e) {
    fail('Settings check: ' . $e->getMessage());
}

// Controllers & routes
echo "\n[Controllers & Routes]\n";
require_once APP_PATH . '/Core/Router.php';
require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/Security.php';
require_once APP_PATH . '/Core/Session.php';
require_once APP_PATH . '/Core/CSRF.php';
require_once APP_PATH . '/Core/Audit.php';

$routeFile = file_get_contents(__DIR__ . '/../config/routes.php');
preg_match_all("/->(?:get|post)\('([^']+)',\s*'([^']+)'\)/", $routeFile, $matches, PREG_SET_ORDER);
foreach ($matches as $m) {
    [$full, $path, $handler] = $m;
    [$ns, $action] = explode('@', $handler);
    $parts = explode('\\', $ns);
    $class = end($parts);
    $className = (count($parts) > 1 && $parts[0] === 'Admin') ? 'Admin' . $class : $class;
    $file = APP_PATH . '/Controllers/' . str_replace('\\', '/', $ns) . '.php';
    if (!file_exists($file)) {
        fail("Route {$path} → missing file {$file}");
        continue;
    }
    require_once $file;
    if (!class_exists($className)) {
        fail("Route {$path} → class {$className} not found");
        continue;
    }
    if (!method_exists($className, $action)) {
        fail("Route {$path} → {$className}@{$action} missing");
        continue;
    }
}
ok(count($matches) . ' routes resolve to existing controller methods');

// Admin logs table
echo "\n[Audit Logs]\n";
try {
    $count = (new AdminLogModel())->count();
    ok("admin_logs accessible ({$count} entries)");
} catch (Throwable $e) {
    fail('admin_logs: ' . $e->getMessage());
}

// Upload directory
echo "\n[Uploads]\n";
$uploadDir = UPLOAD_DIR;
if (is_dir($uploadDir) && is_writable($uploadDir)) {
    ok('Upload directory writable: ' . $uploadDir);
} else {
    fail('Upload directory missing or not writable: ' . $uploadDir);
}

// PHP syntax on all admin controllers
echo "\n[PHP Syntax]\n";
$controllers = glob(APP_PATH . '/Controllers/Admin/*.php') ?: [];
foreach ($controllers as $file) {
    exec('php -l ' . escapeshellarg($file) . ' 2>&1', $out, $code);
    if ($code !== 0) {
        fail(basename($file) . ': ' . implode(' ', $out));
    } else {
        ok(basename($file));
    }
}

echo "\n=== Summary ===\n";
echo "Passed: {$passed}\n";
echo "Warnings: " . count($warnings) . "\n";
echo "Errors: " . count($errors) . "\n";

if ($errors) {
    echo "\nErrors:\n";
    foreach ($errors as $e) {
        echo "  - {$e}\n";
    }
    exit(1);
}

echo "\nCMS health check passed.\n";
exit(0);
