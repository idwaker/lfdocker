FROM php:apache

# install extensions
RUN apt-get update && apt-get install -y zlib1g-dev libgearman-dev \
    && docker-php-ext-install zip pdo_mysql

RUN curl -L https://pecl.php.net/get/gearman-1.1.2.tgz >> /usr/src/php/ext/gearman.tgz && \
    tar -xf /usr/src/php/ext/gearman.tgz -C /usr/src/php/ext/ && \
    rm /usr/src/php/ext/gearman.tgz && \
    docker-php-ext-install gearman-1.1.2

# copy local php.ini file
COPY dev/php.ini /usr/local/etc/php/

# enable rewrite module
RUN a2enmod rewrite
