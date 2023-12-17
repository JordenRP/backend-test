FROM php:8.1.0-fpm-alpine

WORKDIR /var/www/app

COPY --chown=www-data:www-data docker/php/config/15-xdebug.ini $PHP_INI_DIR/conf.d
COPY . /var/www/app
RUN apk add --no-cache bash git

RUN chown -R www-data:www-data .

RUN apk add --no-cache --virtual .pgsql-deps postgresql-dev; \
	docker-php-ext-install -j$(nproc) pdo_pgsql; \
	apk add --no-cache --virtual .pgsql-rundeps so:libpq.so.5; \
	apk del .pgsql-deps

ARG PUID=1000
ARG PGID=1000
RUN apk add --no-cache --virtual .mysql-deps mariadb-dev; \
    docker-php-ext-install -j$(nproc) pdo_mysql; \
    apk add --no-cache --virtual .mysql-rundeps so:libmysqlclient.so.21; \
    apk del .mysql-deps \

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-3.1.2; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer i \
        --prefer-dist \
        --no-scripts \
        --optimize-autoloader -o \
        --ignore-platform-reqs \
        --no-interaction

USER www-data

CMD ["php-fpm"]