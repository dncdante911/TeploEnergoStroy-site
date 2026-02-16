<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cache;
use App\Core\Config;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\LeadRepository;
use App\Repositories\ServiceRepository;
use App\Services\AuthService;

final class AdminController
{
    private function authService(): AuthService
    {
        return new AuthService(Container::get(Config::class), Container::get(Session::class));
    }

    public function loginForm(Request $request): void
    {
        /** @var Response $response */
        $response = Container::get(Response::class);
        /** @var Session $session */
        $session = Container::get(Session::class);

        $response->view('pages/admin-login', [
            'error' => $session->getFlash('error'),
        ]);
    }

    public function login(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');

        /** @var Response $response */
        $response = Container::get(Response::class);
        /** @var Session $session */
        $session = Container::get(Session::class);

        if ($this->authService()->login($email, $password)) {
            $response->redirect('/admin/dashboard');
        }

        $session->flash('error', 'Неверный логин или пароль');
        $response->redirect('/admin');
    }

    public function dashboard(Request $request): void
    {
        if (!$this->authService()->check()) {
            Container::get(Response::class)->redirect('/admin');
        }

        $serviceRepository = new ServiceRepository(Container::get(\App\Core\Database::class));
        $leadRepository = new LeadRepository(Container::get(\App\Core\Database::class));

        Container::get(Response::class)->view('pages/admin-dashboard', [
            'services' => $serviceRepository->all(),
            'leads' => $leadRepository->latest(),
            'status' => Container::get(Session::class)->getFlash('status'),
        ]);
    }

    public function createService(Request $request): void
    {
        if (!$this->authService()->check()) {
            Container::get(Response::class)->redirect('/admin');
        }

        (new ServiceRepository(Container::get(\App\Core\Database::class)))->create([
            'title' => trim((string) $request->input('title', '')),
            'description' => trim((string) $request->input('description', '')),
            'icon' => trim((string) $request->input('icon', 'snowflake')),
            'sort_order' => (int) $request->input('sort_order', 999),
        ]);

        Container::get(Cache::class)->forget('site:services');
        Container::get(Session::class)->flash('status', 'Услуга добавлена');
        Container::get(Response::class)->redirect('/admin/dashboard');
    }

    public function logout(Request $request): void
    {
        $this->authService()->logout();
        Container::get(Response::class)->redirect('/admin');
    }
}
