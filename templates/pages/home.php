<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="hero">
    <div class="container hero-content">
        <h1>Комплексное обслуживание промышленных холодильных систем и технологического оборудования</h1>
        <p>ТОВ "ТеплоЭнергоСтрой" обеспечивает проектирование, поставку, монтаж, модернизацию и сервис холодильных установок для пищевой промышленности, логистики и производства.</p>
        <a class="btn btn-primary" href="#contact">Получить консультацию инженера</a>
    </div>
</section>

<section id="services" class="section">
    <div class="container">
        <h2>Наши услуги</h2>
        <div class="cards">
            <?php foreach ($services as $service): ?>
                <article class="card">
                    <h3><?= htmlspecialchars($service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="about" class="section section-dark">
    <div class="container">
        <h2>Почему нас выбирают</h2>
        <ul class="features">
            <li>15+ лет опыта в промышленном холоде</li>
            <li>Сервисные бригады с выездом 24/7</li>
            <li>Собственный склад критических запчастей</li>
            <li>Инженерный аудит и энергоэффективная модернизация</li>
        </ul>
    </div>
</section>

<section id="reviews" class="section">
    <div class="container">
        <h2>Отзывы клиентов</h2>
        <div class="cards">
            <?php foreach ($reviews as $review): ?>
                <article class="card">
                    <h3><?= htmlspecialchars($review['company_name']) ?></h3>
                    <p><strong><?= htmlspecialchars($review['person_name']) ?></strong></p>
                    <p><?= htmlspecialchars($review['review_text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contact" class="section section-accent">
    <div class="container">
        <h2>Оставить заявку</h2>
        <?php if (!empty($flashSuccess)): ?>
            <p class="alert-success"><?= htmlspecialchars($flashSuccess) ?></p>
        <?php endif; ?>
        <form class="contact-form" action="/contact" method="POST">
            <input name="name" placeholder="Ваше имя" value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>">
            <?php if (!empty($flashErrors['name'])): ?><small><?= htmlspecialchars($flashErrors['name']) ?></small><?php endif; ?>

            <input name="phone" placeholder="Телефон" value="<?= htmlspecialchars($oldInput['phone'] ?? '') ?>">
            <?php if (!empty($flashErrors['phone'])): ?><small><?= htmlspecialchars($flashErrors['phone']) ?></small><?php endif; ?>

            <input name="email" placeholder="E-mail" value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>">
            <input name="company" placeholder="Компания" value="<?= htmlspecialchars($oldInput['company'] ?? '') ?>">

            <textarea name="message" rows="5" placeholder="Описание задачи"><?= htmlspecialchars($oldInput['message'] ?? '') ?></textarea>
            <?php if (!empty($flashErrors['message'])): ?><small><?= htmlspecialchars($flashErrors['message']) ?></small><?php endif; ?>

            <button class="btn btn-primary" type="submit">Отправить заявку</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
