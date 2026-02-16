<?php ob_start(); ?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1><?= htmlspecialchars($page['title']) ?></h1>
    </div>
</section>

<section style="padding: 60px 0;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <div style="line-height: 1.8; color: var(--dark);">
                    <?= $page['content'] ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
