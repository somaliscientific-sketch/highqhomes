<?php
declare(strict_types=1);

class Production
{
    public const CANONICAL_HOST = 'highqhomes.site';

    public static function isHttps(): bool
    {
        $forwarded = strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
        if ($forwarded === 'https') {
            return true;
        }
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }
        return (string)($_SERVER['SERVER_PORT'] ?? '') === '443';
    }

    public static function requestHost(): string
    {
        $host = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
        return (string)preg_replace('/:\d+$/', '', $host);
    }

    public static function isLiveDomain(): bool
    {
        $host = self::requestHost();
        return $host === self::CANONICAL_HOST || $host === 'www.' . self::CANONICAL_HOST;
    }

    public static function applyLiveOverrides(): void
    {
        if (PHP_SAPI === 'cli' || !self::isLiveDomain()) {
            return;
        }

        $_ENV['APP_URL'] = 'https://' . self::CANONICAL_HOST;
        $_ENV['APP_ENV'] = 'production';
        $_ENV['APP_DEBUG'] = 'false';
        putenv('APP_URL=https://' . self::CANONICAL_HOST);
        putenv('APP_ENV=production');
        putenv('APP_DEBUG=false');
        $_SERVER['HTTPS'] = 'on';
    }

    public static function enforceCanonical(): void
    {
        if (PHP_SAPI === 'cli' || !self::isLiveDomain() || headers_sent()) {
            return;
        }

        $host = self::requestHost();
        $https = self::isHttps();
        if ($https && $host === self::CANONICAL_HOST) {
            return;
        }

        $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: https://' . self::CANONICAL_HOST . $uri, true, 301);
        exit;
    }

    public static function log(string $message): void
    {
        $dir = ROOT_PATH . '/tmp';
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        @file_put_contents(
            $dir . '/app.log',
            '[' . date('c') . '] ' . $message . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    public static function fail(int $code, string $internal): never
    {
        http_response_code($code);
        if (defined('APP_DEBUG') && APP_DEBUG) {
            die($internal);
        }
        self::log($internal);
        $view = VIEWS_PATH . '/errors/' . ($code === 404 ? '404' : '500') . '.php';
        if (file_exists($view)) {
            $settings = [];
            try {
                $settings = (new SettingModel())->getAllAsMap();
            } catch (\Throwable $e) {
                $settings = [];
            }
            include $view;
        } else {
            echo 'An error occurred.';
        }
        exit;
    }
}
