<?php

namespace App\Core;

class App
{
    private Router $router;

    public function __construct()
    {
        $this->loadEnv();
        $this->startSession();
        $this->router = new Router();
    }

    private function loadEnv(): void
    {
        $envFile = __DIR__ . '/../../.env';

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../../config/app.php';

            session_set_cookie_params([
                'lifetime' => $config['session']['lifetime'],
                'path' => $config['session']['path'],
                'domain' => $config['session']['domain'],
                'secure' => $config['session']['secure'],
                'httponly' => $config['session']['httponly'],
            ]);

            session_start();
        }
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}
