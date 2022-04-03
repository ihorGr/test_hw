FROM php:7.4-fpm

RUN rm /etc/apt/preferences.d/no-debian-php \
&& apt-get update -y \
&& apt-get install -y git zip unzip \
&& docker-php-ext-install pdo_mysql opcache

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY opcache.ini /usr/local/etc/php-fpm.d/opcache.ini

EXPOSE 9001

WORKDIR /var/www/test-task-hw

CMD ["php-fpm"]