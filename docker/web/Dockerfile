FROM php:7.0-apache

MAINTAINER Paco Orozco <paco@pacoorozco.info>

ENV APP_ROOT /var/www/app
ENV APP_USER ssham

ENV DEBIAN_FRONTEND noninteractive
ENV TERM linux

RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
    git \
    libmcrypt-dev \
    zlib1g-dev

RUN docker-php-ext-install \
    json \
    mbstring \
    mcrypt \
    pdo_mysql \
    zip

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN useradd -r -g www-data ${APP_USER}

RUN git clone https://github.com/pacoorozco/ssham.git ${APP_ROOT} \
    && cd ${APP_ROOT} \
    && composer install --no-plugins --no-scripts --prefer-dist --no-interaction

RUN cd ${APP_ROOT} \
    && cp .env.example .env \
    && sed -i "s/DB_HOST=localhost/DB_HOST=db/" .env \
    && sed -i "s/REDIS_HOST=localhost/REDIS_HOST=redis/" .env \
    && find ${APP_ROOT} -type d -exec chmod 0750 {} \; \
    && find ${APP_ROOT} -type f -exec chmod 0640 {} \; \
    && chown -R ${APP_USER}:www-data ${APP_ROOT} \
    && chown -R www-data:www-data ${APP_ROOT}/bootstrap/cache \
    && chown -R www-data:www-data ${APP_ROOT}/storage

WORKDIR ${APP_ROOT}

EXPOSE 80

COPY scripts/setup.sh /setup.sh
RUN ["chmod", "+x", "/setup.sh"]

