<?php

namespace App\Models;

use App\Core\Database;

class Page
{
    public static function findBySlug(string $slug): ?array
    {
        return Database::cache("page:$slug", function () use ($slug) {
            return Database::fetch(
                'SELECT * FROM pages WHERE slug = ? AND is_active = 1',
                [$slug]
            );
        }, 3600);
    }

    public static function find(int $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM pages WHERE id = ?',
            [$id]
        );
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT * FROM pages ORDER BY title ASC'
        );
    }

    public static function create(array $data): int
    {
        Database::clearCache('page:*');
        return Database::insert('pages', $data);
    }

    public static function update(int $id, array $data): int
    {
        Database::clearCache('page:*');
        return Database::update('pages', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        Database::clearCache('page:*');
        return Database::delete('pages', 'id = ?', [$id]);
    }
}
