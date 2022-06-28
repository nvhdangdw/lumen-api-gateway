FROM php:7.2.34-fpm

# Install package
RUN apt update && \
    apt-get -y install sudo

# Install system dependencies
RUN apt-get update && apt-get install -y git vim

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Get nginx
COPY --from=nginx:alpine /usr/sbin/nginx /usr/sbin/nginx

# Get mysql
COPY --from=mysql:5.7 /usr/bin/mysql  /usr/bin/mysql

# Run scripts
COPY ./run.sh /tmp/
RUN chmod +x /tmp/run.sh

# Set working directory
WORKDIR /var/www

ENTRYPOINT ["/tmp/run.sh"]
