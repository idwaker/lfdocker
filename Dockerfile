FROM php:apache

# install extensions
RUN apt-get update && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip pdo_mysql

# copy local php.ini file
COPY dev/php.ini /usr/local/etc/php/

# enable rewrite module
RUN a2enmod rewrite
