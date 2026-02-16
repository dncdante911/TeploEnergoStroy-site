<?php

declare(strict_types=1);

namespace App\Core;

final class Env
{
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return ($value === false || $value === null || $value === '') ? $default : $value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        return filter_var((string) self::get($key, $default), FILTER_VALIDATE_BOOL);
    }
}
