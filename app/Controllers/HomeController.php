<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Review;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index(): void
    {
        $services = Service::all();
        $reviews = Review::featured();
        $settings = Setting::all();

        $this->view('home.index', [
            'services' => $services,
            'reviews' => $reviews,
            'settings' => $settings,
            'pageTitle' => 'Головна',
        ]);
    }

    public function services(): void
    {
        $services = Service::all();
        $settings = Setting::all();

        $this->view('services.index', [
            'services' => $services,
            'settings' => $settings,
            'pageTitle' => 'Наші послуги',
        ]);
    }

    public function serviceDetail(string $slug): void
    {
        $service = Service::findBySlug($slug);

        if (!$service) {
            http_response_code(404);
            echo '404 - Послугу не знайдено';
            exit;
        }

        $settings = Setting::all();

        $this->view('services.detail', [
            'service' => $service,
            'settings' => $settings,
            'pageTitle' => $service['title'],
        ]);
    }

    public function about(): void
    {
        $page = \App\Models\Page::findBySlug('about');
        $settings = Setting::all();

        if (!$page) {
            http_response_code(404);
            echo '404 - Сторінку не знайдено';
            exit;
        }

        $this->view('pages.show', [
            'page' => $page,
            'settings' => $settings,
            'pageTitle' => $page['title'],
        ]);
    }

    public function reviews(): void
    {
        $reviews = Review::approved(50);
        $avgRating = Review::getAverageRating();
        $settings = Setting::all();

        $this->view('reviews.index', [
            'reviews' => $reviews,
            'avgRating' => $avgRating,
            'settings' => $settings,
            'pageTitle' => 'Відгуки клієнтів',
        ]);
    }

    public function contact(): void
    {
        $settings = Setting::all();

        $this->view('contact.index', [
            'settings' => $settings,
            'pageTitle' => 'Контакти',
        ]);
    }
}
