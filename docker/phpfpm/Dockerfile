FROM php:7.3-fpm

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y wget zip unzip libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql intl opcache \
    && pecl install redis && docker-php-ext-enable redis \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"

CMD ["php-fpm"]