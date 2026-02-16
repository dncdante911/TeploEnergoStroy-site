<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View not found: $view");
        }
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function request(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_REQUEST;
        }
        return $_REQUEST[$key] ?? $default;
    }

    protected function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    protected function session(string $key = null, $value = null)
    {
        if ($value !== null) {
            $_SESSION[$key] = $value;
            return $value;
        }

        if ($key === null) {
            return $_SESSION;
        }

        return $_SESSION[$key] ?? null;
    }

    protected function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    protected function getFlash(string $key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    protected function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $rules = explode('|', $ruleSet);
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field] = "Поле обязательно для заполнения";
                    break;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = "Минимальная длина: $min символов";
                        break;
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = "Максимальная длина: $max символов";
                        break;
                    }
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Некорректный email адрес";
                    break;
                }
            }
        }

        return $errors;
    }

    protected function auth(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    protected function requireAuth(): void
    {
        if (!$this->auth()) {
            $this->redirect('/admin/login');
        }
    }
}
