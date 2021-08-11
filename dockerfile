FROM php:7.3

RUN apt-get update -y && apt-get install -y openssl zip unzip git
# RUN curl -s https://getcomposer.org/composer.phar > /usr/local/bin/composer \
#     && chmod a+x /usr/local/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring pdo_mysql

WORKDIR /app

COPY . .

# RUN if [ -e vendor/composer ]; then composer install --optimize-autoloader --apcu-autoloader; fi
RUN composer install

CMD php artisan serve --host=0.0.0.0
EXPOSE 8000
