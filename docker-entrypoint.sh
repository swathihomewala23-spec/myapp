#!/bin/sh
set -e

mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    database

touch database/database.sqlite
chmod -R 775 bootstrap/cache storage database

php artisan config:clear --ansi
php artisan cache:clear --ansi
php artisan migrate --force

exec "$@"
