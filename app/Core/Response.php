<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public function view(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);

        $templatePath = dirname(__DIR__, 2) . '/templates/' . $template . '.php';
        if (!file_exists($templatePath)) {
            echo 'Template not found';
            return;
        }

        include $templatePath;
    }

    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
