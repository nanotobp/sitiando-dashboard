# ----------------------------------------
# BASE: PHP 8.4 + FPM
# ----------------------------------------
FROM php:8.4-fpm

# ----------------------------------------
# Instalar dependencias del sistema
# ----------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# ----------------------------------------
# Extensiones de PHP necesarias para Laravel
# ----------------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    xml \
    gd \
    zip \
    opcache

# GD fix (muy común en Railway)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# ----------------------------------------
# Instalar Composer
# ----------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------------------
# Setear carpeta de trabajo
# ----------------------------------------
WORKDIR /var/www

# Copiar código
COPY . .

# ----------------------------------------
# Instalar dependencias de Laravel
# ----------------------------------------
RUN composer install --no-dev --optimize-autoloader

# Generar APP_KEY (no falla si ya existe)
RUN php artisan key:generate || true

# ----------------------------------------
# Permisos recomendados
# ----------------------------------------
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# ----------------------------------------
# Exponer puerto Laravel
# ----------------------------------------
EXPOSE 8000

# ----------------------------------------
# Comando de inicio
# ----------------------------------------
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
