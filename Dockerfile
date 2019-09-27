FROM php:7.2-apache
COPY . /var/www/html/
COPY .htaccess /var/www/html/
RUN apt-get update && \
  apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
  docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/  &&  \
  docker-php-ext-install gd
RUN a2enmod rewrite
EXPOSE 80

