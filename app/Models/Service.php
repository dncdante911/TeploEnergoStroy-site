<?php

namespace App\Models;

use App\Core\Database;

class Service
{
    public static function all(): array
    {
        return Database::cache('services:all', function () {
            return Database::fetchAll(
                'SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC'
            );
        }, 3600);
    }

    public static function find(int $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM services WHERE id = ?',
            [$id]
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::cache("service:$slug", function () use ($slug) {
            return Database::fetch(
                'SELECT * FROM services WHERE slug = ? AND is_active = 1',
                [$slug]
            );
        }, 3600);
    }

    public static function create(array $data): int
    {
        Database::clearCache('services:*');
        return Database::insert('services', $data);
    }

    public static function update(int $id, array $data): int
    {
        Database::clearCache('services:*');
        Database::clearCache('service:*');
        return Database::update('services', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        Database::clearCache('services:*');
        Database::clearCache('service:*');
        return Database::delete('services', 'id = ?', [$id]);
    }

    public static function getAllForAdmin(): array
    {
        return Database::fetchAll(
            'SELECT * FROM services ORDER BY sort_order ASC'
        );
    }
}
