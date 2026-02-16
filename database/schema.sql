-- ТОВ "ТеплоЭнергоСтрой" Database Schema
-- MariaDB 10.11.13

CREATE DATABASE IF NOT EXISTS teploenergo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE teploenergo;

-- Таблица администраторов
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица услуг
CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    icon VARCHAR(100),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_sort (sort_order),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица отзывов
CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(200) NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_position VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    content TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    INDEX idx_approved (is_approved),
    INDEX idx_featured (is_featured),
    INDEX idx_rating (rating),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица обратной связи
CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(200) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    is_processed TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    notes TEXT,
    INDEX idx_read (is_read),
    INDEX idx_processed (is_processed),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица страниц
CREATE TABLE IF NOT EXISTS pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    content LONGTEXT NOT NULL,
    meta_title VARCHAR(200),
    meta_description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица настроек сайта
CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Начальные данные: администратор по умолчанию
-- Пароль: admin123 (ОБЯЗАТЕЛЬНО ИЗМЕНИТЬ ПОСЛЕ ПЕРВОГО ВХОДА!)
INSERT INTO admins (username, email, password, full_name) VALUES
('admin', 'admin@teploenergo.ua', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Администратор');

-- Начальные услуги
INSERT INTO services (title, slug, description, content, icon, sort_order, is_active) VALUES
('Установка холодильного оборудования', 'installation', 'Профессиональная установка промышленного холодильного оборудования любой сложности', '<h3>Установка холодильного оборудования</h3><p>Наша компания предоставляет полный спектр услуг по установке промышленного холодильного оборудования для предприятий различных отраслей промышленности Украины.</p><p>Мы работаем с:</p><ul><li>Холодильными камерами</li><li>Морозильными системами</li><li>Чиллерами и градирнями</li><li>Системами кондиционирования</li></ul>', 'tools', 1, 1),
('Обслуживание и ремонт', 'maintenance', 'Плановое обслуживание и экстренный ремонт холодильного оборудования', '<h3>Обслуживание и ремонт</h3><p>Предоставляем услуги по техническому обслуживанию и ремонту холодильного оборудования с гарантией качества.</p><p>Услуги включают:</p><ul><li>Плановое ТО</li><li>Диагностика неисправностей</li><li>Ремонт компрессоров</li><li>Заправка хладагентом</li><li>Замена запчастей</li></ul>', 'wrench', 2, 1),
('Модернизация систем', 'modernization', 'Модернизация и оптимизация существующих холодильных систем', '<h3>Модернизация систем</h3><p>Модернизация устаревшего оборудования для повышения эффективности и снижения энергопотребления.</p><p>Преимущества:</p><ul><li>Снижение энергопотребления до 40%</li><li>Улучшение производительности</li><li>Соответствие современным стандартам</li><li>Продление срока службы оборудования</li></ul>', 'cog', 3, 1),
('Сервисное обслуживание', 'service', 'Комплексное сервисное обслуживание по договору', '<h3>Сервисное обслуживание</h3><p>Заключаем договоры на постоянное сервисное обслуживание с гарантией быстрого реагирования.</p><p>В рамках договора:</p><ul><li>Регулярные профилактические осмотры</li><li>Приоритетное обслуживание</li><li>Скидки на запчасти и ремонт</li><li>Выезд специалиста в течение 24 часов</li></ul>', 'clipboard', 4, 1);

-- Начальные настройки сайта
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'ТОВ "ТеплоЭнергоСтрой"', 'text', 'Название сайта'),
('site_phone', '+380 XX XXX XX XX', 'text', 'Основной телефон'),
('site_email', 'info@teploenergo.ua', 'text', 'Email для связи'),
('site_address', 'Украина, г. Киев', 'text', 'Адрес компании'),
('work_hours', 'Пн-Пт: 9:00 - 18:00', 'text', 'Часы работы'),
('about_company', 'ТОВ "ТеплоЭнергоСтрой" - ведущая компания по установке, обслуживанию и ремонту холодильного оборудования для промышленного сектора Украины.', 'textarea', 'О компании (краткое описание)'),
('meta_description', 'Профессиональная установка, обслуживание и ремонт холодильного оборудования для промышленности в Украине', 'text', 'Meta description для главной страницы'),
('reviews_moderation', '1', 'boolean', 'Включить модерацию отзывов');

-- Начальные страницы
INSERT INTO pages (slug, title, content, meta_title, meta_description, is_active) VALUES
('about', 'О компании', '<h2>О компании ТОВ "ТеплоЭнергоСтрой"</h2><p>Мы специализируемся на комплексных решениях в сфере холодильного оборудования для промышленного сектора.</p><p>С 2010 года наша команда профессионалов предоставляет качественные услуги по всей территории Украины.</p><h3>Наши преимущества:</h3><ul><li>Опытные специалисты с сертификацией</li><li>Работа с ведущими производителями оборудования</li><li>Гарантия на все виды работ</li><li>Круглосуточная техподдержка</li><li>Индивидуальный подход к каждому клиенту</li></ul>', 'О компании | ТеплоЭнергоСтрой', 'Информация о компании ТеплоЭнергоСтрой - профессиональные услуги по холодильному оборудованию', 1);
