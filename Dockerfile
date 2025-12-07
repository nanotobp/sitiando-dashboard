# ============================
# STAGE 1 — Build Dependencies
# ============================
FROM php:8.2-fpm AS build

# Dependencias del sistema para desarrollo
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY composer.json composer.lock ./

# Instalar dependencias con cache y sin dev
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copiar el resto del proyecto
COPY . .

# Permisos correctos
RUN chown -R www-data:www-data storage bootstrap/cache

# Opcional: Optimizar Laravel si no usás closures en rutas
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache


# ============================
# STAGE 2 — Imagen FINAL
# ============================
FROM php:8.2-fpm AS production

# Instalar solo dependencias necesarias en runtime
RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev libpq-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

# Activar OPcache para producción
RUN docker-php-ext-enable opcache

# Configuración OPcache optimizada
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Copiar lo generado en el build
COPY --from=build /var/www/html /var/www/html

# Exponer puerto de Laravel
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
