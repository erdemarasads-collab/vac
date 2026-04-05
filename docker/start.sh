#!/bin/sh

LISTEN_PORT=${PORT:-8080}

echo "==> Starting on port: $LISTEN_PORT"

cat > /etc/nginx/nginx.conf << NGINXCONF
worker_processes 1;
events {
    worker_connections 1024;
}
http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    server {
        listen $LISTEN_PORT;
        root /var/www/html;
        index index.php index.html;

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location /mextonmadmin/ {
            try_files \$uri \$uri/ /mextonmadmin/index.php?\$query_string;
        }

        location ~ \.php\$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_read_timeout 10;
            include fastcgi_params;
        }
    }
}
NGINXCONF

php-fpm &
sleep 2
exec nginx -g "daemon off;"
