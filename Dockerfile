# Usa la imagen oficial de PHP 8.2 FPM
FROM php:8.2-fpm

# Instala dependencias del sistema + oniguruma (necesario para mbstring desde Debian 13)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \     # ← ESTA LÍNEA ES LA QUE FALTABA
    && rm -rf /var/lib/apt/lists/*

# Instala extensiones de PHP
RUN docker-php-ext-install \
    pdo pdo_mysql zip mbstring tokenizer bcmath

# Carpeta de trabajo
WORKDIR /var/www/html

# Copia el código
COPY . .

# Permisos Laravel (si es Laravel)
RUN chown -R www-data:www-data storage bootstrap/cache

# Si usas el servidor built-in de PHP (php -S) en vez de php-fpm + nginx
EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
# o si prefieres php-fpm puro (recomendado con nginx en Railway):
# CMD ["php-fpm"]