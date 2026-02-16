<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\HomeController;

require dirname(__DIR__) . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$home = HomeController::make();
$admin = new AdminController();

if ($path === '/' && $method === 'GET') {
    $home->index();
    return;
}

if ($path === '/contact' && $method === 'POST') {
    $home->sendRequest();
    return;
}

if ($path === '/admin/login' && $method === 'GET') {
    $admin->loginForm();
    return;
}

if ($path === '/admin/login' && $method === 'POST') {
    $admin->login();
    return;
}

if ($path === '/admin' && $method === 'GET') {
    $admin->dashboard();
    return;
}

if ($path === '/admin/logout' && $method === 'POST') {
    $admin->logout();
    return;
}

http_response_code(404);
echo 'Страница не найдена';
