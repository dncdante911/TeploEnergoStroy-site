<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="section">
    <div class="container admin-box">
        <h2>Вход в админ-панель</h2>
        <?php if (!empty($error)): ?><p class="alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form class="contact-form" method="POST" action="/admin/login">
            <input type="email" name="email" placeholder="E-mail администратора" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button class="btn btn-primary" type="submit">Войти</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
