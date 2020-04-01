FROM php:7.3-apache
ENV TZ="America/Santiago"
RUN apt-get clean \ 
&& apt-get update \ 
&& apt-get install git vim unzip libicu-dev g++ libzip-dev libmcrypt-dev libapache2-mod-security2 -y \ 
&& docker-php-source extract \ 
&& docker-php-ext-install zip pdo_mysql \ 
&& docker-php-source delete \ 
&& a2enmod rewrite \ 
&& service apache2 restart \ 
&& apt-get clean

# Copiar carpeta projecto
COPY . /var/www/
COPY ./public/ /var/www/html/

#Configuraciones apache
COPY ./api-files/apache2.conf /etc/apache2/apache2.conf
COPY ./api-files/000-udelvd.conf /etc/apache2/sites-available/000-udelvd.conf
COPY ./api-files/server.pem /etc/ssl/certs/server.pem
COPY ./api-files/server-key.pem /etc/ssl/certs/server-key.pem

RUN a2ensite 000-udelvd.conf \ 
&& a2enmod ssl \ 
&& service apache2 restart

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

EXPOSE 443