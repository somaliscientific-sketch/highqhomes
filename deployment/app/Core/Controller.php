<?php
declare(strict_types=1);

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    /**
     * Load CMS/public data without taking the whole page down if MySQL is unavailable.
     */
    protected function tryLoad(callable $loader, mixed $fallback = null): mixed
    {
        try {
            return $loader();
        } catch (\Throwable $e) {
            Production::log(static::class . ': ' . $e->getMessage());
            return $fallback;
        }
    }

    protected function redirect(string $path): never
    {
        $url = str_starts_with($path, 'http') ? $path : APP_URL . $path;
        header("Location: {$url}");
        exit;
    }

    protected function back(): never
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/admin';
        header("Location: {$referer}");
        exit;
    }

    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string
    {
        return strip_tags(trim($value));
    }

    protected function requireAuth(): void
    {
        Auth::require();
    }

    protected function requirePermission(string $permission): void
    {
        Auth::requirePermission($permission);
    }

    protected function requireView(): void
    {
        $this->requirePermission('content.view');
    }

    protected function requireManage(): void
    {
        $this->requirePermission('content.manage');
    }

    protected function requireAdmin(): void
    {
        $this->requirePermission('settings.manage');
    }

    protected function audit(
        string $action,
        string $module,
        string $description,
        ?string $entityType = null,
        ?int $entityId = null
    ): void {
        Audit::log($action, $module, $description, $entityType, $entityId);
    }

    protected function abort(int $code = 404): never
    {
        $allowed = [403, 404, 419, 500];
        $page = in_array($code, $allowed, true) ? $code : 404;
        if ($page === 500 || $page === 419) {
            Production::fail($page, 'Controller abort ' . $page);
        }
        http_response_code($page);
        $view = VIEWS_PATH . "/errors/{$page}.php";
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<h1>Error {$page}</h1>";
        }
        exit;
    }
}
