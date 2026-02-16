<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Компанія</th>
            <th>Автор</th>
            <th>Рейтинг</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Дії</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= $review['id'] ?></td>
                <td><?= htmlspecialchars($review['company_name']) ?></td>
                <td><?= htmlspecialchars($review['author_name']) ?></td>
                <td><?= str_repeat('⭐', $review['rating']) ?></td>
                <td>
                    <?php if ($review['is_approved']): ?>
                        <span class="badge badge-success">Схвалено</span>
                    <?php else: ?>
                        <span class="badge badge-warning">На модерації</span>
                    <?php endif; ?>
                    <?php if ($review['is_featured']): ?>
                        <span class="badge" style="background: #ffc107;">Вибраний</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d.m.Y', strtotime($review['created_at'])) ?></td>
                <td>
                    <?php if (!$review['is_approved']): ?>
                        <a href="/admin/reviews/approve/<?= $review['id'] ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Схвалити</a>
                    <?php endif; ?>
                    <a href="/admin/reviews/featured/<?= $review['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px; background: #ffc107;">⭐</a>
                    <a href="/admin/reviews/delete/<?= $review['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px; background: var(--danger);" onclick="return confirm('Видалити відгук?')">Видалити</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
