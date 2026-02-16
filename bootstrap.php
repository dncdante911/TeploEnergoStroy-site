<?php

declare(strict_types=1);

use App\Core\Env;

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

$envPath = __DIR__ . '/.env';
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv($key . '=' . $value);
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$hash = (string) Env::get('ADMIN_PASSWORD_HASH', '');
if ($hash === '') {
    $rawPassword = (string) Env::get('ADMIN_PASSWORD', 'change_me_strong_password');
    $_ENV['ADMIN_PASSWORD_HASH'] = password_hash($rawPassword, PASSWORD_BCRYPT);
}
