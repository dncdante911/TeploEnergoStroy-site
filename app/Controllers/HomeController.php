<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\View;
use App\Services\SiteService;

final class HomeController
{
    public function __construct(private readonly SiteService $service)
    {
    }

    public static function make(): self
    {
        return new self(SiteService::make());
    }

    public function index(): void
    {
        $data = $this->service->homepageData();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        View::render('pages/home', [
            ...$data,
            'title' => 'ТОВ ТеплоЭнергоСтрой — промышленный холод и сервис 24/7',
            'csrf' => Csrf::token(),
            'flash' => $flash,
        ]);
    }

    public function sendRequest(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ошибка CSRF. Обновите страницу.'];
            header('Location: /#contact');
            return;
        }

        $result = $this->service->submitContact($_POST);
        $_SESSION['flash'] = $result['ok']
            ? ['type' => 'success', 'message' => 'Спасибо! Мы свяжемся с вами в течение 15 минут.']
            : ['type' => 'error', 'message' => 'Проверьте корректность заполнения формы.'];

        header('Location: /#contact');
    }
}
