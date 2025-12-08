# =====================================
# PHP 8.4 + FPM (Debian Slim) — ESTABLE
# =====================================
FROM php:8.4-fpm

# -------------------------------------
# Instalar dependencias de sistema
# -------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libavif-dev \
    libpq-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
        --with-avif \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        zip \
        gd \
        mbstring \
        intl \
        bcmath \
        exif \
    && docker-php-ext-enable opcache

# -------------------------------------
# Composer
# -------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------------------
# Configuración de PHP
# -------------------------------------
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/php.ini \
 && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/php.ini \
 && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/php.ini

# -------------------------------------
# Código Laravel
# -------------------------------------
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY . .

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
