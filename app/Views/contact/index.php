<?php ob_start(); ?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Контакти</h1>
        <p>Зв'яжіться з нами будь-яким зручним способом</p>
    </div>
</section>

<section style="padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; max-width: 1000px; margin: 0 auto;">
            <div>
                <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <h2 style="margin-bottom: 30px; color: var(--secondary);">Наші контакти</h2>

                    <div style="margin-bottom: 25px;">
                        <h3 style="color: var(--primary); margin-bottom: 10px;">📍 Адреса</h3>
                        <p><?= htmlspecialchars($settings['site_address'] ?? 'Україна, м. Київ') ?></p>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h3 style="color: var(--primary); margin-bottom: 10px;">📞 Телефон</h3>
                        <p><a href="tel:<?= $settings['site_phone'] ?? '' ?>" style="color: var(--dark);"><?= htmlspecialchars($settings['site_phone'] ?? '+380 XX XXX XX XX') ?></a></p>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h3 style="color: var(--primary); margin-bottom: 10px;">✉️ Email</h3>
                        <p><a href="mailto:<?= $settings['site_email'] ?? '' ?>" style="color: var(--dark);"><?= htmlspecialchars($settings['site_email'] ?? 'info@teploenergo.ua') ?></a></p>
                    </div>

                    <div>
                        <h3 style="color: var(--primary); margin-bottom: 10px;">🕐 Години роботи</h3>
                        <p><?= htmlspecialchars($settings['work_hours'] ?? 'Пн-Пт: 9:00 - 18:00') ?></p>
                    </div>
                </div>
            </div>

            <div>
                <form action="/contact/submit" method="POST" class="contact-form">
                    <h2 style="margin-bottom: 30px; color: var(--secondary);">Форма зв'язку</h2>

                    <div class="form-group">
                        <label for="company_name">Назва компанії *</label>
                        <input type="text" id="company_name" name="company_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_name">Ваше ім'я *</label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Тема звернення</label>
                        <input type="text" id="subject" name="subject" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="message">Повідомлення *</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Відправити</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>
