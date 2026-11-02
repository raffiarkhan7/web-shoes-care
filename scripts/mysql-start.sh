#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DATA="$ROOT/database/mysql-data"
RUN="$ROOT/storage/mysql"
PORT="${DB_PORT:-3307}"
SOCKET="$RUN/mysql.sock"
PID="$RUN/mysql.pid"
LOG="$RUN/mysql.log"

mkdir -p "$RUN"

if [ ! -d "$DATA/mysql" ]; then
    echo "Menginisialisasi data directory MySQL..."
    rm -rf "$DATA"
    mkdir -p "$(dirname "$DATA")"
    mysqld --no-defaults --initialize-insecure --datadir="$DATA" --log-error="$LOG"
fi

if mysqladmin --socket="$SOCKET" -uroot ping >/dev/null 2>&1; then
    echo "MySQL sudah berjalan di port $PORT."
else
    echo "Menjalankan MySQL di 127.0.0.1:$PORT..."
    mysqld --no-defaults \
        --datadir="$DATA" \
        --socket="$SOCKET" \
        --port="$PORT" \
        --bind-address=127.0.0.1 \
        --pid-file="$PID" \
        --log-error="$LOG" \
        --mysqlx=0 \
        --daemonize
    sleep 2
fi

mysql --socket="$SOCKET" -uroot -e "CREATE DATABASE IF NOT EXISTS shaos_care CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "MySQL siap. Database: shaos_care | Host: 127.0.0.1:$PORT | User: root | Password: (kosong)"
