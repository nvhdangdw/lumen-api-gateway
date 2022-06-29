#base image php version 7.3
FROM php:7.3-fpm
LABEL MAINTAINER huy.nguyen@ptnglobalcorp.com

# Copy composer into container
COPY composer.lock composer.json /var/www/

# Set workspace/pwd
WORKDIR /var/www

# Install extension
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Add user for laravel
RUN groupadd -g 1000 mba
RUN useradd -u 1000 -ms /bin/bash -g mba mba
RUN gpasswd -a www-data mba

# Copy application folder
COPY . /var/www

# Copy existing permissions from folder to docker
COPY --chown=mba:mba . /var/www
RUN chown -R www-data:www-data /var/www

# Change own for permission
RUN chown www-data:mba ./storage/logs/*.log
RUN chown root:mba -h ./.env
RUN chown www-data:mba -R ./bootstrap
RUN chown www-data:mba -R storage

#install composer 
RUN composer install

#Install JWT
RUN php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

#Clear cache
RUN php artisan config:cache

#Generate key
RUN php artisan key:generate

#Clear config
RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan clear-compiled
RUN php artisan route:clear

# Set file/folder permissions
RUN chown -R 777 ./storage/framework
RUN chown -R 777 ./storage/logs
RUN chown -R 777 ./bootstrap
RUN chown -R 777 ./vendor/laravel/

# Autoloading
RUN composer dump-autoload

# change current user to mba
USER mba

#Start port 9000 to listen from web server-nginx
EXPOSE 9000
EXPOSE 8000

CMD ["php-fpm"]