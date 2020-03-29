# Derived from official mysql image (our base image)
FROM mysql:8.0
RUN apt-get update && apt-get install nano -y \ 
&& apt-get clean

# Condifuracion mysql con certificados
COPY /mysql-files/my.cnf /etc/mysql/conf.d/my.cnf
RUN mkdir /etc/mysql/certs

#Copiar certificados de produccion
COPY /mysql-files/*.pem /etc/mysql/certs/

# Add a database
ENV MYSQL_DATABASE udelvd
# Add the content of the sql-scripts/ directory to your image
# All scripts in docker-entrypoint-initdb.d/ are automatically
# executed during container startup
COPY /script_sql/ /docker-entrypoint-initdb.d/