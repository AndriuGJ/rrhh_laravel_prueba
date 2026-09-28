#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# Uso de depuración: docker run <imagen> <comando>
if [ "$#" -gt 0 ]; then
    exec "$@"
fi

mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permite arrancar la imagen con SQLite (pruebas locales)
if [ "${DB_CONNECTION:-}" = "sqlite" ] && [ -n "${DB_DATABASE:-}" ] && [ "${DB_DATABASE}" != ":memory:" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
    chown sail:sail "$DB_DATABASE"
fi

chown -R sail:sail storage bootstrap/cache

if [ ! -e public/storage ]; then
    ln -sfn ../storage/app/public public/storage
fi
chown -h sail:sail public/storage 2>/dev/null || true

if [ "${OPTIMIZE_ON_BOOT:-true}" = "true" ]; then
    gosu sail php artisan config:cache
    gosu sail php artisan route:cache
fi

if [ "${MIGRATE_ON_BOOT:-true}" = "true" ]; then
    gosu sail php artisan migrate --force

    if [ "${SEED_ON_BOOT:-false}" = "true" ]; then
        gosu sail php artisan db:seed --force
    fi
fi

exec gosu sail php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
