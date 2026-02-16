<?php ob_start(); ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Компанія</th>
            <th>Контакт</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Дії</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr style="<?= !$contact['is_read'] ? 'font-weight: bold;' : '' ?>">
                <td><?= $contact['id'] ?></td>
                <td><?= htmlspecialchars($contact['company_name']) ?></td>
                <td><?= htmlspecialchars($contact['contact_name']) ?></td>
                <td><?= htmlspecialchars($contact['email']) ?></td>
                <td><?= htmlspecialchars($contact['phone']) ?></td>
                <td>
                    <?php if ($contact['is_processed']): ?>
                        <span class="badge badge-success">Оброблено</span>
                    <?php elseif ($contact['is_read']): ?>
                        <span class="badge" style="background: #17a2b8; color: white;">Прочитано</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Нова</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($contact['created_at'])) ?></td>
                <td>
                    <a href="/admin/contacts/view/<?= $contact['id'] ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Переглянути</a>
                    <a href="/admin/contacts/delete/<?= $contact['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px; background: var(--danger);" onclick="return confirm('Видалити заявку?')">Видалити</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
