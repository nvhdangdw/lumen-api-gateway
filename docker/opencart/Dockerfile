FROM php:7.1.33-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    vim \
    libzip-dev \
    libpng-dev \
    libjpeg-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql \
    mysqli \
    zip \
    gd

# Update configuration PHP extensions
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Get nginx
COPY --from=nginx:alpine /usr/sbin/nginx /usr/sbin/nginx

# Set working directory
WORKDIR /var/www
