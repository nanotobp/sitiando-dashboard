# Usa PHP 8.4 FPM como base
FROM php:8.4-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    curl

# Instalar extensiones necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring tokenizer opcache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setear el working directory
WORKDIR /var/www

# Copiar archivos
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Generar APP_KEY automáticamente si no existe
RUN php artisan key:generate || true

# Exponer el puerto estándar de Laravel
EXPOSE 8000

# Comando de arranque
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
