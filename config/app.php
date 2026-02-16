<?php

return [
    'name' => 'ТОВ "ТеплоЭнергоСтрой"',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 7200,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => true,
    ],

    'mail' => [
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'username' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'from' => $_ENV['MAIL_FROM'] ?? 'noreply@teploenergo.ua',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'ТеплоЭнергоСтрой',
    ]
];
