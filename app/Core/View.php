<?php
namespace App\Core;

class View
{
    public static function render($view, array $data = [], $layout = 'main')
    {
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        $layoutPath = APP_PATH . '/Views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        include $layoutPath;
    }
}
