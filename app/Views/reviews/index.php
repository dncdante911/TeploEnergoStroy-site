<?php ob_start(); ?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Відгуки клієнтів</h1>
        <p>Думка наших партнерів про співпрацю з нами</p>
        <?php if (isset($avgRating) && $avgRating > 0): ?>
            <p style="font-size: 24px; margin-top: 20px;">Середній рейтинг: <?= str_repeat('⭐', round($avgRating)) ?> (<?= number_format($avgRating, 1) ?>/5)</p>
        <?php endif; ?>
    </div>
</section>

<section class="reviews">
    <div class="container">
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-author">
                            <h4><?= htmlspecialchars($review['company_name']) ?></h4>
                            <p><?= htmlspecialchars($review['author_name']) ?><?= $review['author_position'] ? ', ' . htmlspecialchars($review['author_position']) : '' ?></p>
                        </div>
                        <div class="stars">
                            <?= str_repeat('⭐', $review['rating']) ?>
                        </div>
                    </div>
                    <div class="review-content">
                        <p><?= htmlspecialchars($review['content']) ?></p>
                    </div>
                    <div style="margin-top: 15px; font-size: 14px; color: var(--gray);">
                        <?= date('d.m.Y', strtotime($review['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 60px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h2 style="text-align: center; margin-bottom: 30px;">Залишити відгук</h2>
            <form action="/review/submit" method="POST" class="contact-form">
                <div class="form-group">
                    <label for="company_name">Назва компанії *</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="author_name">Ваше ім'я *</label>
                    <input type="text" id="author_name" name="author_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="author_position">Посада</label>
                    <input type="text" id="author_position" name="author_position" class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="tel" id="phone" name="phone" class="form-control">
                </div>

                <div class="form-group">
                    <label for="rating">Оцінка *</label>
                    <select id="rating" name="rating" class="form-control" required>
                        <option value="5">⭐⭐⭐⭐⭐ (Відмінно)</option>
                        <option value="4">⭐⭐⭐⭐ (Добре)</option>
                        <option value="3">⭐⭐⭐ (Задовільно)</option>
                        <option value="2">⭐⭐ (Погано)</option>
                        <option value="1">⭐ (Дуже погано)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Ваш відгук *</label>
                    <textarea id="content" name="content" class="form-control" required placeholder="Поділіться своїм досвідом співпраці з нами..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Відправити відгук</button>
                <p style="margin-top: 15px; font-size: 14px; color: var(--gray);">* Відгук буде опублікований після модерації</p>
            </form>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
