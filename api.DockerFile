FROM php:7.3-apache
RUN apt-get clean
RUN apt-get update
RUN apt-get install git unzip libicu-dev g++ libzip-dev libmcrypt-dev -y 
RUN docker-php-source extract
RUN docker-php-ext-install zip pdo_mysql
RUN a2enmod rewrite
RUN service apache2 restart 
RUN apt-get clean

# Copiar carpeta projecto
COPY . /var/www/
COPY ./public/ /var/www/html/

# Archivo de configuracion PHP
#COPY /api-files/php-production.ini "$PHP_INI_DIR/php.ini"
COPY ./api-files/php-development.ini "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/

# Instalacion de composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"
RUN cd /var/www/ && composer install

EXPOSE 80