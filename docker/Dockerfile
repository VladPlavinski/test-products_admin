FROM php:8.2.1-fpm

RUN apt-get update && apt-get upgrade -y && apt-get install -y libzip-dev zip unzip libpq-dev curl \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && docker-php-ext-enable pdo_pgsql;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;

RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y nodejs;

EXPOSE 9000
CMD ["php-fpm"]
