<?php ob_start(); ?>

<div class="stats-grid">
    <div class="stat-card">
        <h3><?= $stats['services'] ?? 0 ?></h3>
        <p>Послуг</p>
    </div>
    <div class="stat-card">
        <h3><?= $stats['reviews_pending'] ?? 0 ?></h3>
        <p>Відгуків на модерації</p>
    </div>
    <div class="stat-card">
        <h3><?= $stats['contacts_unread'] ?? 0 ?></h3>
        <p>Непрочитаних заявок</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px;">Останні заявки</h2>
        <?php if (!empty($recentContacts)): ?>
            <table style="box-shadow: none;">
                <thead>
                    <tr>
                        <th>Компанія</th>
                        <th>Контакт</th>
                        <th>Дата</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentContacts as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['company_name']) ?></td>
                            <td><?= htmlspecialchars($contact['contact_name']) ?></td>
                            <td><?= date('d.m.Y', strtotime($contact['created_at'])) ?></td>
                            <td>
                                <a href="/admin/contacts/view/<?= $contact['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">Переглянути</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: var(--gray);">Немає нових заявок</p>
        <?php endif; ?>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px;">Відгуки на модерації</h2>
        <?php if (!empty($pendingReviews)): ?>
            <table style="box-shadow: none;">
                <thead>
                    <tr>
                        <th>Компанія</th>
                        <th>Рейтинг</th>
                        <th>Дата</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingReviews as $review): ?>
                        <tr>
                            <td><?= htmlspecialchars($review['company_name']) ?></td>
                            <td><?= str_repeat('⭐', $review['rating']) ?></td>
                            <td><?= date('d.m.Y', strtotime($review['created_at'])) ?></td>
                            <td>
                                <a href="/admin/reviews/approve/<?= $review['id'] ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Схвалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: var(--gray);">Немає відгуків на модерації</p>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
