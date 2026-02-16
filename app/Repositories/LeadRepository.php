<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class LeadRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    public function create(array $payload): void
    {
        $stmt = $this->database->pdo()->prepare('INSERT INTO contact_requests (name, phone, email, company, message, source_url) VALUES (:name, :phone, :email, :company, :message, :source_url)');
        $stmt->execute([
            ':name' => $payload['name'],
            ':phone' => $payload['phone'],
            ':email' => $payload['email'] ?: null,
            ':company' => $payload['company'] ?: null,
            ':message' => $payload['message'],
            ':source_url' => $payload['source_url'] ?? '/',
        ]);
    }

    public function latest(int $limit = 20): array
    {
        $stmt = $this->database->pdo()->prepare('SELECT name, phone, email, company, message, created_at FROM contact_requests ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
