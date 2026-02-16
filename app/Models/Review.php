<?php

namespace App\Models;

use App\Core\Database;

class Review
{
    public static function approved(int $limit = 10): array
    {
        return Database::cache("reviews:approved:$limit", function () use ($limit) {
            return Database::fetchAll(
                'SELECT * FROM reviews WHERE is_approved = 1 ORDER BY created_at DESC LIMIT ?',
                [$limit]
            );
        }, 1800);
    }

    public static function featured(): array
    {
        return Database::cache('reviews:featured', function () {
            return Database::fetchAll(
                'SELECT * FROM reviews WHERE is_approved = 1 AND is_featured = 1 ORDER BY created_at DESC LIMIT 6'
            );
        }, 1800);
    }

    public static function find(int $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM reviews WHERE id = ?',
            [$id]
        );
    }

    public static function create(array $data): int
    {
        Database::clearCache('reviews:*');
        return Database::insert('reviews', $data);
    }

    public static function update(int $id, array $data): int
    {
        Database::clearCache('reviews:*');
        return Database::update('reviews', $data, 'id = ?', [$id]);
    }

    public static function approve(int $id): int
    {
        return self::update($id, [
            'is_approved' => 1,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function delete(int $id): int
    {
        Database::clearCache('reviews:*');
        return Database::delete('reviews', 'id = ?', [$id]);
    }

    public static function pending(): array
    {
        return Database::fetchAll(
            'SELECT * FROM reviews WHERE is_approved = 0 ORDER BY created_at DESC'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT * FROM reviews ORDER BY created_at DESC'
        );
    }

    public static function getAverageRating(): float
    {
        $result = Database::fetch(
            'SELECT AVG(rating) as avg_rating FROM reviews WHERE is_approved = 1'
        );
        return round($result['avg_rating'] ?? 0, 1);
    }
}
