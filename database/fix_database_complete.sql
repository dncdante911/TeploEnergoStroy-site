-- ПОВНЕ ВИПРАВЛЕННЯ БАЗИ ДАНИХ
-- Для бази tes на MariaDB 10.11.13
-- Цей скрипт додає всі відсутні колонки та таблиці

USE tes;

-- ============================================
-- 1. ВИПРАВЛЕННЯ ТАБЛИЦІ SERVICES
-- ============================================

-- Додаємо відсутні колонки (ігноруємо помилки якщо вже є)
SET @query1 = 'ALTER TABLE services ADD COLUMN slug VARCHAR(200) NOT NULL UNIQUE AFTER title';
SET @query2 = 'ALTER TABLE services ADD COLUMN content LONGTEXT NOT NULL AFTER description';
SET @query3 = 'ALTER TABLE services ADD COLUMN image VARCHAR(255) AFTER content';
SET @query4 = 'ALTER TABLE services ADD COLUMN icon VARCHAR(100) AFTER image';
SET @query5 = 'ALTER TABLE services ADD COLUMN sort_order INT DEFAULT 0 AFTER icon';
SET @query6 = 'ALTER TABLE services ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER sort_order';
SET @query7 = 'ALTER TABLE services ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER is_active';
SET @query8 = 'ALTER TABLE services ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at';

-- Виконуємо кожен ALTER окремо
ALTER TABLE services ADD COLUMN IF NOT EXISTS slug VARCHAR(200);
ALTER TABLE services ADD COLUMN IF NOT EXISTS content LONGTEXT;
ALTER TABLE services ADD COLUMN IF NOT EXISTS image VARCHAR(255);
ALTER TABLE services ADD COLUMN IF NOT EXISTS icon VARCHAR(100);
ALTER TABLE services ADD COLUMN IF NOT EXISTS sort_order INT DEFAULT 0;
ALTER TABLE services ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1;
ALTER TABLE services ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE services ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Генеруємо slug для існуючих записів якщо немає
UPDATE services SET slug = LOWER(REPLACE(title, ' ', '-')) WHERE slug IS NULL OR slug = '';
UPDATE services SET content = description WHERE content IS NULL OR content = '';
UPDATE services SET is_active = 1 WHERE is_active IS NULL;
UPDATE services SET sort_order = id WHERE sort_order IS NULL OR sort_order = 0;

-- ============================================
-- 2. ВИПРАВЛЕННЯ ТАБЛИЦІ REVIEWS
-- ============================================

ALTER TABLE reviews ADD COLUMN IF NOT EXISTS author_position VARCHAR(100) AFTER author_name;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS email VARCHAR(100) AFTER author_position;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER email;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS rating TINYINT UNSIGNED NOT NULL DEFAULT 5 AFTER phone;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS content TEXT NOT NULL AFTER rating;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS is_approved TINYINT(1) DEFAULT 0 AFTER content;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS is_featured TINYINT(1) DEFAULT 0 AFTER is_approved;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER is_featured;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL AFTER updated_at;

-- Оновлюємо існуючі записи
UPDATE reviews SET is_approved = 1 WHERE is_approved IS NULL;
UPDATE reviews SET is_featured = 0 WHERE is_featured IS NULL;
UPDATE reviews SET rating = 5 WHERE rating IS NULL OR rating = 0;

-- ============================================
-- 3. СТВОРЕННЯ ТАБЛИЦІ ADMINS (якщо немає)
-- ============================================

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

-- Додаємо адміністратора (пароль: admin123)
INSERT IGNORE INTO admins (username, email, password, full_name) VALUES
('admin', 'admin@teploenergo.ua', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Адміністратор');

-- ============================================
-- 4. СТВОРЕННЯ ТАБЛИЦІ CONTACTS (якщо немає)
-- ============================================

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

-- ============================================
-- 5. СТВОРЕННЯ ТАБЛИЦІ PAGES (якщо немає)
-- ============================================

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

-- Додаємо сторінку "Про нас"
INSERT IGNORE INTO pages (slug, title, content, meta_title, meta_description, is_active) VALUES
('about', 'Про компанію', '<h2>Про компанію ТОВ "ТеплоЭнергоСтрой"</h2><p>Ми спеціалізуємось на комплексних рішеннях в сфері холодильного обладнання.</p><h3>Наші переваги:</h3><ul><li>Досвідчені спеціалісти</li><li>Гарантія на роботи</li><li>Індивідуальний підхід</li></ul>', 'Про компанію', 'Інформація про компанію ТеплоЭнергоСтрой', 1);

-- ============================================
-- 6. СТВОРЕННЯ ТАБЛИЦІ SETTINGS (якщо немає)
-- ============================================

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

-- Додаємо/оновлюємо ВСІ налаштування
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'ТОВ "ТеплоЭнергоСтрой"', 'text', '🏢 Назва сайту'),
('site_phone', '+380 XX XXX XX XX', 'text', '🏢 Основний телефон'),
('site_email', 'info@teploenergo.ua', 'text', '🏢 Email для зв\'язку'),
('site_address', 'Україна, м. Київ', 'text', '🏢 Адреса компанії'),
('work_hours', 'Пн-Пт: 9:00 - 18:00', 'text', '🏢 Години роботи'),
('about_company', 'ТОВ "ТеплоЭнергоСтрой" - провідна компанія по встановленню, обслуговуванню та ремонту холодильного обладнання для промислового сектору України.', 'textarea', '🏢 Про компанію'),
('meta_description', 'Професійна установка, обслуговування та ремонт холодильного обладнання в Україні', 'text', '🏢 Meta description'),
('reviews_moderation', '1', 'boolean', '🏢 Модерація відгуків'),
('hero_title', 'Професійні рішення для холодильного обладнання', 'text', '🎯 Заголовок Hero'),
('hero_description', 'Установка, обслуговування та ремонт промислового холодильного обладнання по всій Україні', 'textarea', '🎯 Опис Hero'),
('hero_button_text', 'Замовити консультацію', 'text', '🎯 Текст кнопки'),
('hero_button_link', '/contact', 'text', '🎯 Посилання кнопки'),
('logo_tagline', 'Холодильне обладнання для промисловості', 'text', '🏷️ Підзаголовок логотипу'),
('services_section_title', 'Наші послуги', 'text', '📋 Заголовок секції'),
('services_section_subtitle', 'Комплексні рішення для вашого бізнесу', 'text', '📋 Підзаголовок секції'),
('reviews_section_title', 'Відгуки наших клієнтів', 'text', '⭐ Заголовок секції'),
('reviews_section_subtitle', 'Що кажуть про нас', 'text', '⭐ Підзаголовок секції'),
('reviews_button_text', 'Всі відгуки', 'text', '⭐ Текст кнопки'),
('advantages_section_title', 'Чому обирають нас', 'text', '✨ Заголовок секції'),
('advantages_section_subtitle', 'Переваги роботи з нами', 'text', '✨ Підзаголовок секції'),
('advantage_1_icon', '✓', 'text', '✨ Іконка 1'),
('advantage_1_title', 'Досвід', 'text', '✨ Заголовок 1'),
('advantage_1_description', 'Більше 15 років успішної роботи на ринку України', 'textarea', '✨ Опис 1'),
('advantage_2_icon', '⚡', 'text', '✨ Іконка 2'),
('advantage_2_title', 'Швидкість', 'text', '✨ Заголовок 2'),
('advantage_2_description', 'Виїзд фахівця протягом 24 годин після звернення', 'textarea', '✨ Опис 2'),
('advantage_3_icon', '🛡️', 'text', '✨ Іконка 3'),
('advantage_3_title', 'Гарантія', 'text', '✨ Заголовок 3'),
('advantage_3_description', 'Надаємо гарантію на всі види виконаних робіт', 'textarea', '✨ Опис 3'),
('advantage_4_icon', '💼', 'text', '✨ Іконка 4'),
('advantage_4_title', 'Професіоналізм', 'text', '✨ Заголовок 4'),
('advantage_4_description', 'Сертифіковані фахівці з великим досвідом', 'textarea', '✨ Опис 4'),
('footer_about_title', 'Про компанію', 'text', '📄 Заголовок блоку 1'),
('footer_services_title', 'Послуги', 'text', '📄 Заголовок блоку 2'),
('footer_contacts_title', 'Контакти', 'text', '📄 Заголовок блоку 3'),
('footer_copyright', 'Всі права захищено', 'text', '📄 Копірайт')
ON DUPLICATE KEY UPDATE
    setting_value=VALUES(setting_value),
    description=VALUES(description),
    setting_type=VALUES(setting_type);
