<?php

namespace App\Models;

use App\Core\Database;

class Contact
{
    public static function create(array $data): int
    {
        return Database::insert('contacts', $data);
    }

    public static function find(int $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM contacts WHERE id = ?',
            [$id]
        );
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT * FROM contacts ORDER BY created_at DESC'
        );
    }

    public static function unread(): array
    {
        return Database::fetchAll(
            'SELECT * FROM contacts WHERE is_read = 0 ORDER BY created_at DESC'
        );
    }

    public static function markAsRead(int $id): int
    {
        return Database::update('contacts', ['is_read' => 1], 'id = ?', [$id]);
    }

    public static function markAsProcessed(int $id, string $notes = ''): int
    {
        return Database::update('contacts', [
            'is_processed' => 1,
            'processed_at' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ], 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('contacts', 'id = ?', [$id]);
    }

    public static function countUnread(): int
    {
        $result = Database::fetch('SELECT COUNT(*) as count FROM contacts WHERE is_read = 0');
        return (int)$result['count'];
    }
}
