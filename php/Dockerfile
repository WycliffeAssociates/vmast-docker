FROM php:8.3-fpm-bookworm


RUN apt-get update && apt-get install -y libzip-dev libicu-dev g++ git sudo
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install intl zip sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# config file for php-fpm
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf
WORKDIR /usr/share/nginx/html
# WORKDIR /var/www/html/webapp/www
# COPY ./php_start.sh /php_start.sh
# Install deps
COPY ./htdocs/webapp/www/composer.* .
RUN composer install --no-dev --optimize-autoloader
# Rest of source code. 0755, not 0777: php-fpm workers run as www-data and
# only need to read the application. Anything world-writable here turns a file
# write bug into arbitrary code execution.
COPY --chmod=0755 ./htdocs/webapp/www .

# The two trees the application writes to at run time. Both are backed by named
# volumes, and a volume seeds itself from the image on first use - so the mode
# and ownership set here are what production ends up with. setgid on the
# directories keeps the group on anything created later.
RUN set -eux; \
	writable="app/Storage app/Templates/Default/Assets/fonts/uploads"; \
	chown -R www-data:www-data $writable; \
	find $writable -type d -exec chmod 2775 {} +; \
	find $writable -type f -exec chmod 0664 {} +
CMD php-fpm
# ENTRYPOINT ["/php_start.sh"]
