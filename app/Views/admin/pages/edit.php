<?php ob_start(); ?>

<div style="max-width: 800px;">
    <form action="/admin/pages/edit/<?= $page['id'] ?>" method="POST" class="contact-form">
        <div class="form-group">
            <label for="title">Назва сторінки *</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($page['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Контент *</label>
            <textarea id="content" name="content" class="form-control" rows="15" required><?= htmlspecialchars($page['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" <?= $page['is_active'] ? 'checked' : '' ?>> Активна
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Зберегти</button>
        <a href="/admin/pages" class="btn btn-secondary">Скасувати</a>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
