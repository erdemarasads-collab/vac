#!/bin/sh
php-fpm &
sleep 2
envsubst '${PORT}' < /etc/nginx/nginx.conf > /tmp/nginx.conf
nginx -c /tmp/nginx.conf -g "daemon off;"
