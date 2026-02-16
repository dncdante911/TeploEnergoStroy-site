<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Slug</th>
            <th>Назва</th>
            <th>Статус</th>
            <th>Дії</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= $page['id'] ?></td>
                <td><?= htmlspecialchars($page['slug']) ?></td>
                <td><?= htmlspecialchars($page['title']) ?></td>
                <td>
                    <?php if ($page['is_active']): ?>
                        <span class="badge badge-success">Активна</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Неактивна</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/pages/edit/<?= $page['id'] ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Редагувати</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
