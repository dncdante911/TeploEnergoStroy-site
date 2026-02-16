<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ServiceRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    public function all(): array
    {
        return $this->database->pdo()->query('SELECT id, title, description, icon, created_at FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public function create(array $payload): void
    {
        $stmt = $this->database->pdo()->prepare('INSERT INTO services (title, description, icon, sort_order) VALUES (:title, :description, :icon, :sort_order)');
        $stmt->execute([
            ':title' => $payload['title'],
            ':description' => $payload['description'],
            ':icon' => $payload['icon'] ?? 'snowflake',
            ':sort_order' => (int) ($payload['sort_order'] ?? 999),
        ]);
    }
}
