# Udelvd-server <br/> [![Project Status](https://opensource.box.com/badges/active.svg)](https://opensource.box.com/badges) [![CodeFactor](https://www.codefactor.io/repository/github/figonzal1/udelvd-server/badge)](https://www.codefactor.io/repository/github/figonzal1/udelvd-server) [![Known Vulnerabilities](https://snyk.io/test/github/figonzal1/Udelvd-server/badge.svg?targetFile=composer.lock)](https://snyk.io/test/github/figonzal1/Udelvd-server?targetFile=composer.lock)
Servidor-APIRest utilizado para procesamiento de los datos generados por aplicativo móvil [Udelvd-app](https://github.com/figonzal1/Udelvd-app)

## Construir imágenes Docker
```sh
$ docker build -t figonzal/http-proxy:{VERSION_TAG} -f proxy.DockerFile .
$ docker build -t figonzal/udelvd-api:{VERSION_TAG} -f api.DockerFile .
$ docker build -t figonzal/mysq:{VERSION_TAG} -f mysq.DockerFile .
```
## Ejecutar imágenes Docker
```sh
$ docker run --name proxy -p 80:80 -p 443:443 --network RED_UDELVD figonzal/http-proxy:{VERSION_TAG}
$ docker run --name udelvd-api --network RED_UDELVD figonzal/udelvd-api:{VERSION_TAG}
$ docker run --name mysql -p 3306:3306 --network RED_UDELVD -e MYSQL_DATABASE={MYSQL_DATABASE} -e MYSQL_USER={MYSQL_USER} -e MYSQL_ROOT_PASSWORD={MYSQL_ROOT_PASSWORD} -e MYSQL_PASSWORD={MYSQL_PASSWORD} -d mysql:{VERSION_TAG}
```
## Generación de certificados autofirmados
#### Apache proxy
```sh
$ openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout apache.key -out apache.crt
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
