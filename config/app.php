<?php
declare(strict_types=1);

if (PHP_VERSION_ID < 80100) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('HighQ Homes requires PHP 8.1 or newer. Set PHP 8.2 or 8.3 in Hostinger hPanel.');
}

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
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        $_ENV[trim($key)] = $value;
        putenv(trim($key) . '=' . $value);
    }
}

require_once APP_PATH . '/Core/Production.php';
Production::applyLiveOverrides();
Production::enforceCanonical();

define('APP_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/highQhomes', '/'));
define('APP_NAME', $_ENV['APP_NAME'] ?? 'HighQ Homes');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');
define('APP_SECRET', $_ENV['APP_SECRET'] ?? 'change-this-secret');
define('ADMIN_LOGIN_PATH', preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($_ENV['ADMIN_LOGIN_PATH'] ?? 'secure-admin-login'))) ?: 'secure-admin-login');
define('UPLOAD_MAX_SIZE', (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 10485760));
define('UPLOAD_DIR', ROOT_PATH . '/' . trim($_ENV['UPLOAD_PATH'] ?? 'uploads', '/'));
define('UPLOAD_URL', APP_URL . '/' . trim($_ENV['UPLOAD_PATH'] ?? 'uploads', '/'));

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    $logDir = ROOT_PATH . '/tmp';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    ini_set('error_log', $logDir . '/php-error.log');
}

if (!APP_DEBUG) {
    set_exception_handler(static function (\Throwable $e): void {
        Production::fail(500, $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    });
}
