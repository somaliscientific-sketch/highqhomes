<?php
declare(strict_types=1);

class Auth
{
    private const USER_KEY = '_auth_user';
    private const ADMIN_ROLES = ['super_admin', 'admin'];

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        Session::set(self::USER_KEY, [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ]);
        Session::set('_auth_last_activity', time());
        Session::set('_auth_fingerprint', Security::sessionFingerprint());
    }

    public static function logout(): void
    {
        Session::remove(self::USER_KEY);
        Session::remove('_auth_last_activity');
        Session::remove('_auth_fingerprint');
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return Session::has(self::USER_KEY);
    }

    public static function user(): ?array
    {
        return Session::get(self::USER_KEY);
    }

    public static function id(): ?int
    {
        return Session::get(self::USER_KEY)['id'] ?? null;
    }

    public static function role(): string
    {
        return (string)(self::user()['role'] ?? '');
    }

    public static function isAdminRole(): bool
    {
        return in_array(self::role(), self::ADMIN_ROLES, true);
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 'super_admin';
    }

    public static function roleLabel(): string
    {
        static $labels = [];
        $role = self::role();
        if ($role === '') {
            return 'User';
        }
        if (isset($labels[$role])) {
            return $labels[$role];
        }
        try {
            $row = (new RoleModel())->findBy('name', $role);
            $labels[$role] = $row['label'] ?? ucwords(str_replace('_', ' ', $role));
        } catch (\Throwable $e) {
            $labels[$role] = ucwords(str_replace('_', ' ', $role));
        }
        return $labels[$role];
    }

    public static function require(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in to access the admin panel.');
            header('Location: ' . Security::adminLoginUrl());
            exit;
        }

        $userId = (int)(self::user()['id'] ?? 0);
        if ($userId > 0) {
            try {
                $dbUser = (new UserModel())->findById($userId);
                if (!$dbUser || !(int)$dbUser['is_active']) {
                    self::logout();
                    Session::flash('error', 'Your account has been disabled. Please contact an administrator.');
                    header('Location: ' . Security::adminLoginUrl());
                    exit;
                }
                Session::set(self::USER_KEY, [
                    'id'    => (int)$dbUser['id'],
                    'name'  => (string)$dbUser['name'],
                    'email' => (string)$dbUser['email'],
                    'role'  => (string)$dbUser['role'],
                ]);
            } catch (\Throwable $e) {
                self::logout();
                Session::flash('error', 'Your session could not be verified. Please sign in again.');
                header('Location: ' . Security::adminLoginUrl());
                exit;
            }
        }

        $last = (int)Session::get('_auth_last_activity', 0);
        if ($last > 0 && (time() - $last) > Security::adminIdleSeconds()) {
            self::logout();
            Session::flash('error', 'Your session expired due to inactivity. Please sign in again.');
            header('Location: ' . Security::adminLoginUrl());
            exit;
        }

        $fingerprint = (string)Session::get('_auth_fingerprint', '');
        if ($fingerprint !== '' && !hash_equals($fingerprint, Security::sessionFingerprint())) {
            self::logout();
            Session::flash('error', 'Your session was ended for security reasons. Please sign in again.');
            header('Location: ' . Security::adminLoginUrl());
            exit;
        }

        Session::set('_auth_last_activity', time());
    }

    public static function permissions(): array
    {
        $user = self::user();
        if (!$user) {
            return [];
        }

        static $cache = [];
        $role = (string)($user['role'] ?? '');
        if ($role === '') {
            return [];
        }
        if (array_key_exists($role, $cache)) {
            return $cache[$role];
        }

        try {
            $row = (new RoleModel())->findBy('name', $role);
            $permissions = json_decode((string)($row['permissions'] ?? '[]'), true);
            $cache[$role] = is_array($permissions) ? $permissions : [];
        } catch (\Throwable $e) {
            $cache[$role] = [];
        }

        return $cache[$role];
    }

    public static function can(string $permission): bool
    {
        $permissions = self::permissions();
        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }

    public static function requirePermission(string $permission): void
    {
        self::require();
        if (!self::can($permission)) {
            http_response_code(403);
            Session::flash('error', 'You do not have permission to access that area.');
            header('Location: ' . APP_URL . '/admin/dashboard');
            exit;
        }
    }

    public static function guest(): void
    {
        if (self::check()) {
            header('Location: ' . APP_URL . '/admin/dashboard');
            exit;
        }
    }
}
