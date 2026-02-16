<?php ob_start(); ?>

<div style="max-width: 800px;">
    <form action="/admin/settings" method="POST" class="contact-form">
        <?php foreach ($settings as $setting): ?>
            <div class="form-group">
                <label for="setting_<?= htmlspecialchars($setting['setting_key']) ?>">
                    <?= htmlspecialchars($setting['description'] ?? $setting['setting_key']) ?>
                </label>

                <?php if ($setting['setting_type'] === 'textarea'): ?>
                    <textarea
                        id="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        name="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($setting['setting_value'] ?? '') ?></textarea>
                <?php elseif ($setting['setting_type'] === 'boolean'): ?>
                    <select
                        id="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        name="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        class="form-control"
                    >
                        <option value="1" <?= $setting['setting_value'] == '1' ? 'selected' : '' ?>>Так</option>
                        <option value="0" <?= $setting['setting_value'] == '0' ? 'selected' : '' ?>>Ні</option>
                    </select>
                <?php else: ?>
                    <input
                        type="<?= $setting['setting_type'] === 'number' ? 'number' : 'text' ?>"
                        id="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        name="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                        class="form-control"
                        value="<?= htmlspecialchars($setting['setting_value'] ?? '') ?>"
                    >
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Зберегти налаштування</button>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
