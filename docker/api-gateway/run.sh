#!/bin/bash

# API Gateway
cd /var/www/
declare -a arr=("ApiGateway" "TransactionsService" "ProductsService" "QRBenefit-Monolithic")

for project in "${arr[@]}"
do
   cd $project
    if [ ! -d "$i" ]; then
        break
    fi

   # Update permission directory
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
        php artisan migrate:refresh --seed
    fi
done

# Run
php-fpm
