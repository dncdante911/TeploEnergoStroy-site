<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class ContentRepository
{
    public function __construct(private readonly PDO $db)
    {
    }

    public static function make(): self
    {
        return new self(Database::connection());
    }

    public function allServices(): array
    {
        $stmt = $this->db->query('SELECT id, title, description, icon FROM services WHERE is_active=1 ORDER BY sort_order, id');
        return $stmt->fetchAll();
    }

    public function allProjects(): array
    {
        $stmt = $this->db->query('SELECT id, name, city, summary, completed_at FROM projects WHERE is_active=1 ORDER BY completed_at DESC LIMIT 6');
        return $stmt->fetchAll();
    }

    public function companyStats(): array
    {
        $stmt = $this->db->query('SELECT metric_key, metric_label, metric_value FROM company_stats ORDER BY sort_order, id');
        $rows = $stmt->fetchAll();

        return array_map(static fn(array $row): array => [
            'key' => $row['metric_key'],
            'label' => $row['metric_label'],
            'value' => $row['metric_value'],
        ], $rows);
    }

    public function testimonials(): array
    {
        $stmt = $this->db->query('SELECT author_name, author_position, text FROM testimonials WHERE is_active=1 ORDER BY sort_order, id LIMIT 4');
        return $stmt->fetchAll();
    }

    public function saveContactRequest(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO contact_requests(name, phone, email, company, message, created_at) VALUES(:name,:phone,:email,:company,:message,NOW())');
        $stmt->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':company' => $data['company'],
            ':message' => $data['message'],
        ]);
    }

    public function dashboardData(): array
    {
        $total = (int) $this->db->query('SELECT COUNT(*) FROM contact_requests')->fetchColumn();
        $today = (int) $this->db->query('SELECT COUNT(*) FROM contact_requests WHERE DATE(created_at)=CURDATE()')->fetchColumn();
        $latest = $this->db->query('SELECT name, phone, company, created_at FROM contact_requests ORDER BY created_at DESC LIMIT 10')->fetchAll();

        return ['total' => $total, 'today' => $today, 'latest' => $latest];
    }
}
