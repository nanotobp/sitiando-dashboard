# Imagen oficial PHP 8.2 con FPM
FROM php:8.2-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libpq-dev libonig-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip gd

# Instalar Composer de forma oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear directorio de la aplicación
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Permisos para storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto de Laravel
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
