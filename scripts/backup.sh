#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
DATABASE_FILE="${PROJECT_ROOT}/database/summer.db"
UPLOADS_DIR="${PROJECT_ROOT}/public/uploads"
BACKUP_DIR="${PROJECT_ROOT}/backup"
TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_FILE="${BACKUP_DIR}/sao-he-${TIMESTAMP}.tar.gz"

mkdir -p "${BACKUP_DIR}"

if [[ ! -f "${DATABASE_FILE}" ]]; then
  echo "Missing SQLite database: ${DATABASE_FILE}" >&2
  exit 1
fi

mkdir -p "${UPLOADS_DIR}"

tar -czf "${BACKUP_FILE}" \
  -C "${PROJECT_ROOT}" \
  "database/summer.db" \
  "public/uploads"

echo "${BACKUP_FILE}"
