FROM php:8.4-fpm-alpine

# Dependencias del sistema
RUN apk add --no-cache \
    nginx \
    git \
    curl \
    zip unzip \
    libzip-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev libavif-dev \
    libpq-dev \
    oniguruma-dev \
    libxml2-dev \
    autoconf g++ make

# Extensiones PHP
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-avif \
    && docker-php-ext-install -j$(nproc) \
       pdo_mysql pdo_pgsql zip gd mbstring exif bcmath intl opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar configs
COPY nginx.conf /etc/nginx/nginx.conf
COPY start-container.sh /start-container.sh
RUN chmod +x /start-container.sh

# Copiar composer
COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Copiar app completa
COPY . .

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["/start-container.sh"]
