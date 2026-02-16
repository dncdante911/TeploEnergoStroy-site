<?php ob_start(); ?>

<div style="max-width: 800px;">
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="margin-bottom: 20px;">Деталі заявки #<?= $contact['id'] ?></h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <strong>Компанія:</strong>
                <p><?= htmlspecialchars($contact['company_name']) ?></p>
            </div>
            <div>
                <strong>Контактна особа:</strong>
                <p><?= htmlspecialchars($contact['contact_name']) ?></p>
            </div>
            <div>
                <strong>Email:</strong>
                <p><a href="mailto:<?= $contact['email'] ?>"><?= htmlspecialchars($contact['email']) ?></a></p>
            </div>
            <div>
                <strong>Телефон:</strong>
                <p><a href="tel:<?= $contact['phone'] ?>"><?= htmlspecialchars($contact['phone']) ?></a></p>
            </div>
            <?php if ($contact['subject']): ?>
            <div style="grid-column: 1 / -1;">
                <strong>Тема:</strong>
                <p><?= htmlspecialchars($contact['subject']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>Повідомлення:</strong>
            <p style="background: var(--light); padding: 15px; border-radius: 5px; margin-top: 10px;">
                <?= nl2br(htmlspecialchars($contact['message'])) ?>
            </p>
        </div>

        <div style="border-top: 1px solid var(--light); padding-top: 20px; margin-top: 20px;">
            <p style="color: var(--gray); font-size: 14px;">
                <strong>Дата звернення:</strong> <?= date('d.m.Y H:i', strtotime($contact['created_at'])) ?><br>
                <strong>IP адреса:</strong> <?= htmlspecialchars($contact['ip_address']) ?>
            </p>
        </div>
    </div>

    <?php if (!$contact['is_processed']): ?>
    <form action="/admin/contacts/view/<?= $contact['id'] ?>" method="POST" class="contact-form">
        <h3 style="margin-bottom: 20px;">Позначити як оброблено</h3>

        <div class="form-group">
            <label for="notes">Примітки</label>
            <textarea id="notes" name="notes" class="form-control" rows="5" placeholder="Введіть примітки про обробку заявки..."></textarea>
        </div>

        <button type="submit" class="btn btn-success">Позначити як оброблено</button>
        <a href="/admin/contacts" class="btn btn-secondary">Назад до списку</a>
    </form>
    <?php else: ?>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <strong>Примітки:</strong>
            <p><?= nl2br(htmlspecialchars($contact['notes'] ?? 'Немає приміток')) ?></p>
            <p style="color: var(--gray); font-size: 14px; margin-top: 10px;">
                Оброблено: <?= date('d.m.Y H:i', strtotime($contact['processed_at'])) ?>
            </p>
        </div>
        <div style="margin-top: 20px;">
            <a href="/admin/contacts" class="btn btn-secondary">Назад до списку</a>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
