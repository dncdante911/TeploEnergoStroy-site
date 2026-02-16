<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Repositories\ReviewRepository;
use App\Repositories\ServiceRepository;

final class ApiController
{
    public function services(Request $request): void
    {
        $services = (new ServiceRepository(Container::get(\App\Core\Database::class)))->all();
        Container::get(Response::class)->json(['data' => $services]);
    }

    public function reviews(Request $request): void
    {
        $reviews = (new ReviewRepository(Container::get(\App\Core\Database::class)))->latest(20);
        Container::get(Response::class)->json(['data' => $reviews]);
    }
}
