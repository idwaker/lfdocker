FROM php

# install extensions
RUN apt-get update && apt-get install -y libgearman-dev

RUN curl -L https://pecl.php.net/get/gearman-1.1.2.tgz >> /usr/src/php/ext/gearman.tgz && \
    tar -xf /usr/src/php/ext/gearman.tgz -C /usr/src/php/ext/ && \
    rm /usr/src/php/ext/gearman.tgz && \
    docker-php-ext-install gearman-1.1.2
