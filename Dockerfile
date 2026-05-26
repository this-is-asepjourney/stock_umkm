# Stage 1: Build PHP dependencies
FROM composer:2.7 AS vendor

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without autoloader and scripts to leverage Docker caching
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-dev \
    --ignore-platform-reqs \
    --no-scripts \
    --no-autoloader

# Copy application files to /app so that artisan and source files are present
COPY . .

# Generate optimized autoloader and run Laravel post-autoload-dump scripts
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev \
    --ignore-platform-reqs


# Stage 2: Build Node.js dependencies (Frontend assets)
FROM node:20 AS frontend

WORKDIR /app

# Copy package.json and related files
COPY package.json package-lock.json* vite.config.js tailwind.config.js postcss.config.js ./

# Install npm dependencies
RUN npm install

# Copy application files needed for build
COPY . .

# Build frontend assets - ensure public/build is created
RUN npm run build && \
    echo "✅ Build completed. Build directory structure:" && \
    ls -la public/build/ || true

# Stage 3: Setup Production Environment
FROM php:8.3-apache

# Install Node.js (needed for potential asset rebuilding at runtime)
RUN apt-get update && \
    apt-get install -y curl && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Configure Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy vendor directory from vendor stage
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

# Copy built frontend assets from frontend stage (if exists)
# Note: In Laravel 11 with Vite, the build is usually in public/build
RUN mkdir -p /var/www/html/public/build
COPY --from=frontend /app/public/build/ /var/www/html/public/build/

# Set permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/public/build

# Copy custom entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh


# Expose port 80
EXPOSE 80

# Use the entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache in foreground
CMD ["apache2-foreground"]
