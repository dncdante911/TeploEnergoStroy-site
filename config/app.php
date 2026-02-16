<?php

use App\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'ТОВ ТеплоЭнергоСтрой'),
    'env' => Env::get('APP_ENV', 'production'),
    'debug' => filter_var(Env::get('APP_DEBUG', false), FILTER_VALIDATE_BOOL),
    'url' => Env::get('APP_URL', 'http://localhost:8080'),
    'admin_email' => Env::get('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => Env::get('ADMIN_PASSWORD', 'ChangeMeNow123!'),
];
