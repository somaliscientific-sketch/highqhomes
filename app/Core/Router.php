<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function post(string $pattern, string $handler): void
    {
        $this->routes[] = ['POST', $pattern, $handler];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $_SERVER['REQUEST_URI'];

        // Strip base path and query string
        $base = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        if ($base && stripos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }
        $path = '/' . ltrim($path, '/');
        if ($path !== '/' ) $path = rtrim($path, '/');

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) continue;

            $regex = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (!preg_match($regex, $path, $matches)) continue;

            // Extract named params
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            // Resolve handler
            [$class, $action] = explode('@', $handler);

            // Support Admin\ClassName namespace notation
            $parts = explode('\\', $class);
            $className = end($parts);
            // Prefix Admin_ if in Admin namespace
            if (count($parts) > 1 && $parts[0] === 'Admin') {
                $className = 'Admin' . $className;
            }

            if (!class_exists($className)) {
                http_response_code(500);
                die("Controller not found: {$className}");
            }

            $controller = new $className();
            if (!method_exists($controller, $action)) {
                http_response_code(500);
                die("Action not found: {$className}@{$action}");
            }

            $controller->$action($params);
            return;
        }

        // No route matched — 404
        http_response_code(404);
        $view = VIEWS_PATH . '/errors/404.php';
        if (file_exists($view)) {
            // Load settings for layout
            try {
                $settingModel = new SettingModel();
                $settings = $settingModel->getAllAsMap();
            } catch (\Throwable $e) {
                $settings = [];
            }
            include $view;
        } else {
            echo '<h1>404 Not Found</h1>';
        }
    }
}
