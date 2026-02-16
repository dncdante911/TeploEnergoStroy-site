<?php

namespace App\Core;

use PDO;
use PDOException;
use Redis;

class Database
{
    private static ?PDO $pdo = null;
    private static ?Redis $redis = null;
    private static array $config = [];

    public static function connect(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        self::$config = require __DIR__ . '/../../config/database.php';

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                self::$config['host'],
                self::$config['port'],
                self::$config['database'],
                self::$config['charset']
            );

            self::$pdo = new PDO(
                $dsn,
                self::$config['username'],
                self::$config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return self::$pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function redis(): ?Redis
    {
        if (self::$redis !== null) {
            return self::$redis;
        }

        if (empty(self::$config)) {
            self::$config = require __DIR__ . '/../../config/database.php';
        }

        try {
            self::$redis = new Redis();
            self::$redis->connect(
                self::$config['redis']['host'],
                self::$config['redis']['port']
            );

            if (!empty(self::$config['redis']['password'])) {
                self::$redis->auth(self::$config['redis']['password']);
            }

            return self::$redis;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        self::query($sql, $values);
        return (int)self::connect()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $fields),
            $where
        );

        $stmt = self::query($sql, array_merge($values, $whereParams));
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function cache(string $key, callable $callback, int $ttl = 3600)
    {
        $redis = self::redis();

        if ($redis) {
            $cached = $redis->get($key);
            if ($cached !== false) {
                return json_decode($cached, true);
            }
        }

        $data = $callback();

        if ($redis && $data !== null) {
            $redis->setex($key, $ttl, json_encode($data));
        }

        return $data;
    }

    public static function clearCache(string $pattern = '*'): void
    {
        $redis = self::redis();
        if ($redis) {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                $redis->del($keys);
            }
        }
    }
}
