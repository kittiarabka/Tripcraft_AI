FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl ca-certificates gnupg libsqlite3-dev sqlite3 libonig-dev unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && docker-php-ext-install pdo pdo_sqlite mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN for i in 1 2 3 4 5; do composer install --no-dev --optimize-autoloader --no-interaction && break || sleep 3; done \
    && npm ci \
    && npm run build \
    && rm -rf node_modules \
    && mkdir -p database storage/framework/{cache,sessions,views} storage/logs storage/app/public bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 8080
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && php artisan storage:link || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
