<?php
declare(strict_types=1);

/**
 * HTTP integration tests — run: php scripts/cms-http-test.php
 * Requires Apache/XAMPP serving http://localhost/highQhomes
 */
$base = rtrim(getenv('APP_URL') ?: 'http://localhost/highQhomes', '/');
$loginPath = '/secure-admin-login';
$cookieFile = sys_get_temp_dir() . '/hqhomes_cms_test_cookies.txt';

$errors = [];
$passed = 0;

function req(string $url, string $method = 'GET', array $opts = []): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER         => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_TIMEOUT        => 15,
    ] + $opts);
    $raw = (string)curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $headerSize = strpos($raw, "\r\n\r\n");
    $headers = $headerSize !== false ? substr($raw, 0, $headerSize) : '';
    $body = $headerSize !== false ? substr($raw, $headerSize + 4) : $raw;
    return ['code' => $code, 'headers' => $headers, 'body' => $body];
}

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

function extractCsrf(string $html): ?string
{
    if (preg_match('/name="_csrf_token"\s+value="([^"]+)"/', $html, $m)) {
        return $m[1];
    }
    if (preg_match('/name=\'_csrf_token\'\s+value=\'([^\']+)\'/', $html, $m)) {
        return $m[1];
    }
    return null;
}

echo "=== CMS HTTP Tests ({$base}) ===\n\n";

@unlink($cookieFile);

// Public homepage
$r = req("{$base}/");
($r['code'] === 200) ? ok('Homepage returns 200') : fail("Homepage returned {$r['code']}");

// Secure login page
$r = req("{$base}{$loginPath}");
($r['code'] === 200 && str_contains($r['body'], 'Sign in')) ? ok('Secure login page loads') : fail('Secure login page failed');

// Legacy login blocked
$r = req("{$base}/admin/login");
($r['code'] === 404) ? ok('Legacy /admin/login returns 404') : fail("Legacy login returned {$r['code']} (expected 404)");

// Unauthenticated admin redirect
$r = req("{$base}/admin/dashboard");
($r['code'] === 302 && str_contains($r['headers'], 'secure-admin-login')) ? ok('Dashboard redirects to secure login') : fail('Dashboard auth redirect failed');

// Login page CSRF + form
$r = req("{$base}{$loginPath}", 'GET', [CURLOPT_COOKIEJAR => $cookieFile, CURLOPT_COOKIEFILE => $cookieFile]);
$csrf = extractCsrf($r['body']);
$csrf ? ok('Login form has CSRF token') : fail('Login form missing CSRF');

// Load credentials from env or defaults
$envFile = dirname(__DIR__) . '/.env';
$email = 'info@highqhomes.net';
$password = 'garowe@1234#';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), 'ADMIN_TEST_EMAIL=')) {
            $email = trim(substr($line, strlen('ADMIN_TEST_EMAIL=')));
        }
        if (str_starts_with(trim($line), 'ADMIN_TEST_PASSWORD=')) {
            $password = trim(substr($line, strlen('ADMIN_TEST_PASSWORD=')));
        }
    }
}

if ($csrf) {
    $post = http_build_query([
        '_csrf_token' => $csrf,
        'email'       => $email,
        'password'    => $password,
        'website'     => '',
    ]);
    $r = req("{$base}{$loginPath}", 'POST', [
        CURLOPT_COOKIEJAR     => $cookieFile,
        CURLOPT_COOKIEFILE    => $cookieFile,
        CURLOPT_POSTFIELDS    => $post,
        CURLOPT_HTTPHEADER    => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    if ($r['code'] === 302 && str_contains($r['headers'], '/admin/dashboard')) {
        ok('Login succeeds and redirects to dashboard');
    } else {
        fail("Login failed (HTTP {$r['code']}) — check credentials");
    }
}

// Authenticated admin pages
$adminPages = [
    '/admin/dashboard' => 'Dashboard',
    '/admin/identity'  => 'Site Identity',
    '/admin/settings'  => 'Settings',
    '/admin/security'  => 'Security',
    '/admin/logs'      => 'Activity Logs',
    '/admin/users'     => 'Users',
    '/admin/roles'     => 'Roles',
    '/admin/profile'   => 'Profile',
    '/admin/seo'       => 'SEO',
    '/admin/sliders'   => 'Sliders',
    '/admin/services'  => 'Services',
    '/admin/projects'  => 'Projects',
    '/admin/gallery'   => 'Gallery',
    '/admin/pages'     => 'Pages',
    '/admin/media'     => 'Media',
    '/admin/menus'     => 'Menus',
    '/admin/messages'  => 'Messages',
    '/admin/sections'  => 'Sections',
];

foreach ($adminPages as $path => $label) {
    $r = req("{$base}{$path}", 'GET', [CURLOPT_COOKIEJAR => $cookieFile, CURLOPT_COOKIEFILE => $cookieFile]);
    if ($r['code'] === 200) {
        ok("{$label} ({$path}) → 200");
    } elseif ($r['code'] === 302) {
        fail("{$label} ({$path}) → redirect (permission issue?)");
    } else {
        fail("{$label} ({$path}) → HTTP {$r['code']}");
    }
}

// Logout via POST
$r = req("{$base}/admin/dashboard", 'GET', [CURLOPT_COOKIEJAR => $cookieFile, CURLOPT_COOKIEFILE => $cookieFile]);
$csrf = extractCsrf($r['body']);
if ($csrf) {
    $post = http_build_query(['_csrf_token' => $csrf]);
    $r = req("{$base}/admin/logout", 'POST', [
        CURLOPT_COOKIEJAR     => $cookieFile,
        CURLOPT_COOKIEFILE    => $cookieFile,
        CURLOPT_POSTFIELDS    => $post,
        CURLOPT_HTTPHEADER    => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    ($r['code'] === 302 && str_contains($r['headers'], 'secure-admin-login')) ? ok('Logout POST redirects to login') : fail('Logout POST failed');
}

@unlink($cookieFile);

echo "\n=== Summary ===\n";
echo "Passed: {$passed}\n";
echo "Errors: " . count($errors) . "\n";
if ($errors) {
    foreach ($errors as $e) {
        echo "  - {$e}\n";
    }
    exit(1);
}
echo "\nHTTP tests passed.\n";
