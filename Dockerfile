# ============================
# STAGE 1 — Build Dependencies
# ============================
FROM php:8.2-fpm AS build

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar composer files
COPY composer.json composer.lock ./

# Instalar dependencias de producción
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copiar el resto del código
COPY . .

# Permisos correctos
RUN chown -R www-data:www-data storage bootstrap/cache


# ============================
# STAGE 2 — Imagen FINAL (producción)
# ============================
FROM php:8.2-fpm AS production

# Solo las extensiones necesarias en runtime
RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev libpq-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd \
    && docker-php-ext-enable opcache  # ← OPcache ya viene activado, solo lo aseguramos

# ¡ELIMINAMOS ESTA LÍNEA QUE CAUSABA EL ERROR!
# COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
# → No necesitas ese archivo. PHP 8.2+ ya trae una configuración excelente por defecto.

WORKDIR /var/www/html

# Copiar todo desde el stage de build
COPY --from=build /var/www/html /var/www/html

# Exponer puerto (Railway lo ignora, pero está bien tenerlo)
EXPOSE 8000

# ¡¡IMPORTANTE!! Cambia el comando final para producción en Railway
# Railway NO necesita que ejecutes "php artisan serve"
# Usa php-fpm directamente (es más rápido y estable)
CMD ["php-fpm"]