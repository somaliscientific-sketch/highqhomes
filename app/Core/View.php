<?php
declare(strict_types=1);

class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Expose data as local vars inside the view template
        extract($data, EXTR_SKIP);

        // Render the view into $content
        ob_start();
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            Production::fail(500, "View not found: {$view}");
        }
        include $viewFile;
        $content = ob_get_clean();

        // Render the layout (which embeds $content)
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }
        include $layoutFile;
    }

    public static function partial(string $partial, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = VIEWS_PATH . '/partials/' . $partial . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }

    public static function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
