require('dotenv').config();
const path = require('path');
const express = require('express');
const { initDb, createRequest, listRequests } = require('./src/db');
const { services, projects, advantages } = require('./src/content');

initDb();

const app = express();
const port = Number(process.env.PORT || 3000);
const host = process.env.HOST || '0.0.0.0';
const publicUrl = process.env.PUBLIC_URL || `http://localhost:${port}`;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.use(express.urlencoded({ extended: false }));
app.use(express.json());
app.use('/static', express.static(path.join(__dirname, 'public')));

function normalizeText(value) {
  return String(value || '').trim();
}

function validateRequest(body) {
  const errors = [];

  const requestType = normalizeText(body.requestType);
  const company = normalizeText(body.company);
  const contactName = normalizeText(body.contactName);
  const phone = normalizeText(body.phone);
  const email = normalizeText(body.email);
  const equipmentType = normalizeText(body.equipmentType);
  const message = normalizeText(body.message);

  if (!requestType) errors.push('Укажите тип обращения.');
  if (!company) errors.push('Укажите название компании.');
  if (!contactName) errors.push('Укажите контактное лицо.');
  if (!phone) errors.push('Укажите телефон для связи.');
  if (email && !/^\S+@\S+\.\S+$/.test(email)) errors.push('Некорректный email.');
  if (message.length > 3000) errors.push('Сообщение слишком длинное (до 3000 символов).');

  return {
    errors,
    value: {
      requestType,
      company,
      contactName,
      phone,
      email,
      equipmentType,
      message
    }
  };
}

app.get('/', (req, res) => {
  res.render('index', {
    pageTitle: 'ТОВ ТеплоЭнергоСтрой — промышленный холод и сервис оборудования',
    services,
    projects,
    advantages,
    submitted: req.query.submitted === '1'
  });
});

app.post('/api/requests', (req, res) => {
  const { errors, value } = validateRequest(req.body);

  if (errors.length > 0) {
    return res.status(400).json({
      ok: false,
      errors
    });
  }

  const id = createRequest(value);
  return res.status(201).json({
    ok: true,
    id
  });
});

app.get('/health', (req, res) => {
  res.status(200).json({ ok: true });
});

app.post('/request', (req, res) => {
  const { errors, value } = validateRequest(req.body);

  if (errors.length > 0) {
    return res.status(400).render('error', {
      pageTitle: 'Ошибка отправки заявки',
      errors
    });
  }

  createRequest(value);
  return res.redirect('/?submitted=1#contact');
});

function adminAuth(req, res, next) {
  const user = process.env.ADMIN_USER || 'admin';
  const pass = process.env.ADMIN_PASS || 'admin123';

  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Basic ')) {
    res.setHeader('WWW-Authenticate', 'Basic realm="Admin area"');
    return res.status(401).send('Authentication required');
  }

  const encoded = authHeader.split(' ')[1];
  const decoded = Buffer.from(encoded, 'base64').toString('utf-8');
  const [inputUser, inputPass] = decoded.split(':');

  if (inputUser !== user || inputPass !== pass) {
    res.setHeader('WWW-Authenticate', 'Basic realm="Admin area"');
    return res.status(401).send('Invalid credentials');
  }

  return next();
}

app.get('/admin/requests', adminAuth, (req, res) => {
  const requests = listRequests();
  res.render('admin', {
    pageTitle: 'Админ-панель — заявки',
    requests
  });
});

app.use((req, res) => {
  res.status(404).render('error', {
    pageTitle: 'Страница не найдена',
    errors: ['Запрошенная страница не найдена.']
  });
});

app.listen(port, host, () => {
  console.log(`TeploEnergoStroy site started on ${publicUrl}`);
});
