FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    default-mysql-client \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN cp .env.example .env

RUN mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    database \
    && touch database/database.sqlite \
    && rm -f bootstrap/cache/*.php \
    && chmod -R 775 bootstrap/cache storage database

RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && php artisan key:generate \
    && php artisan package:discover --ansi \
    && php artisan migrate --force

RUN chmod +x docker-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["./docker-entrypoint.sh"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
