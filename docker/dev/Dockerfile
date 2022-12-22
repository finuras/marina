FROM ubuntu:22.04

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# BASE LAYER
RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor \
       nano cron libcap2-bin libpng-dev python2 \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-get install -y php8.1-fpm php8.1-cli php8.1-dev \
       php8.1-sqlite3 php8.1-gd \
       php8.1-curl php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath \
       php8.1-intl php8.1-readline php8.1-pcov \
       php8.1-msgpack php8.1-igbinary \
       php8.1-redis \
       mysql-client nano  \
       nginx \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# DOCKER-OUTSIDE-OF-DOCKER
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

# USER
RUN groupadd --force -g 1000 sail
RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1000 sail
RUN echo 'sail ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

RUN chown -R sail:sail /var/www
COPY --chown=sail:sail . /var/www/html

# NGINX
COPY ./docker/dev/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/dev/nginx_default.conf /etc/nginx/sites-available/default

# PHP
COPY ./docker/dev/php-fpm.conf /etc/php/8.1/fpm/php-fpm.conf
COPY ./docker/dev/www.conf /etc/php/8.1/fpm/pool.d/www.conf
COPY ./docker/dev/php.ini /etc/php/8.1/cli/conf.d/99-php.ini

# SERVICES
COPY ./docker/dev/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# CRON
COPY ./docker/dev/add_to_cron /tmp/add_to_cron
RUN crontab /tmp/add_to_cron ; rm /tmp/add_to_cron

USER root

EXPOSE 80

CMD ["/usr/bin/supervisord"]
