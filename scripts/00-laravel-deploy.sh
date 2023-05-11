#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer global require "darkaonline/l5-swagger"
composer install --no-dev --working-dir=/var/www/html


echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force