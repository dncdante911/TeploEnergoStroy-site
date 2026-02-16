<?php

use App\Core\Container;
use App\Core\Config;

$config = Container::get(Config::class);
$appName = $config->get('app.name', 'ТОВ ТеплоЭнергоСтрой');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?> — Промышленный холод и сервис оборудования</title>
    <meta name="description" content="ТОВ ТеплоЭнергоСтрой — проектирование, монтаж и сервис промышленных холодильных систем, компрессорных станций и технологического оборудования.">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="header">
    <div class="container nav-wrap">
        <div class="logo">ТеплоЭнергоСтрой</div>
        <nav>
            <a href="#services">Услуги</a>
            <a href="#about">О компании</a>
            <a href="#reviews">Отзывы</a>
            <a href="#contact">Контакты</a>
        </nav>
        <a class="btn btn-primary" href="tel:+380000000000">+38 (000) 000-00-00</a>
    </div>
</header>
<main>
