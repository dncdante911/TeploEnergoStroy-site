# ТОВ «ТеплоЭнергоСтрой» — корпоративный сайт

Полноценный сайт для компании по обслуживанию промышленных холодильных систем и промышленного оборудования.

## Что реализовано

- Главная страница с блоками услуг, преимуществ, кейсов и контактной формой.
- Обработка заявок с валидацией на сервере.
- Хранение заявок в локальном JSON-файле (`data/requests.json`).
- API-эндпоинт для интеграции (`POST /api/requests`).
- Защищенная админ-страница заявок через Basic Auth (`/admin/requests`).

## Стек

- Node.js + Express + EJS
- Node.js fs (JSON storage)
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
HOST=0.0.0.0
PUBLIC_URL=https://example.com
ADMIN_USER=admin
ADMIN_PASS=change_me
```

`HOST=0.0.0.0` позволяет принимать подключения снаружи контейнера/сервера (по домену), а `PUBLIC_URL` используется только для корректного URL в логах запуска.

## API

### `GET /health`

Проверка, что приложение запущено и принимает HTTP-запросы:

```json
{
  "ok": true
}
```

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
- `data/requests.json` — файл хранения заявок (создается автоматически).


## Деплой через Apache (исправление 403 Forbidden)

Если при открытии домена вы видите страницу вида `Apache/2.4.x ... 403 Forbidden`, это означает, что запрос до Node.js не доходит: Apache отдает ответ сам (обычно из-за неверного `DocumentRoot`/прав доступа или отсутствия reverse proxy).

Для этого проекта Apache должен проксировать трафик в Node.js:

1. Запустите приложение на сервере (например, через `pm2`):

```bash
PORT=3000 HOST=127.0.0.1 PUBLIC_URL=https://radio-active.top npm start
```

2. Включите модули Apache:

```bash
sudo a2enmod proxy proxy_http headers
```

3. Подключите готовый vhost из репозитория `deploy/apache/teploenergostroy.conf` (замените домен при необходимости), активируйте сайт и перезапустите Apache.

Пример:

```bash
sudo cp deploy/apache/teploenergostroy.conf /etc/apache2/sites-available/teploenergostroy.conf
sudo a2ensite teploenergostroy.conf
sudo apache2ctl configtest
sudo systemctl reload apache2
```

После этого домен должен открывать Express-приложение, а не страницу Apache 403.


## Диагностика 502 Bad Gateway (nginx)

Если после переключения обработчика на Node.js появляется `502 Bad Gateway (nginx)`, это обычно значит, что процесс Node не смог стартовать или упал сразу после запуска.

Проверьте на сервере:

```bash
node -v
npm install
npm start
```

И отдельно health-check:

```bash
curl -i http://127.0.0.1:3000/health
```

Ожидаемый ответ: `HTTP/1.1 200` и `{"ok":true}`.

В этой версии проекта убрана зависимость от нативного `better-sqlite3` (частая причина падения на shared-хостингах), поэтому приложение не требует компиляции нативных модулей при установке.
