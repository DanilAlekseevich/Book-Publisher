FROM php:8.1-fpm-alpine

RUN apk add --update \
    php8-pdo \
    libpq-dev \
	php8-pdo_pgsql \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php composer-setup.php \
	&& php -r "unlink('composer-setup.php');" \
	&& mv composer.phar /usr/bin/composer \
