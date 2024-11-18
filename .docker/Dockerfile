FROM composer:latest AS composer
FROM php:8.2-apache
RUN a2enmod rewrite
RUN a2enmod headers

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update --fix-missing && apt-get install -y \
    curl \
    libfreetype6-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    build-essential \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libssl-dev \
    libmemcached-dev \
    libz-dev \
    zlib1g-dev \
    libsqlite3-dev \
    zip \
    libxml2-dev \
    libcurl3-dev \
    libedit-dev \
    libpspell-dev \
    libldap2-dev \
    unixodbc-dev \
    libpq-dev \
    libxslt-dev \
    libicu-dev \
    net-tools

RUN echo "Installing PHP extensions"
RUN docker-php-ext-install iconv
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install soap
RUN docker-php-ext-install pspell
RUN docker-php-ext-install xsl
RUN docker-php-ext-install sockets
RUN docker-php-ext-install calendar
RUN docker-php-ext-install intl
RUN echo "Enabling PHP extensions"
RUN docker-php-ext-enable iconv
#RUN docker-php-ext-enable gd
RUN docker-php-ext-enable pdo
RUN docker-php-ext-enable pdo_mysql
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-enable pdo_pgsql
RUN docker-php-ext-enable pcntl
RUN docker-php-ext-enable zip
RUN docker-php-ext-enable bcmath
RUN docker-php-ext-enable simplexml
RUN docker-php-ext-enable soap
RUN docker-php-ext-enable pspell
RUN docker-php-ext-enable xsl
RUN docker-php-ext-enable sockets
RUN docker-php-ext-enable calendar
RUN docker-php-ext-enable intl
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
