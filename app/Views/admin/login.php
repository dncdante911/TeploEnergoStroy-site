<section class="container section small">
    <h1>Вход в админ-панель</h1>
    <?php if (!empty($error)): ?><div class="flash err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" action="/admin/login" class="form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
        <label>Логин <input name="login" required></label>
        <label>Пароль <input type="password" name="password" required></label>
        <button class="btn" type="submit">Войти</button>
    </form>
</section>
