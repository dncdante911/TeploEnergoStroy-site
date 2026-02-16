<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private array $config = [];

    public function __construct(private readonly string $basePath)
    {
        $this->loadConfig();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = $this->config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private function loadConfig(): void
    {
        foreach (glob($this->basePath . '/config/*.php') ?: [] as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $this->config[$name] = require $file;
        }
    }
}
