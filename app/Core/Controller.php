<?php
declare(strict_types=1);

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
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
        http_response_code($code);
        $view = VIEWS_PATH . "/errors/{$code}.php";
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<h1>Error {$code}</h1>";
        }
        exit;
    }
}
