<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Адмін-панель' ?> | ТеплоЭнергоСтрой</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2 style="margin-bottom: 30px; color: var(--accent);">Адмін-панель</h2>

            <ul>
                <li><a href="/admin/dashboard" <?= $_SERVER['REQUEST_URI'] == '/admin/dashboard' || $_SERVER['REQUEST_URI'] == '/admin' ? 'class="active"' : '' ?>>📊 Головна</a></li>
                <li><a href="/admin/services" <?= strpos($_SERVER['REQUEST_URI'], '/admin/services') === 0 ? 'class="active"' : '' ?>>⚙️ Послуги</a></li>
                <li><a href="/admin/reviews" <?= strpos($_SERVER['REQUEST_URI'], '/admin/reviews') === 0 ? 'class="active"' : '' ?>>⭐ Відгуки</a></li>
                <li><a href="/admin/contacts" <?= strpos($_SERVER['REQUEST_URI'], '/admin/contacts') === 0 ? 'class="active"' : '' ?>>✉️ Заявки</a></li>
                <li><a href="/admin/pages" <?= strpos($_SERVER['REQUEST_URI'], '/admin/pages') === 0 ? 'class="active"' : '' ?>>📄 Сторінки</a></li>
                <li><a href="/admin/settings" <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') === 0 ? 'class="active"' : '' ?>>⚙️ Налаштування</a></li>
                <li><a href="/" target="_blank">🌐 Перегляд сайту</a></li>
                <li><a href="/admin/logout">🚪 Вихід</a></li>
            </ul>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="font-size: 14px; opacity: 0.7;">Увійшли як:</p>
                <p style="font-weight: 600;"><?= $_SESSION['admin_username'] ?? 'Admin' ?></p>
            </div>
        </aside>

        <div class="admin-content">
            <div class="admin-header">
                <h1><?= $pageTitle ?? 'Адмін-панель' ?></h1>
            </div>

            <?php if (isset($_SESSION['_flash']['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['_flash']['success']; unset($_SESSION['_flash']['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['_flash']['error'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['_flash']['error']; unset($_SESSION['_flash']['error']); ?>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </div>
</body>
</html>
