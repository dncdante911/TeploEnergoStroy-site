<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Env;

$basePath = dirname(__DIR__);

require_once $basePath . '/bootstrap/autoload.php';

Env::load($basePath . '/.env');

return new App($basePath);
