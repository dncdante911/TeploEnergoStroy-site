#!/usr/bin/env bash
set -euo pipefail

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-tes_site}"
DB_USERNAME="${DB_USERNAME:-tes_user}"
DB_PASSWORD="${DB_PASSWORD:-tes_password}"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < app/Database/schema.sql
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < app/Database/seed.sql

echo "Database reset completed"
