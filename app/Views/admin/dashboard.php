<section class="container section">
    <div class="row-between">
        <h1>Админ-панель</h1>
        <form method="post" action="/admin/logout"><button class="btn btn-outline">Выйти</button></form>
    </div>
    <div class="stats">
        <article><strong><?= (int)$stats['total'] ?></strong><span>Всего заявок</span></article>
        <article><strong><?= (int)$stats['today'] ?></strong><span>За сегодня</span></article>
    </div>
    <h2>Последние обращения</h2>
    <table class="table">
        <thead><tr><th>Имя</th><th>Телефон</th><th>Компания</th><th>Дата</th></tr></thead>
        <tbody>
        <?php foreach ($stats['latest'] as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['company']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
