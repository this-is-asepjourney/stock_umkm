
# =========================================
# Stage 1: Composer Dependencies
# =========================================
FROM composer:2.7 AS vendor
FROM php:8.3-apache
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# Copy all application files
COPY . .

# Regenerate optimized autoload
RUN composer dump-autoload --optimize


# =========================================
# Stage 2: Frontend Build
# =========================================
FROM node:20 AS frontend

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install node modules
RUN npm install

# Copy app files
COPY . .

# Build Vite assets
RUN npm run build


# =========================================
# Stage 3: Production Image
# =========================================
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Configure Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# Copy frontend build
COPY --from=frontend /app/public/build ./public/build

# Laravel permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create .env if missing
RUN cp .env.example .env || true

# Generate Laravel APP_KEY
RUN php artisan key:generate || true

# Optimize Laravel
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Laravel storage link
RUN php artisan storage:link || true


# Expose Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

