FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apk add --no-cache nginx

# php-fpm env variable'ları görsün
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

COPY . /var/www/html/
COPY docker/start.sh /start.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /start.sh

CMD ["/start.sh"]
