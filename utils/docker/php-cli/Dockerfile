FROM php:cli

RUN apt-get update && apt-get install -y \
         libfreetype6-dev \
         libjpeg62-turbo-dev \
         libmcrypt-dev \
         libpng12-dev \
         zlib1g-dev \
         libicu-dev \
         g++\
         libxrender1\
         libfontconfig1\
         libxext6\
     && docker-php-ext-install -j$(nproc) iconv mcrypt pdo pdo_mysql mbstring zip\
     && docker-php-ext-configure intl \
     && docker-php-ext-install -j$(nproc) intl \
     && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
     && docker-php-ext-install -j$(nproc) gd \
     && pecl install xdebug
#Composer

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer --version