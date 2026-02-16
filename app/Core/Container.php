<?php

declare(strict_types=1);

namespace App\Core;

final class Container
{
    private static array $items = [];

    public static function set(string $id, mixed $value): void
    {
        self::$items[$id] = $value;
    }

    public static function get(string $id): mixed
    {
        if (!array_key_exists($id, self::$items)) {
            throw new \RuntimeException("Container entry {$id} not found");
        }

        return self::$items[$id];
    }
}
