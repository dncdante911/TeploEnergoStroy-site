<?php

declare(strict_types=1);

namespace App\Validation;

final class Validator
{
    public static function validateContact(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Введите имя';
        }

        if (empty(trim($data['phone'] ?? ''))) {
            $errors['phone'] = 'Введите телефон';
        }

        if (empty(trim($data['message'] ?? ''))) {
            $errors['message'] = 'Введите описание задачи';
        }

        return $errors;
    }
}
