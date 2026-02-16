<?php ob_start(); ?>

<div style="margin-bottom: 20px;">
    <a href="/admin/services/create" class="btn btn-primary">+ Додати послугу</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Назва</th>
            <th>Slug</th>
            <th>Порядок</th>
            <th>Статус</th>
            <th>Дії</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?= $service['id'] ?></td>
                <td><?= htmlspecialchars($service['title']) ?></td>
                <td><?= htmlspecialchars($service['slug']) ?></td>
                <td><?= $service['sort_order'] ?></td>
                <td>
                    <?php if ($service['is_active']): ?>
                        <span class="badge badge-success">Активна</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Неактивна</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/services/edit/<?= $service['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Редагувати</a>
                    <a href="/admin/services/delete/<?= $service['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px; background: var(--danger);" onclick="return confirm('Видалити цю послугу?')">Видалити</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
