FROM php:8.2-apache
COPY . /var/web/html
WORKDIR /var/web/html
EXPOSE 80
