FROM php:7.4-apache

RUN a2enmod rewrite

RUN set -xe \
    && apt-get update \
    && apt-get install -y unzip libpng-dev libzip-dev libonig-dev libjpeg-dev libmcrypt-dev nano \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install gd mbstring mysqli zip pdo_mysql \
    && pecl install mcrypt-1.0.4\
    && docker-php-ext-enable mcrypt

RUN pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug

RUN curl -L https://getcomposer.org/download/2.1.0/composer.phar -o /usr/local/bin/composer && chmod a+x /usr/local/bin/composer

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/public

RUN chown -R www-data:www-data /var/www

EXPOSE 80

