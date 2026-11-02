#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SOCKET="$ROOT/storage/mysql/mysql.sock"

if mysqladmin --socket="$SOCKET" -uroot ping >/dev/null 2>&1; then
    mysqladmin --socket="$SOCKET" -uroot shutdown
    echo "MySQL dihentikan."
else
    echo "MySQL tidak sedang berjalan."
fi
