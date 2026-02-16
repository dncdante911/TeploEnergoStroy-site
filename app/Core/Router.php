<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes[$method] ?? [] as $route) {
            [$routePath, $handler] = $route;
            if ($routePath === $path) {
                if (is_array($handler) && count($handler) === 2) {
                    [$class, $action] = $handler;
                    return (new $class())->{$action}($request);
                }

                return $handler($request);
            }
        }

        http_response_code(404);
        echo '404 Not Found';
        return null;
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $normalized = '/' . trim($path, '/');
        $this->routes[$method][] = [$normalized === '/' ? '/' : $normalized, $handler];
    }
}
