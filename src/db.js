const fs = require('fs');
const path = require('path');

const dataDir = path.join(__dirname, '..', 'data');
const dbPath = path.join(dataDir, 'requests.json');

function ensureStorage() {
  if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir, { recursive: true });
  }

  if (!fs.existsSync(dbPath)) {
    fs.writeFileSync(dbPath, JSON.stringify({ lastId: 0, requests: [] }, null, 2));
  }
}

function readStorage() {
  ensureStorage();
  const raw = fs.readFileSync(dbPath, 'utf-8');
  return JSON.parse(raw);
}

function writeStorage(payload) {
  fs.writeFileSync(dbPath, JSON.stringify(payload, null, 2));
}

function initDb() {
  ensureStorage();
}

function createRequest(payload) {
  const storage = readStorage();
  const id = storage.lastId + 1;
  const now = new Date().toISOString();

  storage.lastId = id;
  storage.requests.unshift({
    id,
    request_type: payload.requestType,
    company: payload.company,
    contact_name: payload.contactName,
    phone: payload.phone,
    email: payload.email || '',
    equipment_type: payload.equipmentType || '',
    message: payload.message || '',
    created_at: now,
    status: 'new'
  });

  writeStorage(storage);
  return id;
}

function listRequests() {
  const storage = readStorage();
  return storage.requests;
}

module.exports = {
  initDb,
  createRequest,
  listRequests
};
