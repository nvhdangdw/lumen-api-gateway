#!/bin/sh

# API Gateway
cd /var/www/ApiGateway
# Fix permission
chmod 775 -R stogare/*
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
# Fix permission
cd /var/www/OrdersService
chmod 775 -R stogare/*
if [ ! -d "vendor" ]; then
    composer install
fi
if [ ! -f ".env" ]; then
    cp .env.example .env
    # Artisan
    php artisan migrate:fresh --seed
fi

# Product service
# Fix permission
chmod 775 -R stogare/*
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
