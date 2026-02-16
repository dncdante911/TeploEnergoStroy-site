<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Env;
use App\Core\View;
use App\Repositories\ContentRepository;

final class AdminController
{
    public function loginForm(): void
    {
        View::render('admin/login', [
            'title' => 'Админ-панель — ТеплоЭнергоСтрой',
            'csrf' => Csrf::token(),
            'error' => $_SESSION['admin_error'] ?? null,
        ]);

        unset($_SESSION['admin_error']);
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['admin_error'] = 'Ошибка безопасности.';
            header('Location: /admin/login');
            return;
        }

        $login = trim((string) ($_POST['login'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($login === (string) Env::get('ADMIN_LOGIN') && password_verify($password, (string) Env::get('ADMIN_PASSWORD_HASH', ''))) {
            $_SESSION['admin_auth'] = true;
            header('Location: /admin');
            return;
        }

        $_SESSION['admin_error'] = 'Неверные учетные данные';
        header('Location: /admin/login');
    }

    public function dashboard(): void
    {
        $this->guard();
        $stats = ContentRepository::make()->dashboardData();

        View::render('admin/dashboard', [
            'title' => 'Админ-панель',
            'stats' => $stats,
        ]);
    }

    public function logout(): void
    {
        unset($_SESSION['admin_auth']);
        header('Location: /admin/login');
    }

    private function guard(): void
    {
        if (!($_SESSION['admin_auth'] ?? false)) {
            header('Location: /admin/login');
            exit;
        }
    }
}
