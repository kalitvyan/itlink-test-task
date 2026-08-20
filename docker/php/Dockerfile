FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        zip \
    && docker-php-ext-enable opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

COPY . .

CMD ["php-fpm"]
