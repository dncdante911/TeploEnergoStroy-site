<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід до адмін-панелі | ТеплоЭнергоСтрой</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-box h1 {
            text-align: center;
            color: var(--secondary);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Адмін-панель</h1>
            <p style="text-align: center; color: var(--gray); margin-bottom: 30px;">ТОВ "ТеплоЭнергоСтрой"</p>

            <?php if (isset($_SESSION['_flash']['error'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['_flash']['error']; unset($_SESSION['_flash']['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/admin/auth" method="POST">
                <div class="form-group">
                    <label for="username">Логін</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Увійти</button>
            </form>

            <p style="margin-top: 20px; text-align: center; font-size: 14px; color: var(--gray);">
                Дані для входу за замовчуванням:<br>
                Логін: <strong>admin</strong><br>
                Пароль: <strong>admin123</strong>
            </p>
        </div>
    </div>
</body>
</html>
