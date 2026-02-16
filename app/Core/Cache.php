<?php

declare(strict_types=1);

namespace App\Core;

use Redis;

final class Cache
{
    private ?Redis $redis = null;

    public function __construct(private readonly Config $config)
    {
    }

    public function get(string $key): mixed
    {
        $data = $this->connection()->get($key);
        return $data === false ? null : json_decode($data, true);
    }

    public function set(string $key, mixed $value, int $ttl = 300): void
    {
        $this->connection()->setex($key, $ttl, json_encode($value, JSON_UNESCAPED_UNICODE));
    }

    public function forget(string $key): void
    {
        $this->connection()->del([$key]);
    }

    private function connection(): Redis
    {
        if ($this->redis instanceof Redis) {
            return $this->redis;
        }

        $redis = new Redis();
        $redis->connect((string) $this->config->get('cache.host'), (int) $this->config->get('cache.port'));

        $password = $this->config->get('cache.password');
        if (!empty($password)) {
            $redis->auth((string) $password);
        }

        $redis->select((int) $this->config->get('cache.database', 0));
        $this->redis = $redis;

        return $this->redis;
    }
}
