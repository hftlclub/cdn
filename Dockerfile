FROM php:7.2-apache
MAINTAINER Stecker-HfTL-Club
COPY *.php /var/www/html/
COPY .htaccess /var/www/html/
RUN mkdir -p /var/www/html/upload
RUN mkdir -p /var/www/html/sync
RUN a2enmod rewrite
COPY upload/* /var/www/html/upload/
COPY sync/* /var/www/html/sync/
RUN ls -la /var/www/html/
EXPOSE 80

