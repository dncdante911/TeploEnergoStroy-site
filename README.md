# ТОВ «ТеплоЭнергоСтрой» — корпоративный сайт

Полноценный PHP 8.4+ / MariaDB / Redis проект с публичным сайтом и админ-панелью для входящих заявок.

## Стек
- PHP 8.4+ (PDO + ext-redis + ext-intl)
- MariaDB 10.11.13
- Redis 7
- Vanilla CSS/JS

## Важно про Composer и ошибку `Class "Normalizer" not found`
Если при запуске Composer вы видите ошибку вида:

```text
PHP Fatal error: Uncaught Error: Class "Normalizer" not found
```

это означает, что в системе не включено расширение `intl`.

### Debian/Ubuntu
```bash
sudo apt-get update
sudo apt-get install -y php-intl
sudo phpenmod intl
php -m | grep -i intl
```

### Docker (php:8.4-apache)
В контейнере нужно установить `intl` перед использованием Composer:

```bash
docker-php-ext-install intl
```

## Быстрый запуск
1. `cp .env.example .env`
2. Поднимите инфраструктуру: `docker compose up -d`
3. Инициализируйте БД: `./scripts/reset_db.sh`
4. Откройте `http://localhost:8080`

## Маршруты
- `/` — главная страница
- `/contact` (POST) — отправка заявки
- `/admin/login` — вход администратора
- `/admin` — дашборд заявок

## Безопасность
- CSRF-токены для форм
- Экранирование пользовательских данных в шаблонах
- Доступ в админ-панель по логину и bcrypt-хешу пароля

### Генерация ADMIN_PASSWORD_HASH
```bash
php -r "echo password_hash('MyStrongPass#2026', PASSWORD_BCRYPT), PHP_EOL;"
```
Поместите результат в `.env` как `ADMIN_PASSWORD_HASH`.
