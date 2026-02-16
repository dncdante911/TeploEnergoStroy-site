<?php

use App\Core\Env;

return [
    'host' => Env::get('REDIS_HOST', '127.0.0.1'),
    'port' => (int) Env::get('REDIS_PORT', 6379),
    'password' => Env::get('REDIS_PASSWORD', ''),
    'database' => (int) Env::get('REDIS_DB', 0),
];
