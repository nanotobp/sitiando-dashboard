#!/bin/sh
set -e

# Migraciones (seguras en Railway)
php artisan migrate --force || true

# Iniciar PHP-FPM
php-fpm -D

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
