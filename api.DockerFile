FROM php:7.3-apache
RUN apt-get clean \ 
&& apt-get update \ 
&& apt-get install git unzip libicu-dev g++ libzip-dev libmcrypt-dev libapache2-mod-security2 -y \ 
&& docker-php-source extract \ 
&& docker-php-ext-install zip pdo_mysql \ 
&& docker-php-source delete \ 
&& a2enmod rewrite \ 
&& service apache2 restart \ 
&& apt-get clean

# Copiar carpeta projecto
COPY . /var/www/
COPY ./public/ /var/www/html/
COPY ./api-files/apache2.conf /etc/apache2/apache2.conf

# Archivo de configuracion PHP
COPY /api-files/php-production.ini "$PHP_INI_DIR/php.ini"
#COPY ./api-files/php-development.ini "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/

# Instalacion de composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"
RUN cd /var/www/ && composer install

EXPOSE 80