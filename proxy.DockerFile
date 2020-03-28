FROM httpd:latest

COPY ./proxy-files/httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./proxy-files/httpd-vhosts.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf
COPY ./proxy-files/ssl/apache-prod.crt /usr/local/apache2/conf/server.crt
COPY ./proxy-files/ssl/apache-prod.key /usr/local/apache2/conf/server.key