<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Session;

final class AuthService
{
    public function __construct(private readonly Config $config, private readonly Session $session)
    {
    }

    public function login(string $email, string $password): bool
    {
        $adminEmail = (string) $this->config->get('app.admin_email');
        $adminPassword = (string) $this->config->get('app.admin_password');

        if ($email === $adminEmail && hash_equals($adminPassword, $password)) {
            $this->session->put('admin_auth', true);
            return true;
        }

        return false;
    }

    public function check(): bool
    {
        return (bool) $this->session->get('admin_auth', false);
    }

    public function logout(): void
    {
        $this->session->forget('admin_auth');
    }
}
