<?php ob_start(); ?>

<section class="hero">
    <div class="container">
        <h1><?= htmlspecialchars($settings['hero_title'] ?? 'Професійні рішення для холодильного обладнання') ?></h1>
        <p><?= htmlspecialchars($settings['hero_description'] ?? 'Установка, обслуговування та ремонт промислового холодильного обладнання по всій Україні') ?></p>
        <a href="<?= htmlspecialchars($settings['hero_button_link'] ?? '/contact') ?>" class="btn btn-primary"><?= htmlspecialchars($settings['hero_button_text'] ?? 'Замовити консультацію') ?></a>
    </div>
</section>

<section class="services">
    <div class="container">
        <div class="section-title">
            <h2><?= htmlspecialchars($settings['services_section_title'] ?? 'Наші послуги') ?></h2>
            <p><?= htmlspecialchars($settings['services_section_subtitle'] ?? 'Комплексні рішення для вашого бізнесу') ?></p>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <div class="icon">⚙️</div>
                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    <a href="/services/<?= htmlspecialchars($service['slug']) ?>" class="btn">Детальніше</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($reviews)): ?>
<section class="reviews">
    <div class="container">
        <div class="section-title">
            <h2><?= htmlspecialchars($settings['reviews_section_title'] ?? 'Відгуки наших клієнтів') ?></h2>
            <p><?= htmlspecialchars($settings['reviews_section_subtitle'] ?? 'Що кажуть про нас') ?></p>
        </div>

        <div class="reviews-grid">
            <?php foreach (array_slice($reviews, 0, 3) as $review): ?>
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
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/reviews" class="btn btn-secondary"><?= htmlspecialchars($settings['reviews_button_text'] ?? 'Всі відгуки') ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="services" style="background: white;">
    <div class="container">
        <div class="section-title">
            <h2><?= htmlspecialchars($settings['advantages_section_title'] ?? 'Чому обирають нас') ?></h2>
            <p><?= htmlspecialchars($settings['advantages_section_subtitle'] ?? 'Переваги роботи з нами') ?></p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="icon"><?= htmlspecialchars($settings['advantage_1_icon'] ?? '✓') ?></div>
                <h3><?= htmlspecialchars($settings['advantage_1_title'] ?? 'Досвід') ?></h3>
                <p><?= htmlspecialchars($settings['advantage_1_description'] ?? 'Більше 15 років успішної роботи на ринку України') ?></p>
            </div>

            <div class="service-card">
                <div class="icon"><?= htmlspecialchars($settings['advantage_2_icon'] ?? '⚡') ?></div>
                <h3><?= htmlspecialchars($settings['advantage_2_title'] ?? 'Швидкість') ?></h3>
                <p><?= htmlspecialchars($settings['advantage_2_description'] ?? 'Виїзд фахівця протягом 24 годин після звернення') ?></p>
            </div>

            <div class="service-card">
                <div class="icon"><?= htmlspecialchars($settings['advantage_3_icon'] ?? '🛡️') ?></div>
                <h3><?= htmlspecialchars($settings['advantage_3_title'] ?? 'Гарантія') ?></h3>
                <p><?= htmlspecialchars($settings['advantage_3_description'] ?? 'Надаємо гарантію на всі види виконаних робіт') ?></p>
            </div>

            <div class="service-card">
                <div class="icon"><?= htmlspecialchars($settings['advantage_4_icon'] ?? '💼') ?></div>
                <h3><?= htmlspecialchars($settings['advantage_4_title'] ?? 'Професіоналізм') ?></h3>
                <p><?= htmlspecialchars($settings['advantage_4_description'] ?? 'Сертифіковані фахівці з великим досвідом') ?></p>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
