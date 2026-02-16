<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AdminController;
use App\Controllers\ApiController;
use App\Controllers\HomeController;

final class App
{
    private Config $config;
    private Session $session;

    public function __construct(private readonly string $basePath)
    {
        $this->config = new Config($basePath);
        $this->session = new Session();
        $this->session->start();
        Container::set(Config::class, $this->config);
        Container::set(Database::class, new Database($this->config));
        Container::set(Cache::class, new Cache($this->config));
        Container::set(Session::class, $this->session);
        Container::set(Response::class, new Response());
    }

    public function run(): void
    {
        $router = new Router();
        $request = new Request();

        $router->get('/', [HomeController::class, 'index']);
        $router->post('/contact', [HomeController::class, 'contact']);
        $router->get('/admin', [AdminController::class, 'loginForm']);
        $router->post('/admin/login', [AdminController::class, 'login']);
        $router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
        $router->post('/admin/services', [AdminController::class, 'createService']);
        $router->post('/admin/logout', [AdminController::class, 'logout']);

        $router->get('/api/services', [ApiController::class, 'services']);
        $router->get('/api/reviews', [ApiController::class, 'reviews']);

        $router->dispatch($request);
    }
}
