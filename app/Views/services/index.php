<?php ob_start(); ?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Наші послуги</h1>
        <p>Комплексні рішення для промислового холодильного обладнання</p>
    </div>
</section>

<section class="services">
    <div class="container">
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <div class="icon">⚙️</div>
                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    <a href="/services/<?= htmlspecialchars($service['slug']) ?>" class="btn btn-primary">Детальніше</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
