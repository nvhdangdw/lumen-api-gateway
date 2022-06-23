FROM php:7.2.34-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Get nginx
COPY --from=nginx:alpine /usr/sbin/nginx /usr/sbin/nginx

# Set working directory
WORKDIR /var/www
