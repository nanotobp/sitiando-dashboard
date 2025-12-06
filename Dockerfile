# -------------------------------
# Base PHP 8.4 FPM
# -------------------------------
FROM php:8.4-fpm

# -------------------------------
# Instalar dependencias del sistema
# -------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

# -------------------------------
# Configurar e instalar GD correctamente
# -------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# -------------------------------
# Instalar extensiones PHP
# -------------------------------
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    xml \
    gd \
    zip \
    opcache

# -------------------------------
# Instalar Composer
# -------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------------
# Setear directorio de trabajo
# -------------------------------
WORKDIR /var/www

# Copiar el código
COPY . .

# -------------------------------
# Instalar dependencias de Laravel
# -------------------------------
RUN composer install --no-dev --optimize-autoloader

# -------------------------------
# Generar APP_KEY si no existe
# -------------------------------
RUN php artisan key:generate || true

# -------------------------------
# DAR PERMISOS
# -------------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# -------------------------------
# Railway usa el puerto 8080
# -------------------------------
EXPOSE 8080

# -------------------------------
# Comando de inicio CORRECTO
# -------------------------------
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
