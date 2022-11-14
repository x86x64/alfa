FROM php:8.1-fpm

ENV DEBIAN_FRONTEND noninteractive

ARG APP_TIMEZONE

# Common stack and utils
RUN set -x \
    && ln -sf /usr/share/zoneinfo/${APP_TIMEZONE} /etc/localtime \
    && usermod -u 1000 www-data \
    && apt-get update \
    && apt-get install -y nano wget procps iputils-ping \
    && set +x

# Install php extensions
RUN set -x \
    && apt-get install -y curl git gnupg \
    && apt-get install -y libgmp-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gmp bcmath gd zip pdo pdo_mysql \
    && set +x

# Install Composer
RUN set -x \
    && cd /tmp \
    && wget -O composer-setup.php https://getcomposer.org/installer \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && set +x

# Clean
RUN set -x \
    && rm -rf /var/lib/apt/lists/* \
    && set +x

COPY docker/app/php.ini /usr/local/etc/php/php.ini
COPY docker/app/www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /project
