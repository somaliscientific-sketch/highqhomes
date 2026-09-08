<?php
declare(strict_types=1);

class CSRF
{
    private const TOKEN_KEY = '_csrf_token';

    public static function generate(): string
    {
        if (!Session::has(self::TOKEN_KEY)) {
            Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        }
        return Session::get(self::TOKEN_KEY);
    }

    public static function token(): string
    {
        return self::generate();
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . self::generate() . '">';
    }

    public static function verify(): bool
    {
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $stored = Session::get(self::TOKEN_KEY, '');
        return is_string($token) && is_string($stored) && $stored !== '' && hash_equals($stored, $token);
    }

    public static function check(): void
    {
        if (!self::verify()) {
            http_response_code(403);
            if (Security::isAdminLoginRequest()) {
                Session::flash('error', 'Security token expired. Please sign in again.');
                header('Location: ' . Security::adminLoginUrl());
                exit;
            }
            if (Security::isAdminRequest()) {
                Session::flash('error', 'Security token expired. Please try again.');
                header('Location: ' . APP_URL . '/admin/dashboard');
                exit;
            }
            if (defined('APP_DEBUG') && APP_DEBUG) {
                die('CSRF token mismatch.');
            }
            http_response_code(403);
            echo 'Security check failed. Please refresh and try again.';
            exit;
        }
        Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
    }
}
