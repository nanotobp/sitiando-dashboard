#!/bin/sh
set -e

echo "Starting PHP-FPM..."
php-fpm -y /usr/local/etc/php-fpm.conf -R &

# Esperar a que FPM esté listo
sleep 2

echo "Starting Nginx..."
exec nginx -g "daemon off;"
