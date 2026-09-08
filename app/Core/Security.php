<?php
declare(strict_types=1);

class Security
{
    private const LOGIN_MAX_ATTEMPTS_DEFAULT = 5;
    private const LOGIN_LOCK_SECONDS_DEFAULT = 900;
    private const ADMIN_IDLE_SECONDS_DEFAULT = 7200;

    public static function loginMaxAttempts(): int
    {
        $v = (int)setting('security_max_attempts', (string)self::LOGIN_MAX_ATTEMPTS_DEFAULT);
        return max(3, min(10, $v ?: self::LOGIN_MAX_ATTEMPTS_DEFAULT));
    }

    public static function loginLockSeconds(): int
    {
        $mins = (int)setting('security_lockout_minutes', '15');
        $mins = max(5, min(60, $mins ?: 15));
        return $mins * 60;
    }

    public static function adminIdleSeconds(): int
    {
        $hours = (int)setting('security_session_hours', '2');
        $hours = max(1, min(12, $hours ?: 2));
        return $hours * 3600;
    }

    public static function boot(): void
    {
        if (self::isAdminRequest()) {
            self::sendAdminHeaders();
        } else {
            self::sendPublicHeaders();
        }
    }

    public static function requestPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $base = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        if ($base && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = '/' . ltrim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public static function adminLoginPath(): string
    {
        return '/' . ADMIN_LOGIN_PATH;
    }

    public static function adminLoginUrl(): string
    {
        return APP_URL . self::adminLoginPath();
    }

    public static function isAdminLoginRequest(): bool
    {
        return self::requestPath() === self::adminLoginPath();
    }

    public static function isAdminRequest(): bool
    {
        $path = self::requestPath();
        return str_starts_with($path, '/admin') || $path === self::adminLoginPath();
    }

    public static function isLegacyAdminLoginRequest(): bool
    {
        return self::requestPath() === '/admin/login';
    }

    public static function sendAdminHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header('X-Robots-Tag: noindex, nofollow');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }

    public static function sendPublicHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    public static function loginAllowed(string $email): bool
    {
        $key = self::loginKey($email);
        $data = Session::get('_login_guard', []);
        $entry = $data[$key] ?? null;
        if (!$entry) {
            return true;
        }
        if (!empty($entry['locked_until']) && time() < (int)$entry['locked_until']) {
            return false;
        }
        return (int)($entry['count'] ?? 0) < self::loginMaxAttempts();
    }

    public static function loginLockRemaining(string $email): int
    {
        $key = self::loginKey($email);
        $data = Session::get('_login_guard', []);
        $lockedUntil = (int)($data[$key]['locked_until'] ?? 0);
        return max(0, $lockedUntil - time());
    }

    public static function recordFailedLogin(string $email): void
    {
        $key = self::loginKey($email);
        $data = Session::get('_login_guard', []);
        $count = (int)($data[$key]['count'] ?? 0) + 1;
        $data[$key] = [
            'count' => $count,
            'locked_until' => $count >= self::loginMaxAttempts() ? time() + self::loginLockSeconds() : 0,
        ];
        Session::set('_login_guard', $data);
    }

    public static function clearLoginAttempts(string $email): void
    {
        $key = self::loginKey($email);
        $data = Session::get('_login_guard', []);
        unset($data[$key]);
        Session::set('_login_guard', $data);
    }

    public static function sessionFingerprint(): string
    {
        $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
        return hash('sha256', $ua . APP_SECRET);
    }

    private static function loginKey(string $email): string
    {
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        return hash('sha256', strtolower(trim($email)) . '|' . $ip);
    }
}
