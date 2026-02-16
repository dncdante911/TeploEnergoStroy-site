# ТОВ «ТеплоЭнергоСтрой» — корпоративный сайт

Полноценный сайт для компании по обслуживанию промышленных холодильных систем и промышленного оборудования.

## Что реализовано

- Главная страница с блоками услуг, преимуществ, кейсов и контактной формой.
- Обработка заявок с валидацией на сервере.
- Хранение заявок в SQLite базе данных.
- API-эндпоинт для интеграции (`POST /api/requests`).
- Защищенная админ-страница заявок через Basic Auth (`/admin/requests`).

## Стек

- Node.js + Express + EJS
- SQLite (better-sqlite3)
- Нативный CSS

## Быстрый старт

```bash
npm install
cp .env.example .env
npm start
```

После запуска сайт доступен по адресу:

- `http://localhost:3000` — клиентская часть
- `http://localhost:3000/admin/requests` — список заявок (Basic Auth)

## Настройки

Укажите переменные в `.env`:

```env
PORT=3000
ADMIN_USER=admin
ADMIN_PASS=change_me
```

## API

### `POST /api/requests`

Тело запроса (JSON):

```json
{
  "requestType": "Плановое ТО",
  "company": "ООО Пример",
  "contactName": "Иван Петров",
  "phone": "+380670000000",
  "email": "service@example.com",
  "equipmentType": "Чиллер",
  "message": "Нужна диагностика"
}
```

Успех:

```json
{
  "ok": true,
  "id": 1
}
```

## Структура

- `server.js` — запуск приложения и маршруты.
- `src/db.js` — инициализация БД и доступ к данным.
- `src/content.js` — контент блоков главной страницы.
- `views/` — серверные шаблоны EJS.
- `public/css/styles.css` — стили сайта.
- `data/teploenergostroy.db` — SQLite база (создается автоматически).
