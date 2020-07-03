# syntax=docker/dockerfile:1.0-experimental
FROM node:14.4.0-alpine3.12 as build

RUN mkdir /project

WORKDIR /project

ADD . /project

RUN yarn install --frozen-lockfile --production=true
RUN yarn prod

# alpine 3.11
FROM nginx:1.19-alpine as nginx

COPY --chown=nginx:nginx --from=build /project /var/www/html
COPY --chown=root:root --from=build /project/infrastructure/docker/frontend/nginx.conf /etc/nginx/nginx.conf

FROM php:7.4-fpm-alpine3.11 as php

RUN apk --no-cache add shadow
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data --from=build /project /var/www/html

RUN install-php-extensions decimal pdo_mysql

USER www-data
WORKDIR /var/www/html

RUN composer global require hirak/prestissimo
RUN composer install --no-interaction
