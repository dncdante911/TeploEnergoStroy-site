#!/usr/bin/env bash
set -euo pipefail

FILES=(
  ".env.example"
  ".gitignore"
  "README.md"
  "app/Controllers/AdminController.php"
  "app/Controllers/HomeController.php"
  "app/Core/Cache.php"
  "app/Core/Database.php"
  "app/Core/Env.php"
  "composer.json"
  "deploy/apache/teploenergostroy.conf"
  "docker-compose.yml"
  "public/index.php"
)

echo "Checking files existence and merge markers..."
for f in "${FILES[@]}"; do
  if [[ ! -f "$f" ]]; then
    echo "MISSING: $f"
    exit 1
  fi

  if rg -n "^(<<<<<<<|=======|>>>>>>>)" "$f" >/dev/null; then
    echo "CONFLICT MARKERS FOUND: $f"
    rg -n "^(<<<<<<<|=======|>>>>>>>)" "$f"
    exit 2
  fi

done

echo "OK: files exist and do not contain merge markers."