# Udelvd-server <br/> [![Build Status](https://travis-ci.com/figonzal1/Udelvd-server.svg?branch=udelvd.tk)](https://travis-ci.com/figonzal1/Udelvd-server) [![CodeFactor](https://www.codefactor.io/repository/github/figonzal1/udelvd-server/badge)](https://www.codefactor.io/repository/github/figonzal1/udelvd-server) ![GitHub top language](https://img.shields.io/github/languages/top/figonzal1/Udelvd-server) ![GitHub repo size](https://img.shields.io/github/repo-size/figonzal1/Udelvd-server) ![GitHub last commit](https://img.shields.io/github/last-commit/figonzal1/Udelvd-server) ![Docker Image Version (latest by date)](https://img.shields.io/docker/v/figonzal/udelvd-api?label=Docker%20udelvd-api) ![Docker Image Version (latest by date)](https://img.shields.io/docker/v/figonzal/mysql?label=Docker%20mysql) ![Uptime Robot status](https://img.shields.io/uptimerobot/status/m784784797-355fdf5539abcc0df0dcd13c)

Backend PHP construido con [Slim](http://www.slimframework.com/) que contiene una APIRest utilizada para el procesamiento de los datos generados por el aplicativo móvil [Udelvd-app](https://github.com/figonzal1/Udelvd-app)

## Construir imágenes Docker
```sh
$ docker build -t figonzal/udelvd-api:{VERSION_TAG} -f api.DockerFile .
$ docker build -t figonzal/mysq:{VERSION_TAG} -f mysq.DockerFile .
```
## Ejecutar imágenes Docker
```sh
$ docker run --name udelvd-api -p 43:43 --network RED_UDELVD figonzal/udelvd-api:{VERSION_TAG}
$ docker run --name mysql -p 5021:3306 --network RED_UDELVD -e MYSQL_DATABASE={MYSQL_DATABASE} -e MYSQL_USER={MYSQL_USER} -e MYSQL_ROOT_PASSWORD={MYSQL_ROOT_PASSWORD} -e MYSQL_PASSWORD={MYSQL_PASSWORD} -d mysql:{VERSION_TAG}
```
## Generación de certificados autofirmados
```
#### Mysql-Server
Certificado CA
```sh
$ openssl genrsa 2048 > ca-key.pem
$ openssl req -new -x509 -nodes -days 3600 -key ca-key.pem -out ca.pem
```
Certificado Servidor
```sh
$ openssl req -newkey rsa:2048 -days 3600 -nodes -keyout server-key.pem -out server-req.pem
$ openssl rsa -in server-key.pem -out server-key.pem
$ openssl x509 -req -in server-req.pem -days 3600 -CA ca.pem -CAkey ca-key.pem -set_serial 01 -out server-cert.pem
```

Certificado Cliente
```sh
$ openssl req -newkey rsa:2048 -days 3600 -nodes -keyout client-key.pem -out client-req.pem
$ openssl rsa -in client-key.pem -out client-key.pem
$ openssl x509 -req -in client-req.pem -days 3600 -CA ca.pem -CAkey ca-key.pem -set_serial 01 -out client-cert.pem
```

## Encriptación datos sensibles - Travis
````sh
$ tar -czvf encrypt.tar.gz api-files/server-key.pem api-files/server.pem mysql-files/ .env udelvd-server-credentials.json
$ travis encrypt-file encrypt.tar.gz --pro
````
