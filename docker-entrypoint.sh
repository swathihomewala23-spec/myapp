#!/bin/sh
set -e

mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs

if [ "${DB_CONNECTION:-mysql}" = "sqlite" ]; then
    mkdir -p database
    touch "${DB_DATABASE:-database/database.sqlite}"
fi

chmod -R 775 bootstrap/cache storage database

php artisan config:clear --ansi
php artisan cache:clear --ansi
php artisan migrate --force

exec "$@"
