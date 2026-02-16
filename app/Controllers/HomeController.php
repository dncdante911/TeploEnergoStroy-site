<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cache;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Repositories\LeadRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\ServiceRepository;
use App\Validation\Validator;

final class HomeController
{
    public function index(Request $request): void
    {
        /** @var Cache $cache */
        $cache = Container::get(Cache::class);
        /** @var Response $response */
        $response = Container::get(Response::class);
        /** @var Session $session */
        $session = Container::get(Session::class);

        $services = $cache->get('site:services');
        if ($services === null) {
            $services = (new ServiceRepository(Container::get(\App\Core\Database::class)))->all();
            $cache->set('site:services', $services, 600);
        }

        $reviews = $cache->get('site:reviews');
        if ($reviews === null) {
            $reviews = (new ReviewRepository(Container::get(\App\Core\Database::class)))->latest();
            $cache->set('site:reviews', $reviews, 600);
        }

        $response->view('pages/home', [
            'services' => $services,
            'reviews' => $reviews,
            'flashSuccess' => $session->getFlash('success'),
            'flashErrors' => $session->getFlash('errors', []),
            'oldInput' => $session->getFlash('old', []),
        ]);
    }

    public function contact(Request $request): void
    {
        /** @var Response $response */
        $response = Container::get(Response::class);
        /** @var Session $session */
        $session = Container::get(Session::class);

        $payload = [
            'name' => trim((string) $request->input('name', '')),
            'phone' => trim((string) $request->input('phone', '')),
            'email' => trim((string) $request->input('email', '')),
            'company' => trim((string) $request->input('company', '')),
            'message' => trim((string) $request->input('message', '')),
            'source_url' => $_SERVER['HTTP_REFERER'] ?? '/',
        ];

        $errors = Validator::validateContact($payload);

        if ($errors !== []) {
            $session->flash('errors', $errors);
            $session->flash('old', $payload);
            $response->redirect('/#contact');
        }

        (new LeadRepository(Container::get(\App\Core\Database::class)))->create($payload);
        $session->flash('success', 'Спасибо! Заявка принята. Наш инженер свяжется с вами в ближайшее время.');
        $response->redirect('/#contact');
    }
}
