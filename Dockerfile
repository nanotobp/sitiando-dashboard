# ================================
#  PHP 8.4 + FPM + Todo lo necesario
# ================================
FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema
RUN apk add --no-cache \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libavif-dev \
        libpq-dev \
        oniguruma-dev \
        libxml2-dev \
        autoconf \
        g++ \
        make \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
        --with-avif \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        zip \
        gd \
        mbstring \
        exif \
        bcmath \
        intl \
        opcache \
    && docker-php-ext-enable opcache

# Instalar Composer desde la imagen oficial (más rápido y fiable)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuración recomendada de OPcache para producción
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=1'; \
        echo 'opcache.save_comments=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Configuración extra de PHP (opcional pero recomendado)
RUN { \
        echo 'memory_limit=512M'; \
        echo 'upload_max_filesize=100M'; \
        echo 'post_max_size=100M'; \
        echo 'max_execution_time=300'; \
    } > /usr/local/etc/php/conf.d/custom.ini

# Cambiar al directorio de trabajo
WORKDIR /var/www/html

# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar dependencias de PHP (ahora compatible con PHP 8.4+)
RUN composer install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts \
        --no-progress

# Copiar el resto del código de la aplicación
COPY . .

# Dar permisos correctos (opcional, ajusta según tu proyecto)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Exponer puerto (solo informativo, FPM no lo usa directamente)
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]