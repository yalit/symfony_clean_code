FROM php:8.1-apache

ARG USER_ID
ARG GROUP_ID

EXPOSE 80
WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update -yqq
RUN apt upgrade -yqq
RUN apt install -yqq  libjpeg-dev libpng-dev libbz2-dev libxslt-dev libsodium-dev libzip-dev libxslt-dev wget openssh-client zip firefox-esr \
    && docker-php-ext-install bz2 intl pcntl sodium zip xsl gd pdo pdo_mysql \
    && docker-php-ext-enable bz2 intl pcntl sodium zip xsl gd pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN groupadd -f -g $GROUP_ID user
RUN useradd -u $USER_ID -g $GROUP_ID user
USER user

## Apache conf
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY apache.conf /etc/apache2/conf-available/z-app.conf
USER root
RUN a2enconf z-app

RUN a2enmod rewrite

RUN yes | pecl install xdebug

## Php conf
COPY php.ini /usr/local/etc/php/conf.d/app.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/

## Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
