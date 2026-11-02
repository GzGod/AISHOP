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

# Nginx config
RUN echo 'server { \n\
    listen 80; \n\
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

EXPOSE 80

# Start: migrate → php-fpm → nginx
CMD php database/migrate.php 2>&1 && php-fpm -D && nginx -g 'daemon off;'
