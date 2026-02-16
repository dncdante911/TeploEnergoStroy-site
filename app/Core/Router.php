<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->basePath . $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->getPattern($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        $this->notFound();
    }

    private function getPattern(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function executeHandler($handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            [$controller, $method] = explode('@', $handler);
            $controllerClass = "App\\Controllers\\$controller";

            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                $this->notFound();
            }
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 - Page Not Found';
        exit;
    }
}
