FROM php:8.1.0-fpm-alpine

WORKDIR /var/www/app

COPY --chown=www-data:www-data docker/php/config/15-xdebug.ini $PHP_INI_DIR/conf.d
RUN apk add --no-cache bash git

RUN chown -R www-data:www-data .

RUN apk add --no-cache --virtual .pgsql-deps postgresql-dev; \
	docker-php-ext-install -j$(nproc) pdo_pgsql; \
	apk add --no-cache --virtual .pgsql-rundeps so:libpq.so.5; \
	apk del .pgsql-deps

ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-3.1.2; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER www-data

CMD ["php-fpm"]