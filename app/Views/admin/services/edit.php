<?php ob_start(); ?>

<div style="max-width: 800px;">
    <form action="/admin/services/edit/<?= $service['id'] ?>" method="POST" class="contact-form">
        <div class="form-group">
            <label for="title">Назва послуги *</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($service['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="slug">Slug (URL) *</label>
            <input type="text" id="slug" name="slug" class="form-control" value="<?= htmlspecialchars($service['slug']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Короткий опис *</label>
            <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Повний опис *</label>
            <textarea id="content" name="content" class="form-control" rows="10" required><?= htmlspecialchars($service['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="icon">Іконка</label>
            <input type="text" id="icon" name="icon" class="form-control" value="<?= htmlspecialchars($service['icon'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="sort_order">Порядок сортування</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= $service['sort_order'] ?>">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" <?= $service['is_active'] ? 'checked' : '' ?>> Активна
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Зберегти</button>
        <a href="/admin/services" class="btn btn-secondary">Скасувати</a>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
