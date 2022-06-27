#!/bin/sh

# API Gateway
cd /var/www/ApiGateway
# Permission
if [ "$(stat -c "%U" storage/logs/)" != "www-data" ] || [ "$(stat -c "%G" storage/logs/)" != "www-data" ]; then
    chown -R www-data:www-data storage/logs/
fi

if [ ! -d "vendor" ]; then
    composer install
fi

if [ "$(php artisan migrate:status)" = "Migration table not found." ]; then
    php artisan migrate:fresh --seed
    php artisan passport:install
fi

# Order service
cd /var/www/OrdersService
# Permission
if [ "$(stat -c "%U" storage/logs/)" != "www-data" ] || [ "$(stat -c "%G" storage/logs/)" != "www-data" ]; then
    chown -R www-data:www-data storage/logs/
fi

if [ ! -d "vendor" ]; then
    composer install
fi

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

if [ "$(php artisan migrate:status)" = "Migration table not found." ]; then
    php artisan migrate:fresh --seed
fi


# Product service
cd /var/www/ProductsApi

# Permission
if [ "$(stat -c "%U" storage/logs/)" != "www-data" ] || [ "$(stat -c "%G" storage/logs/)" != "www-data" ]; then
    chown -R www-data:www-data storage/logs/
fi

if [ ! -d "vendor" ]; then
    composer install
fi

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

if [ "$(php artisan migrate:status)" = "Migration table not found." ]; then
    php artisan migrate:fresh --seed
fi

# Run
php-fpm
