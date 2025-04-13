FROM php:8.4.5-fpm

RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www