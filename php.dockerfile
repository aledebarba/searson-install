FROM php:7.4-fpm-alpine

RUN touch /var/log/error_log

# copy config files and set workdir
ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN addgroup -g 1000 wp && adduser -G wp -g wp -s /bin/sh -D wp
RUN mkdir -p /var/www/html
RUN chown wp:wp /var/www/html
WORKDIR /var/www/html

# install mysql extension
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

#install wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

#install xdebug (but first autoconf, pkgconfig and gcc via build-base)
RUN apk add --no-cache --update autoconf 
RUN apk add --no-cache --update pkgconfig 
RUN apk add --no-cache --update build-base
RUN pecl install xdebug 
RUN docker-php-ext-enable xdebug 
