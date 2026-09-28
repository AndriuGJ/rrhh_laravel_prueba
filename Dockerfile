# syntax=docker/dockerfile:1
#
# Imagen de PRODUCCION para Render.
# Base: runtime oficial de Laravel Sail 8.4 (vendor/laravel/sail/runtimes/8.4)
# con los componentes solo-desarrollo retirados (xdebug, pcov, playwright, ffmpeg).
# El desarrollo local NO usa este archivo: usa `compose.yaml` + ./vendor/bin/sail.

########################################################################
# Etapa 1 — dependencias PHP (composer)
########################################################################
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
# ext-gd solo existe en la imagen final (php8.4-gd); el chequeo ocurre ahí.
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist \
        --ignore-platform-req=ext-gd

COPY . .
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction \
        --ignore-platform-req=ext-gd

########################################################################
# Etapa 2 — assets (Vite + Tailwind)
########################################################################
FROM node:24 AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

########################################################################
# Etapa 3 — runtime (Sail 8.4)
########################################################################
FROM ubuntu:24.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP=1000
ARG NODE_VERSION=24
ARG MYSQL_CLIENT="mysql-client"
ARG POSTGRES_VERSION=18

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV LANG=C.UTF-8

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN echo "Acquire::http::Pipeline-Depth 0;" > /etc/apt/apt.conf.d/99custom && \
    echo "Acquire::http::No-Cache true;" >> /etc/apt/apt.conf.d/99custom && \
    echo "Acquire::BrokenProxy    true;" >> /etc/apt/apt.conf.d/99custom

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python3 dnsutils librsvg2-bin nano \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y libgd3 php8.4-cli php8.4-dev \
       php8.4-pgsql php8.4-sqlite3 php8.4-gd \
       php8.4-curl php8.4-mongodb \
       php8.4-imap php8.4-mysql php8.4-mbstring \
       php8.4-xml php8.4-zip php8.4-bcmath php8.4-soap \
       php8.4-intl php8.4-readline \
       php8.4-ldap \
       php8.4-msgpack php8.4-igbinary php8.4-redis php8.4-swoole \
       php8.4-memcached php8.4-imagick \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt noble-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y $MYSQL_CLIENT \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.4

RUN userdel -r ubuntu || true
RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail
RUN git config --global --add safe.directory /var/www/html

COPY docker/php.ini /etc/php/8.4/cli/conf.d/99-sail.ini

WORKDIR /var/www/html

COPY --from=vendor --chown=sail:sail /app /var/www/html
COPY --from=assets --chown=sail:sail /app/public/build /var/www/html/public/build

RUN mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && ln -sfn ../storage/app/public public/storage \
    && chown -R sail:sail storage bootstrap/cache \
    && gosu sail php artisan package:discover --ansi

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 10000/tcp

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
