FROM php:7.4.23-apache-buster
#VErsão mais recente do PHP 7 - 05-09-2021 https://www.php.net/downloads.php

ARG DEBIAN_FRONTEND=noninteractive

# Update (Atualizações do sistema)
RUN apt-get -y update --fix-missing && \
    apt-get upgrade -y && \
    apt-get --no-install-recommends install -y apt-utils && \
    rm -rf /var/lib/apt/lists/*


# Install (Instalar ferramentas úteis e bibliotecas )
RUN apt-get -y update && \
    apt-get -y --no-install-recommends install nano wget \
dialog \
libsqlite3-dev \
libsqlite3-0 && \
    apt-get -y --no-install-recommends install default-mysql-client \
zlib1g-dev \
libzip-dev \
libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils \
build-essential \
git \
curl \
libonig-dev && \ 
    apt-get -y --no-install-recommends install --fix-missing libcurl4 \
libcurl4-openssl-dev \
zip \
openssl && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# xdebug (Extensão de depuração)
RUN pecl install xdebug-2.8.0 && \
    docker-php-ext-enable xdebug

# redis (Cache de dados em memória, como memcached)
RUN pecl install redis-5.1.1 && \
    docker-php-ext-enable redis

# Instalações de extensões PHP7
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install pdo_sqlite && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install curl && \
    docker-php-ext-install tokenizer && \
    docker-php-ext-install json && \
    docker-php-ext-install zip && \
    docker-php-ext-install -j$(nproc) intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install gettext && \
    docker-php-ext-install exif

# Apache config (Configuração do Apache)
RUN a2enmod rewrite headers

# Cleanup (Limpeza do sistema para remover arquivos desnecessários)
RUN rm -rf /usr/src/*
