# syntax=docker/dockerfile:1

FROM php:8.4-cli AS base
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    zlib1g-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) exif
RUN docker-php-ext-install -j$(nproc) bcmath
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY vite.config.js ./
COPY resources ./resources
RUN npm run build
RUN test -f public/build/manifest.json

FROM base AS app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --no-progress --prefer-dist

COPY . .
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache
RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi
COPY --from=frontend /app/public/build /var/www/html/public/build
RUN test -f public/build/manifest.json

RUN chown -R www-data:www-data storage bootstrap/cache public

USER www-data

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
