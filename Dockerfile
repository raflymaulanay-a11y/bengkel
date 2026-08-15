FROM php:7.4.33-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]