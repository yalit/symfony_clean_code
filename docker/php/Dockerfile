FROM php:8.1-apache

EXPOSE 80
WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update -yqq
RUN apt upgrade -yqq
RUN apt install -yqq  libjpeg-dev libpng-dev libbz2-dev libxslt-dev libsodium-dev libzip-dev libxslt-dev wget openssh-client zip firefox-esr \
    && docker-php-ext-install bz2 intl pcntl sodium zip xsl gd \
    && docker-php-ext-enable bz2 intl pcntl sodium zip xsl gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

RUN curl -sS https://get.symfony.com/cli/installer | bash
