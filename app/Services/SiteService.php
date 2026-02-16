<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use App\Repositories\ContentRepository;
use Throwable;

final class SiteService
{
    private ?ContentRepository $repository = null;

    public static function make(): self
    {
        return new self();
    }

    public function homepageData(): array
    {
        $cacheKey = 'homepage_data_v1';
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        try {
            $repository = $this->repository();
            $data = [
                'services' => $repository->allServices(),
                'projects' => $repository->allProjects(),
                'stats' => $repository->companyStats(),
                'testimonials' => $repository->testimonials(),
            ];
        } catch (Throwable) {
            $data = $this->fallbackData();
        }

        Cache::put($cacheKey, $data, 900);
        return $data;
    }

    public function submitContact(array $payload): array
    {
        $errors = [];
        $required = ['name', 'phone', 'message'];

        foreach ($required as $field) {
            if (trim((string)($payload[$field] ?? '')) === '') {
                $errors[$field] = 'Поле обязательно для заполнения';
            }
        }

        if (!empty($payload['email']) && !filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный формат email';
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        try {
            $this->repository()->saveContactRequest([
                'name' => trim((string) $payload['name']),
                'phone' => trim((string) $payload['phone']),
                'email' => trim((string) ($payload['email'] ?? '')),
                'company' => trim((string) ($payload['company'] ?? '')),
                'message' => trim((string) $payload['message']),
            ]);
            Cache::delete('homepage_data_v1');
        } catch (Throwable) {
            // no-op fallback for offline demo environment
        }

        return ['ok' => true];
    }

    private function repository(): ContentRepository
    {
        if ($this->repository === null) {
            $this->repository = ContentRepository::make();
        }

        return $this->repository;
    }

    private function fallbackData(): array
    {
        return [
            'services' => [
                ['icon' => '🧪', 'title' => 'Аудит холодильных систем', 'description' => 'Диагностика, энергоаудит и техническое обследование.'],
                ['icon' => '🛠️', 'title' => 'Плановый сервис', 'description' => 'Регламентное обслуживание промышленного холода.'],
                ['icon' => '🚨', 'title' => 'Аварийный ремонт 24/7', 'description' => 'Выезд и восстановление работоспособности.'],
            ],
            'projects' => [
                ['name' => 'Пищевой комбинат', 'summary' => 'Снижение аварийности на 65%', 'city' => 'Днепр', 'completed_at' => '2025-02-11'],
                ['name' => 'Логистический хаб', 'summary' => 'Модернизация холодильного контура', 'city' => 'Киев', 'completed_at' => '2024-12-03'],
            ],
            'stats' => [
                ['value' => '14+', 'label' => 'лет практики'],
                ['value' => '28', 'label' => 'инженеров'],
                ['value' => '170+', 'label' => 'объектов на сервисе'],
                ['value' => '24/7', 'label' => 'аварийная поддержка'],
            ],
            'testimonials' => [
                ['author_name' => 'Александр П.', 'author_position' => 'Главный энергетик', 'text' => 'Оперативно вернули линию в работу.'],
                ['author_name' => 'Ирина Б.', 'author_position' => 'Операционный директор', 'text' => 'Высокая культура сервиса и отчетности.'],
            ],
        ];
    }
}
