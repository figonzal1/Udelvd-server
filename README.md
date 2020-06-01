<p align="center">
  <img src="https://github.com/figonzal1/Udelvd-app/blob/master/app/src/main/ic_launcher-web.png" width="200" height="200">
</p>
<h1 align="center">Udelvd-server</h1>

<p align="center">
  
  <a href="https://img.shields.io/docker/v/figonzal/udelvd-api?label=Docker%20udelvd-api" alt="Docker Image Version">
        <img alt="Docker Image Version" src="https://img.shields.io/docker/v/figonzal/udelvd-api?label=Docker%20udelvd-api">
  </a>
  
  <a href="https://img.shields.io/docker/v/figonzal/mysql?label=Docker%20mysql" alt="Docker Image Version">
        <img alt="Docker Image Version" src="https://img.shields.io/docker/v/figonzal/mysql?label=Docker%20mysql">
  </a>
  
  <a href="https://travis-ci.com/figonzal1/Udelvd-server" alt="Travis">
        <img alt="Travis Builds" src="https://travis-ci.com/figonzal1/Udelvd-server.svg?branch=udelvd.tk">
  </a>
  
  <a href="https://www.codefactor.io/repository/github/figonzal1/udelvd-server" alt="CodeFactor">
        <img src="https://www.codefactor.io/repository/github/figonzal1/udelvd-server/badge" />
  </a>
  
  <a href="https://img.shields.io/github/languages/top/figonzal1/Udelvd-server" alt="Top Language">
        <img alt="GitHub repo language" src="https://img.shields.io/github/languages/top/figonzal1/Udelvd-server">
  </a>
  
  <a href="https://img.shields.io/github/repo-size/figonzal1/Udelvd-server" alt="GitHub repo size">
        <img alt="GitHub repo size" src="https://img.shields.io/github/repo-size/figonzal1/Udelvd-server">
  </a>
  
  <a href="https://img.shields.io/github/last-commit/figonzal1/Udelvd-server?color=yellow" alt="Last Commit">
        <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/figonzal1/Udelvd-server?color=yellow">
  </a>
  
  <a href="https://undiaenlavidade.cl" alt="Uptime Status">
        <img alt="Uptime Status" src="https://img.shields.io/uptimerobot/status/m784784797-355fdf5539abcc0df0dcd13c?label=website%20status">
  </a>
  <a href="https://securityheaders.com/?q=undiaenlavidade.cl&hide=on&followRedirects=on" alt="Security headers">
    <img alt="Security Headers" src="https://img.shields.io/security-headers?label=Website%20security&url=https%3A%2F%2Fundiaenlavidade.cl">
  </a>
  
</p>

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
