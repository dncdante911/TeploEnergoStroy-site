const fs = require('fs');
const path = require('path');
const Database = require('better-sqlite3');

const dataDir = path.join(__dirname, '..', 'data');
if (!fs.existsSync(dataDir)) {
  fs.mkdirSync(dataDir, { recursive: true });
}

const dbPath = path.join(dataDir, 'teploenergostroy.db');
const db = new Database(dbPath);

function initDb() {
  db.exec(`
    CREATE TABLE IF NOT EXISTS requests (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      request_type TEXT NOT NULL,
      company TEXT NOT NULL,
      contact_name TEXT NOT NULL,
      phone TEXT NOT NULL,
      email TEXT,
      equipment_type TEXT,
      message TEXT,
      created_at TEXT NOT NULL DEFAULT (datetime('now')),
      status TEXT NOT NULL DEFAULT 'new'
    );

    CREATE INDEX IF NOT EXISTS idx_requests_created_at ON requests(created_at DESC);
    CREATE INDEX IF NOT EXISTS idx_requests_status ON requests(status);
  `);
}

initDb();

const insertRequestStmt = db.prepare(`
  INSERT INTO requests (
    request_type,
    company,
    contact_name,
    phone,
    email,
    equipment_type,
    message
  ) VALUES (
    @requestType,
    @company,
    @contactName,
    @phone,
    @email,
    @equipmentType,
    @message
  )
`);

const listRequestsStmt = db.prepare(`
  SELECT id, request_type, company, contact_name, phone, email, equipment_type, message, created_at, status
  FROM requests
  ORDER BY id DESC
`);

function createRequest(payload) {
  const result = insertRequestStmt.run(payload);
  return result.lastInsertRowid;
}

function listRequests() {
  return listRequestsStmt.all();
}

module.exports = {
  initDb,
  createRequest,
  listRequests
};
