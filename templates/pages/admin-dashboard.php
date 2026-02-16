<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="section">
    <div class="container">
        <h2>Админ-панель</h2>
        <?php if (!empty($status)): ?><p class="alert-success"><?= htmlspecialchars($status) ?></p><?php endif; ?>
        <form class="contact-form" method="POST" action="/admin/services">
            <h3>Добавить услугу</h3>
            <input name="title" placeholder="Название" required>
            <textarea name="description" placeholder="Описание" rows="3" required></textarea>
            <input name="icon" placeholder="Иконка (текст)" value="snowflake">
            <input name="sort_order" type="number" placeholder="Порядок" value="999">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </form>

        <h3>Существующие услуги</h3>
        <div class="cards">
            <?php foreach ($services as $service): ?>
                <article class="card"><h4><?= htmlspecialchars($service['title']) ?></h4><p><?= htmlspecialchars($service['description']) ?></p></article>
            <?php endforeach; ?>
        </div>

        <h3>Последние заявки</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Дата</th><th>Имя</th><th>Телефон</th><th>Компания</th><th>Сообщение</th></tr></thead>
                <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><?= htmlspecialchars($lead['created_at']) ?></td>
                        <td><?= htmlspecialchars($lead['name']) ?></td>
                        <td><?= htmlspecialchars($lead['phone']) ?></td>
                        <td><?= htmlspecialchars((string) $lead['company']) ?></td>
                        <td><?= htmlspecialchars($lead['message']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form method="POST" action="/admin/logout"><button class="btn" type="submit">Выйти</button></form>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
