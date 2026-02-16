#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Database;
use App\Core\Env;

require_once dirname(__DIR__) . '/bootstrap/autoload.php';
Env::load(dirname(__DIR__) . '/.env');

$config = new Config(dirname(__DIR__));
$database = new Database($config);
$pdo = $database->pdo();

$sql = file_get_contents(dirname(__DIR__) . '/database/seeds/seed.sql');
$pdo->exec($sql);

echo "Seed data inserted\n";
