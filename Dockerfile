# ===================================================
# Tahap 1: Build Frontend (Node.js & Vite)
# ===================================================
FROM node:22-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# ===================================================
# Tahap 2: Build Vendor (Composer & PHP CLI)
# ===================================================
FROM php:8.4-cli-alpine AS vendor
WORKDIR /app

# Ambil binary Composer dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-interaction

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# ===================================================
# Tahap 3: Runtime Akhir (PHP-FPM, Nginx, Supervisor)
# ===================================================
FROM php:8.4-fpm-alpine AS runtime

# Install Nginx, Supervisor, utility packages, dan PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    netcat-openbsd \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring gd zip bcmath opcache \
    && apk del libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev oniguruma-dev

WORKDIR /var/www/html

# Salin konfigurasi Nginx, Supervisor, dan Entrypoint
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Salin source code project
COPY . /var/www/html

# Salin dependensi PHP dari tahap vendor & aset frontend dari tahap frontend
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

# Setup permission direktori storage dan cache
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80 untuk layanan HTTP web
EXPOSE 80

# Jalankan entrypoint dan supervisor
ENTRYPOINT ["entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
