<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ReviewRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    public function latest(int $limit = 6): array
    {
        $stmt = $this->database->pdo()->prepare('SELECT company_name, person_name, review_text, rating FROM reviews WHERE is_active = 1 ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
