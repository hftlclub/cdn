FROM php:7.2-apache
COPY . /var/www/html/
COPY .htaccess /var/www/html/
RUN mkdir -p /var/www/html/upload/files && mkdir -p /var/www/html/sync && a2enmod rewrite
EXPOSE 80

