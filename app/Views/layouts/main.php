<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'ТОВ "ТеплоЭнергоСтрой"' ?> | ТеплоЭнергоСтрой</title>
    <meta name="description" content="<?= $settings['meta_description'] ?? 'Професійна установка, обслуговування та ремонт холодильного обладнання для промисловості в Україні' ?>">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="container">
                <div class="header-info">
                    <span><?= $settings['work_hours'] ?? 'Пн-Пт: 9:00 - 18:00' ?></span>
                </div>
                <div class="header-contacts">
                    <a href="tel:<?= $settings['site_phone'] ?? '' ?>"><?= $settings['site_phone'] ?? '+380 XX XXX XX XX' ?></a>
                    <a href="mailto:<?= $settings['site_email'] ?? '' ?>"><?= $settings['site_email'] ?? 'info@teploenergo.ua' ?></a>
                </div>
            </div>
        </div>

        <div class="header-main">
            <div class="container">
                <div class="logo">
                    <h1><?= $settings['site_name'] ?? 'ТОВ "ТеплоЭнергоСтрой"' ?></h1>
                    <p>Холодильне обладнання для промисловості</p>
                </div>

                <nav>
                    <ul>
                        <li><a href="/" <?= $_SERVER['REQUEST_URI'] == '/' ? 'class="active"' : '' ?>>Головна</a></li>
                        <li><a href="/services" <?= strpos($_SERVER['REQUEST_URI'], '/services') === 0 ? 'class="active"' : '' ?>>Послуги</a></li>
                        <li><a href="/about" <?= $_SERVER['REQUEST_URI'] == '/about' ? 'class="active"' : '' ?>>Про нас</a></li>
                        <li><a href="/reviews" <?= $_SERVER['REQUEST_URI'] == '/reviews' ? 'class="active"' : '' ?>>Відгуки</a></li>
                        <li><a href="/contact" <?= $_SERVER['REQUEST_URI'] == '/contact' ? 'class="active"' : '' ?>>Контакти</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <?php if (isset($_SESSION['_flash']['success'])): ?>
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-success">
                    <?= $_SESSION['_flash']['success']; unset($_SESSION['_flash']['success']); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['_flash']['error'])): ?>
            <div class="container" style="margin-top: 20px;">
                <div class="alert alert-error">
                    <?= $_SESSION['_flash']['error']; unset($_SESSION['_flash']['error']); ?>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Про компанію</h3>
                    <p><?= $settings['about_company'] ?? 'ТОВ "ТеплоЭнергоСтрой" - ведуча компанія по установці, обслуговуванню та ремонту холодильного обладнання для промислового сектору України.' ?></p>
                </div>

                <div class="footer-section">
                    <h3>Послуги</h3>
                    <ul>
                        <li><a href="/services">Установка обладнання</a></li>
                        <li><a href="/services">Обслуговування та ремонт</a></li>
                        <li><a href="/services">Модернізація систем</a></li>
                        <li><a href="/services">Сервісне обслуговування</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Контакти</h3>
                    <ul>
                        <li><?= $settings['site_address'] ?? 'Україна, м. Київ' ?></li>
                        <li>Телефон: <a href="tel:<?= $settings['site_phone'] ?? '' ?>"><?= $settings['site_phone'] ?? '+380 XX XXX XX XX' ?></a></li>
                        <li>Email: <a href="mailto:<?= $settings['site_email'] ?? '' ?>"><?= $settings['site_email'] ?? 'info@teploenergo.ua' ?></a></li>
                        <li><?= $settings['work_hours'] ?? 'Пн-Пт: 9:00 - 18:00' ?></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $settings['site_name'] ?? 'ТОВ "ТеплоЭнергоСтрой"' ?>. Всі права захищено.</p>
            </div>
        </div>
    </footer>

    <script src="/js/script.js"></script>
</body>
</html>
