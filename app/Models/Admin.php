<?php

namespace App\Models;

use App\Core\Database;

class Admin
{
    public static function findByUsername(string $username): ?array
    {
        return Database::fetch(
            'SELECT * FROM admins WHERE username = ? AND is_active = 1',
            [$username]
        );
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetch(
            'SELECT * FROM admins WHERE email = ? AND is_active = 1',
            [$email]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::fetch(
            'SELECT * FROM admins WHERE id = ?',
            [$id]
        );
    }

    public static function authenticate(string $username, string $password): ?array
    {
        $admin = self::findByUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            self::updateLastLogin($admin['id']);
            return $admin;
        }

        return null;
    }

    private static function updateLastLogin(int $id): void
    {
        Database::update('admins', [
            'last_login' => date('Y-m-d H:i:s')
        ], 'id = ?', [$id]);
    }

    public static function create(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return Database::insert('admins', $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return Database::update('admins', $data, 'id = ?', [$id]);
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT id, username, email, full_name, created_at, last_login, is_active FROM admins ORDER BY created_at DESC'
        );
    }
}
