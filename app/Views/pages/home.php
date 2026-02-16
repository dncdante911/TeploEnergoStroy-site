<header class="hero" id="top">
    <nav class="nav container">
        <a href="#top" class="logo">ТеплоЭнергоСтрой</a>
        <div class="menu">
            <a href="#services">Услуги</a>
            <a href="#projects">Проекты</a>
            <a href="#contact">Контакты</a>
            <a href="/admin/login" class="btn btn-outline">Вход</a>
        </div>
    </nav>
    <div class="container hero-grid">
        <div>
            <p class="tag">Промышленный холод • Монтаж • Сервис 24/7</p>
            <h1>Профессиональный сервис промышленных холодильников и инженерного оборудования</h1>
            <p>ТОВ «ТеплоЭнергоСтрой» обеспечивает бесперебойную работу холодильных камер, чиллеров, компрессорных и вентиляционных систем на производствах и складах.</p>
            <a href="#contact" class="btn">Получить аудит</a>
        </div>
        <div class="hero-card">
            <h3>Почему выбирают нас</h3>
            <ul>
                <li>Сертифицированные инженеры</li>
                <li>SLA и аварийный выезд до 2 часов</li>
                <li>Поставка оригинальных комплектующих</li>
                <li>Прозрачная отчётность и KPI</li>
            </ul>
        </div>
    </div>
</header>

<main>
    <section class="stats container">
        <?php foreach ($stats as $item): ?>
            <article>
                <strong><?= htmlspecialchars((string) $item['value']) ?></strong>
                <span><?= htmlspecialchars((string) $item['label']) ?></span>
            </article>
        <?php endforeach; ?>
    </section>

    <section id="services" class="container section">
        <h2>Ключевые услуги</h2>
        <div class="cards">
            <?php foreach ($services as $service): ?>
                <article class="card">
                    <h3><?= htmlspecialchars($service['icon'] . ' ' . $service['title']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="projects" class="container section">
        <h2>Последние проекты</h2>
        <div class="projects">
            <?php foreach ($projects as $project): ?>
                <article>
                    <h3><?= htmlspecialchars($project['name']) ?></h3>
                    <p><?= htmlspecialchars($project['summary']) ?></p>
                    <small><?= htmlspecialchars($project['city']) ?> • <?= htmlspecialchars($project['completed_at']) ?></small>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="container section testimonials">
        <h2>Отзывы клиентов</h2>
        <div class="cards">
            <?php foreach ($testimonials as $item): ?>
                <article class="card">
                    <p>“<?= htmlspecialchars($item['text']) ?>”</p>
                    <small><?= htmlspecialchars($item['author_name']) ?>, <?= htmlspecialchars($item['author_position']) ?></small>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact" class="container section contact">
        <h2>Оставьте заявку</h2>
        <?php if (!empty($flash)): ?>
            <div class="flash <?= $flash['type'] === 'success' ? 'ok' : 'err' ?>"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>
        <form method="post" action="/contact" class="form">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
            <label>Имя* <input name="name" required></label>
            <label>Телефон* <input name="phone" required></label>
            <label>Email <input name="email" type="email"></label>
            <label>Компания <input name="company"></label>
            <label>Комментарий* <textarea name="message" rows="4" required></textarea></label>
            <button class="btn" type="submit">Отправить</button>
        </form>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <p>© <?= date('Y') ?> ТОВ «ТеплоЭнергоСтрой». Промышленный холод и инженерные системы.</p>
    </div>
</footer>
