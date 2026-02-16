<?php ob_start(); ?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1><?= htmlspecialchars($service['title']) ?></h1>
    </div>
</section>

<section style="padding: 60px 0;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h2 style="color: var(--secondary); margin-bottom: 20px;"><?= htmlspecialchars($service['title']) ?></h2>
                <p style="color: var(--gray); font-size: 18px; margin-bottom: 30px;"><?= htmlspecialchars($service['description']) ?></p>

                <div style="line-height: 1.8; color: var(--dark);">
                    <?= $service['content'] ?>
                </div>

                <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid var(--light);">
                    <h3 style="margin-bottom: 20px;">Замовити послугу</h3>
                    <p>Зацікавлені в цій послузі? Зв'яжіться з нами для детальної консультації.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top: 20px;">Зв'язатися з нами</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
