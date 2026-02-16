-- Виправлення всіх таблиць для бази даних tes
-- MariaDB 10.11.13

USE tes;

-- 1. Таблиця адміністраторів
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

-- Додаємо адміністратора (якщо ще немає)
-- Пароль: admin123
INSERT IGNORE INTO admins (username, email, password, full_name) VALUES
('admin', 'admin@teploenergo.ua', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Адміністратор');

-- 2. Таблиця зворотного зв'язку (contacts)
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

-- 3. Таблиця сторінок
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

-- Додаємо сторінку "Про нас" (якщо немає)
INSERT IGNORE INTO pages (slug, title, content, meta_title, meta_description, is_active) VALUES
('about', 'Про компанію', '<h2>Про компанію ТОВ "ТеплоЭнергоСтрой"</h2><p>Ми спеціалізуємось на комплексних рішеннях в сфері холодильного обладнання для промислового сектору.</p><p>З 2010 року наша команда професіоналів надає якісні послуги по всій території України.</p><h3>Наші переваги:</h3><ul><li>Досвідчені спеціалісти з сертифікацією</li><li>Робота з провідними виробниками обладнання</li><li>Гарантія на всі види робіт</li><li>Цілодобова техпідтримка</li><li>Індивідуальний підхід до кожного клієнта</li></ul>', 'Про компанію | ТеплоЭнергоСтрой', 'Інформація про компанію ТеплоЭнергоСтрой - професійні послуги по холодильному обладнанню', 1);

-- 4. Таблиця налаштувань
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

-- Додаємо ВСІ налаштування
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
-- Основні налаштування сайту
('site_name', 'ТОВ "ТеплоЭнергоСтрой"', 'text', '🏢 Назва сайту'),
('site_phone', '+380 XX XXX XX XX', 'text', '🏢 Основний телефон'),
('site_email', 'info@teploenergo.ua', 'text', '🏢 Email для зв\'язку'),
('site_address', 'Україна, м. Київ', 'text', '🏢 Адреса компанії'),
('work_hours', 'Пн-Пт: 9:00 - 18:00', 'text', '🏢 Години роботи'),
('about_company', 'ТОВ "ТеплоЭнергоСтрой" - провідна компанія по встановленню, обслуговуванню та ремонту холодильного обладнання для промислового сектору України.', 'textarea', '🏢 Про компанію (короткий опис)'),
('meta_description', 'Професійна установка, обслуговування та ремонт холодильного обладнання для промисловості в Україні', 'text', '🏢 Meta description для головної сторінки'),
('reviews_moderation', '1', 'boolean', '🏢 Увімкнути модерацію відгуків'),

-- Налаштування Hero секції
('hero_title', 'Професійні рішення для холодильного обладнання', 'text', '🎯 Заголовок Hero секції'),
('hero_description', 'Установка, обслуговування та ремонт промислового холодильного обладнання по всій Україні', 'textarea', '🎯 Опис Hero секції'),
('hero_button_text', 'Замовити консультацію', 'text', '🎯 Текст кнопки Hero секції'),
('hero_button_link', '/contact', 'text', '🎯 Посилання кнопки Hero секції'),

-- Налаштування Logo
('logo_tagline', 'Холодильне обладнання для промисловості', 'text', '🏷️ Підзаголовок логотипу'),

-- Налаштування секції Послуги (на головній)
('services_section_title', 'Наші послуги', 'text', '📋 Заголовок секції послуг'),
('services_section_subtitle', 'Комплексні рішення для вашого бізнесу', 'text', '📋 Підзаголовок секції послуг'),

-- Налаштування секції Відгуки
('reviews_section_title', 'Відгуки наших клієнтів', 'text', '⭐ Заголовок секції відгуків'),
('reviews_section_subtitle', 'Що кажуть про нас', 'text', '⭐ Підзаголовок секції відгуків'),
('reviews_button_text', 'Всі відгуки', 'text', '⭐ Текст кнопки "Всі відгуки"'),

-- Налаштування секції "Чому обирають нас"
('advantages_section_title', 'Чому обирають нас', 'text', '✨ Заголовок секції переваг'),
('advantages_section_subtitle', 'Переваги роботи з нами', 'text', '✨ Підзаголовок секції переваг'),

-- Перевага 1
('advantage_1_icon', '✓', 'text', '✨ Іконка переваги 1'),
('advantage_1_title', 'Досвід', 'text', '✨ Заголовок переваги 1'),
('advantage_1_description', 'Більше 15 років успішної роботи на ринку України', 'textarea', '✨ Опис переваги 1'),

-- Перевага 2
('advantage_2_icon', '⚡', 'text', '✨ Іконка переваги 2'),
('advantage_2_title', 'Швидкість', 'text', '✨ Заголовок переваги 2'),
('advantage_2_description', 'Виїзд фахівця протягом 24 годин після звернення', 'textarea', '✨ Опис переваги 2'),

-- Перевага 3
('advantage_3_icon', '🛡️', 'text', '✨ Іконка переваги 3'),
('advantage_3_title', 'Гарантія', 'text', '✨ Заголовок переваги 3'),
('advantage_3_description', 'Надаємо гарантію на всі види виконаних робіт', 'textarea', '✨ Опис переваги 3'),

-- Перевага 4
('advantage_4_icon', '💼', 'text', '✨ Іконка переваги 4'),
('advantage_4_title', 'Професіоналізм', 'text', '✨ Заголовок переваги 4'),
('advantage_4_description', 'Сертифіковані фахівці з великим досвідом', 'textarea', '✨ Опис переваги 4'),

-- Налаштування Footer
('footer_about_title', 'Про компанію', 'text', '📄 Заголовок блоку "Про компанію" в футері'),
('footer_services_title', 'Послуги', 'text', '📄 Заголовок блоку "Послуги" в футері'),
('footer_contacts_title', 'Контакти', 'text', '📄 Заголовок блоку "Контакти" в футері'),
('footer_copyright', 'Всі права захищено', 'text', '📄 Текст копірайту в футері')

ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);
