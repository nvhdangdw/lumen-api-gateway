#!/bin/sh

# API Gateway
cd /var/www/ApiGateway
if [ ! -d "vendor" ]; then
    composer install
fi
if [ ! -f ".env" ]; then
    cp .env.example .env
    # Artisan
    php artisan migrate:fresh --seed
    php artisan passport:install
fi

# Order service
cd /var/www/OrdersService
if [ ! -d "vendor" ]; then
    composer install
fi
if [ ! -f ".env" ]; then
    cp .env.example .env
    # Artisan
    php artisan migrate:fresh --seed
fi

# Product service
cd /var/www/ProductsApi
if [ ! -d "vendor" ]; then
    composer install
fi
if [ ! -f ".env" ]; then
    cp .env.example .env
    # Artisan
    php artisan migrate:fresh --seed
fi

# Run
php-fpm
