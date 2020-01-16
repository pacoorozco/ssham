FROM php:7.2-fpm

MAINTAINER Paco Orozco <paco@pacoorozco.info>

RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
        zlib1g-dev

# Install the PHP needed extentions
RUN docker-php-ext-install \
    mbstring \
    pdo_mysql \
    zip

COPY ./docker/app/php/local.ini /usr/local/etc/php/conf.d

USER root

# Clean up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
