# ---- 1. Dependencias de PHP (sin paquetes de desarrollo) ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-interaction --ignore-platform-reqs

# ---- 2. Assets del frontend (Vite + Tailwind; necesita vendor/ para escanear las vistas de Jetstream) ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY --from=vendor /app/vendor ./vendor
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- 3. Imagen final: PHP + Apache ----
FROM php:8.3-apache
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    SESSION_DRIVER=file \
    CACHE_DRIVER=file \
    QUEUE_CONNECTION=sync \
    DEMO_MODE=false

RUN a2enmod rewrite headers \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY --from=vendor /app ./
COPY --from=assets /app/public/build ./public/build
COPY docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s CMD curl -fs "http://localhost:${PORT:-80}/up" || exit 1
CMD ["start"]
