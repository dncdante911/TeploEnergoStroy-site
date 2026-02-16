<?php

namespace App\Models;

use App\Core\Database;

class Setting
{
    public static function get(string $key, $default = null)
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    public static function all(): array
    {
        return Database::cache('settings:all', function () {
            $result = Database::fetchAll('SELECT * FROM settings');
            $settings = [];
            foreach ($result as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        }, 3600);
    }

    public static function set(string $key, $value): int
    {
        Database::clearCache('settings:*');

        $existing = Database::fetch(
            'SELECT id FROM settings WHERE setting_key = ?',
            [$key]
        );

        if ($existing) {
            return Database::update('settings', [
                'setting_value' => $value
            ], 'setting_key = ?', [$key]);
        } else {
            return Database::insert('settings', [
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
    }

    public static function getAllForAdmin(): array
    {
        return Database::fetchAll('SELECT * FROM settings ORDER BY setting_key ASC');
    }

    public static function update(int $id, array $data): int
    {
        Database::clearCache('settings:*');
        return Database::update('settings', $data, 'id = ?', [$id]);
    }
}
