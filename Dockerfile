FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apk add --no-cache nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
