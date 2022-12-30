FROM composer:2 as build_composer
WORKDIR /app
COPY . /app
RUN composer install --no-dev --ignore-platform-reqs --no-interaction

FROM node:16 as build_frontend
WORKDIR /app
COPY --from=build_composer /app /app
RUN npm install
RUN npm run build
RUN rm -rf node_modules

FROM --platform=linux/amd64 ubuntu:22.04

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# BASE LAYER
RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates lsb-release zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2
RUN mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && echo "keyserver hkp://keyserver.ubuntu.com:80" >> ~/.gnupg/dirmngr.conf \
        && gpg --recv-key 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c \
        && gpg --export 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c > /usr/share/keyrings/ppa_ondrej_php.gpg \
        && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
        && apt-get update \
    && apt-get install -y php8.1-cli php8.1-fpm nginx cron \
       php8.1-gd php8.1-curl \
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath mysql-client \
       php8.1-intl php8.1-readline \
       php8.1-msgpack php8.1-igbinary php8.1-redis \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# DOCKER-OUTSIDE-OF-DOCKER
RUN apt-get update
RUN apt-get install -y gnupg lsb-release sudo
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --batch --yes --dearmor -o /etc/apt/keyrings/docker.gpg
RUN echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
RUN apt-get update
RUN apt-get -y install docker-ce docker-ce-cli containerd.io docker-compose-plugin

# CLEANING LAYER
RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# NGINX
COPY ./docker/production/nginx_default.conf /etc/nginx/sites-available/default

# PHP
COPY ./docker/production/php-fpm.conf /etc/php/8.1/fpm/php-fpm.conf
COPY ./docker/production/www.conf /etc/php/8.1/fpm/pool.d/www.conf
COPY ./docker/production/php.ini /etc/php/8.1/cli/conf.d/99-php.ini

# SERVICES
COPY ./docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# USER
RUN groupadd --force -g 1000 forge
RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1000 forge
RUN usermod -aG sudo forge
RUN usermod -aG forge www-data
RUN chown -R forge:forge /var/www

COPY --from=build_frontend --chown=forge:forge /app /var/www/html

USER forge
# PHP DEPENDENCIES
RUN composer install

EXPOSE 80

USER root

CMD ["/usr/bin/supervisord"]
