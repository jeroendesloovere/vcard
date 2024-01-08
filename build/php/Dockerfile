FROM php:8.2-cli

ENV PHP_IDE_CONFIG="serverName=vcard"
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

ADD ./etc/php.ini /usr/local/etc/php/php.ini
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

WORKDIR /var/www/html
CMD ["tail", "-f", "/dev/stdout"]
