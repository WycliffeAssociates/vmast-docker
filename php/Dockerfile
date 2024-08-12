FROM php:7.2-fpm-buster

# FROM php:8.3-fpm-bookworm


RUN apt-get update && apt-get install -y libzip-dev libicu-dev g++ git sudo
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install intl zip sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# config file for php-fpm
WORKDIR /usr/share/nginx/html
# Install deps


COPY ./htdocs/webapp/www/composer.* .
RUN composer install --no-dev --optimize-autoloader
# Rest of source code
# Set permissions for all files
COPY --chmod=0777 ./htdocs/webapp/www .
CMD php-fpm
# ENTRYPOINT ["/php_start.sh"]
