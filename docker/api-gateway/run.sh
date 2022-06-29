#!/bin/bash

# API Gateway

declare -a arr=("ApiGateway" "OrdersService" "ProductsApi" "QRBenefitService")

for i in "${arr[@]}"
do
   cd /var/www/$i
   # Permission
    if [ "$(stat -c "%U" storage/logs/)" != "www-data" ] || [ "$(stat -c "%G" storage/logs/)" != "www-data" ]; then
        rm -rf sstorage/logs/*
        chown -R www-data:www-data storage/logs/
    fi

    if [ ! -d "vendor" ]; then
        composer install
    fi

    if [ ! -f ".env" ]; then
        cp .env.example .env
    fi

    if [ "$(php artisan migrate:status)" = "Migration table not found." ]; then
        php artisan passport:install
    fi
done

# Run
php-fpm
