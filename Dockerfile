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
    && chmod -R 775 bootstrap/cache storage

RUN php artisan key:generate

RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && php artisan package:discover --ansi

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
