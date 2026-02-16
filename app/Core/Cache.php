<?php

declare(strict_types=1);

namespace App\Core;

use Redis;

final class Cache
{
    private static ?Redis $redis = null;

    public static function client(): ?Redis
    {
        if (self::$redis !== null) {
            return self::$redis;
        }

        if (!class_exists(Redis::class)) {
            return null;
        }

        $redis = new Redis();
        $connected = $redis->connect((string) Env::get('REDIS_HOST', '127.0.0.1'), (int) Env::get('REDIS_PORT', 6379), 1.5);
        if (!$connected) {
            return null;
        }

        $password = (string) Env::get('REDIS_PASSWORD', '');
        if ($password !== '') {
            $redis->auth($password);
        }

        $redis->select((int) Env::get('REDIS_DB', 0));
        self::$redis = $redis;

        return self::$redis;
    }

    public static function get(string $key): mixed
    {
        $redis = self::client();
        if ($redis === null) {
            return null;
        }

        $raw = $redis->get($key);
        return $raw ? json_decode($raw, true, flags: JSON_THROW_ON_ERROR) : null;
    }

    public static function put(string $key, mixed $value, int $ttl = 300): void
    {
        $redis = self::client();
        if ($redis === null) {
            return;
        }

        $redis->setex($key, $ttl, json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }

    public static function delete(string $key): void
    {
        $redis = self::client();
        if ($redis !== null) {
            $redis->del($key);
        }
    }
}
