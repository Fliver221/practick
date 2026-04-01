<?php
namespace App\Core;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get($path, $action)
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action)
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch()
    {
        $method = request_method();
        $path = current_path();

        if (!isset($this->routes[$method][$path])) {
            $controller = new \App\Controllers\ErrorController();
            $controller->notFound();
            return;
        }

        list($controllerName, $methodName) = explode('@', $this->routes[$method][$path], 2);
        $class = '\\App\\Controllers\\' . $controllerName;

        if (!class_exists($class) || !method_exists($class, $methodName)) {
            $controller = new \App\Controllers\ErrorController();
            $controller->notFound();
            return;
        }

        $controller = new $class();
        call_user_func([$controller, $methodName]);
    }
}
