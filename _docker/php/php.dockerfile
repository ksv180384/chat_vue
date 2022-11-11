FROM php:8.1.12-fpm

WORKDIR /var/www/chat-vue.local

RUN apt-get update && apt-get install -y nodejs npm zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql\
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/chat-vue.local

RUN chown -R www-data:www-data /var/www/chat-vue.local

RUN npm install

RUN npm run dev

EXPOSE 9000

ENTRYPOINT ["entrypoint"]

