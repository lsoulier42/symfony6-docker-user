FROM php:8.3-fpm

RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions xdebug pgsql pdo_pgsql intl zip

RUN apt-get update && apt-get install git -y

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* \

WORKDIR /var/www/html