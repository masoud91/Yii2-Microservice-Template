FROM php:7.2-apache

MAINTAINER Masoud Aghaei <masoud@idco.io>

RUN a2enmod rewrite

COPY . /var/www/html/
