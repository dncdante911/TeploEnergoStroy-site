<?php ob_start(); ?>

<style>
.settings-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.settings-section h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary);
    color: var(--primary);
    font-size: 18px;
}

.settings-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}

.form-group {
    margin-bottom: 15px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}
</style>

<div style="max-width: 1200px;">
    <form action="/admin/settings" method="POST" class="contact-form">

        <?php
        // Групуємо налаштування за префіксами
        $grouped = [
            'site' => ['title' => '🏢 Основні налаштування сайту', 'items' => []],
            'hero' => ['title' => '🎯 Hero секція (головна сторінка)', 'items' => []],
            'logo' => ['title' => '🏷️ Логотип', 'items' => []],
            'services_section' => ['title' => '📋 Секція послуг', 'items' => []],
            'reviews' => ['title' => '⭐ Секція відгуків', 'items' => []],
            'advantages_section' => ['title' => '✨ Секція "Чому обирають нас" - Заголовки', 'items' => []],
            'advantage' => ['title' => '✨ Переваги (картки)', 'items' => []],
            'footer' => ['title' => '📄 Футер', 'items' => []],
            'other' => ['title' => '⚙️ Інші налаштування', 'items' => []],
        ];

        foreach ($settings as $setting) {
            $placed = false;
            foreach ($grouped as $prefix => $group) {
                if (strpos($setting['setting_key'], $prefix) === 0) {
                    $grouped[$prefix]['items'][] = $setting;
                    $placed = true;
                    break;
                }
            }
            if (!$placed) {
                $grouped['other']['items'][] = $setting;
            }
        }
        ?>

        <?php foreach ($grouped as $prefix => $group): ?>
            <?php if (!empty($group['items'])): ?>
                <div class="settings-section">
                    <h3><?= $group['title'] ?></h3>
                    <div class="settings-grid">
                        <?php foreach ($group['items'] as $setting): ?>
                            <div class="form-group <?= $setting['setting_type'] === 'textarea' ? 'full-width' : '' ?>">
                                <label for="setting_<?= htmlspecialchars($setting['setting_key']) ?>">
                                    <?= htmlspecialchars($setting['description'] ?? $setting['setting_key']) ?>
                                </label>

                                <?php if ($setting['setting_type'] === 'textarea'): ?>
                                    <textarea
                                        id="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                                        name="setting_<?= htmlspecialchars($setting['setting_key']) ?>"
                                        class="form-control"
                                        rows="3"
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
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div style="position: sticky; bottom: 20px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 16px;">
                💾 Зберегти всі налаштування
            </button>
        </div>
    </form>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>
