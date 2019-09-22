FROM php:7.2-apache

MAINTAINER Masoud Aghaei <masoud@idco.io>

RUN a2enmod rewrite

COPY . /var/www/html/

#COPY components /var/www/html/
#COPY config /var/www/html/
#COPY controllers /var/www/html/
#COPY docs /var/www/html/
#COPY messages /var/www/html/
#COPY models /var/www/html/
#COPY web /var/www/html/