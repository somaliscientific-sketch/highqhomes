<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
        putenv(trim($key) . '=' . trim($value));
    }
}

define('APP_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/highQhomes', '/'));
define('APP_NAME', $_ENV['APP_NAME'] ?? 'HighQ Homes');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');
define('APP_SECRET', $_ENV['APP_SECRET'] ?? 'change-this-secret');
define('ADMIN_LOGIN_PATH', preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($_ENV['ADMIN_LOGIN_PATH'] ?? 'secure-admin-login'))) ?: 'secure-admin-login');
define('UPLOAD_MAX_SIZE', (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 10485760));
define('UPLOAD_DIR', ROOT_PATH . '/' . trim($_ENV['UPLOAD_PATH'] ?? 'uploads', '/'));
define('UPLOAD_URL', APP_URL . '/' . trim($_ENV['UPLOAD_PATH'] ?? 'uploads', '/'));

// Enable verbose error reporting in development/debug mode
if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
