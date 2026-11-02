FROM php:8.1-fpm

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    nginx \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Nginx config template (uses $PORT at runtime)
RUN echo 'server { \n\
    listen __PORT__; \n\
    root /var/www/html/public; \n\
    index index.php; \n\
    location / { try_files $uri $uri/ /index.php?$query_string; } \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_index index.php; \n\
        include fastcgi_params; \n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \n\
    } \n\
}' > /etc/nginx/sites-available/default

# Storage permissions
RUN mkdir -p storage/logs storage/app && chmod -R 777 storage

# Startup script: run migrations, start php-fpm, then nginx on $PORT
RUN echo '#!/bin/sh\n\
set -e\n\
php /var/www/html/database/migrate.php\n\
php-fpm -D\n\
PORT=${PORT:-80}\n\
sed -i "s/__PORT__/$PORT/g" /etc/nginx/sites-available/default\n\
exec nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
