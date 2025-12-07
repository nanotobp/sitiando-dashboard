# ============================
# STAGE 1 — Build Dependencies
# ============================
FROM php:8.4-fpm AS build

RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache


# ============================
# STAGE 2 — Producción
# ============================
FROM php:8.4-fpm AS production

RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev libpq-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

WORKDIR /var/www/html

COPY --from=build /var/www/html /var/www/html

# Usa php-fpm (Railway lo espera así)
CMD ["php-fpm"]