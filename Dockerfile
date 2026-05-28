# =========================================
# Stage 1: Composer Dependencies
# =========================================
FROM php:8.4-cli AS vendor

# Install system dependencies required for Composer
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs \
    --no-scripts

# Copy all application files
COPY . .

# Create required directories for Laravel discovery scripts
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache

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
FROM php:8.4-apache

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
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh

# Expose Apache
EXPOSE 80

# Use the entrypoint script to prepare the environment dynamically at runtime
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
