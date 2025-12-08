#!/bin/sh

# Arrancar PHP-FPM en background
php-fpm -D

# Iniciar NGINX en foreground (Railway necesita un proceso en primer plano)
nginx -g "daemon off;"
