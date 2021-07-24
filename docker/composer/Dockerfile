FROM composer:2.0

# gmp
RUN apk add --update --no-cache gmp gmp-dev \
    && docker-php-ext-install gmp
