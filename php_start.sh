#!/bin/bash

cd $MAIN_HOST/www

sudo -u www-data php composer.phar update
php-fpm
