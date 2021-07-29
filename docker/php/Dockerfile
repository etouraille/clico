FROM php:7.4.11-fpm-buster

WORKDIR /application

ARG XDEBUG_VERSION=3.0.0beta1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        tzdata \
        coreutils \
        libgmp-dev \
        libgmp3-dev \
        zlib1g-dev \
        libssl-dev \
        libzip-dev \
        libonig-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip gmp mbstring curl \
    && mkdir -p /usr/src/php/ext/xdebug \
    && curl -fsSL https://xdebug.org/files/xdebug-${XDEBUG_VERSION}.tgz | tar xz -C /usr/src/php/ext/xdebug --strip 1 \
    && docker-php-ext-install xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/php.ini \
    && echo 'date.timezone="Europe/Paris"' >> /usr/local/etc/php/php.ini \
    && rm -rf /var/lib/apt/lists/*
