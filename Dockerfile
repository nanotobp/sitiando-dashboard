# ===============================
# PHP-FPM (8.2) + NGINX (Alpine)
# ===============================
FROM php:8.2-fpm-alpine AS php

# Dependencias
RUN apk add --no-cache \
    nginx \
    git \
    curl \
    zip unzip \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    oniguruma-dev \
    libxml2-dev

# Extensiones PHP
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql pdo_pgsql zip gd mbstring intl bcmath exif dom

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App directory
WORKDIR /var/www/html

# Composer first
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Copy full project
COPY . .

# Permisos Laravel
RUN chown -R nginx:nginx /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# NGINX CONFIG
COPY nginx.conf /etc/nginx/nginx.conf

# START SCRIPT
COPY start.sh /start.sh
RUN chmod +x /st
