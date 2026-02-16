<?php
/**
 * 🔐 СКРИПТ ДЛЯ ЗМІНИ ПАРОЛЯ АДМІНІСТРАТОРА
 *
 * Використання:
 * 1. Відкрийте: https://radio-active.top/reset-admin-password.php
 * 2. Введіть новий пароль
 * 3. Скопіюйте SQL команду
 * 4. Виконайте в phpMyAdmin
 * 5. ⚠️ ВИДАЛІТЬ ЦЕЙ ФАЙЛ ПІСЛЯ ВИКОРИСТАННЯ!
 */

// Налаштування безпеки
$allowed_ips = ['127.0.0.1', '::1']; // Дозволено тільки з localhost
$remote_ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Якщо хочете дозволити з будь-якої IP - закоментуйте наступні 4 рядки:
// if (!in_array($remote_ip, $allowed_ips)) {
//     die('❌ Доступ заборонено! Цей скрипт можна запускати тільки з localhost.');
// }

$newPassword = '';
$passwordHash = '';
$sqlCommand = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $newPassword = $_POST['password'];
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $sqlCommand = "UPDATE admins SET password = '$passwordHash' WHERE username = 'admin';";
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Зміна пароля адміністратора</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .sql-box {
            background: #f8f9fa;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-wrap: break-word;
            position: relative;
        }
        .copy-btn {
            background: #28a745;
            padding: 8px 15px;
            font-size: 14px;
            margin-top: 10px;
            width: auto;
        }
        .step {
            background: #f8f9fa;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }
        .danger {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: 600;
        }
        small {
            color: #666;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Зміна пароля адміністратора</h1>
        <p style="color: #666; margin-bottom: 20px;">Скрипт для відновлення доступу до адмін-панелі</p>

        <div class="warning">
            <strong>⚠️ Важливо!</strong><br>
            Після використання обов'язково <strong>ВИДАЛІТЬ</strong> цей файл з сервера!
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="password">🔑 Введіть новий пароль:</label>
                <input
                    type="text"
                    id="password"
                    name="password"
                    placeholder="Наприклад: MySecurePass123!"
                    required
                    minlength="6"
                    value="<?= htmlspecialchars($newPassword) ?>"
                >
                <small>Мінімум 6 символів. Використовуйте великі/малі літери, цифри та символи.</small>
            </div>

            <button type="submit">🔒 Згенерувати новий пароль</button>
        </form>

        <?php if ($passwordHash): ?>
        <div class="success">
            <strong>✅ Пароль згенеровано!</strong><br>
            Виконайте наступні кроки:
        </div>

        <div style="margin: 20px 0;">
            <div class="step">
                <strong>Крок 1:</strong> Скопіюйте SQL команду нижче
            </div>

            <div class="sql-box" id="sqlBox">
                <?= htmlspecialchars($sqlCommand) ?>
            </div>
            <button class="copy-btn" onclick="copySql()">📋 Скопіювати SQL</button>

            <div class="step">
                <strong>Крок 2:</strong> Відкрийте phpMyAdmin → Виберіть базу <code>tes</code> → Вкладка SQL
            </div>

            <div class="step">
                <strong>Крок 3:</strong> Вставте скопійовану SQL команду → Натисніть "Вперед"
            </div>

            <div class="step">
                <strong>Крок 4:</strong> Тепер можете увійти:
                <ul style="margin-top: 10px;">
                    <li>Логін: <code><strong>admin</strong></code></li>
                    <li>Пароль: <code><strong><?= htmlspecialchars($newPassword) ?></strong></code></li>
                </ul>
            </div>
        </div>

        <div class="danger">
            ⚠️ ВИДАЛІТЬ цей файл після використання:<br>
            <code>/public/reset-admin-password.php</code>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee;">
            <h3 style="margin-bottom: 15px;">📚 Довідка</h3>

            <h4 style="margin: 15px 0 10px;">Де зберігаються паролі?</h4>
            <ul style="line-height: 1.8;">
                <li>📊 <strong>База даних:</strong> таблиця <code>admins</code></li>
                <li>🔒 <strong>Формат:</strong> хешовані через PHP <code>password_hash()</code></li>
                <li>🔐 <strong>Алгоритм:</strong> bcrypt (PASSWORD_DEFAULT)</li>
                <li>❌ <strong>Конфіг файли:</strong> паролів немає, все в БД!</li>
            </ul>

            <h4 style="margin: 15px 0 10px;">Альтернативний спосіб (через phpMyAdmin):</h4>
            <ol style="line-height: 1.8;">
                <li>Згенеруйте хеш тут</li>
                <li>Відкрийте phpMyAdmin</li>
                <li>Виберіть базу <code>tes</code> → таблиця <code>admins</code></li>
                <li>Знайдіть користувача <code>admin</code> → кнопка "Редагувати"</li>
                <li>Вставте новий хеш в поле <code>password</code></li>
                <li>Збережіть</li>
            </ol>
        </div>
    </div>

    <script>
        function copySql() {
            const sqlBox = document.getElementById('sqlBox');
            const text = sqlBox.textContent;

            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = '✅ Скопійовано!';
                btn.style.background = '#28a745';

                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                }, 2000);
            });
        }
    </script>
</body>
</html>
