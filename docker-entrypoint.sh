#!/bin/bash
set -e

# Copy .env.example to .env if it does not exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY if it's not set in the environment and not set in .env
if [ -z "$APP_KEY" ]; then
    if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
        echo "No APP_KEY found, generating one..."
        php artisan key:generate --force
    fi
fi

# Run standard Laravel optimization commands
echo "Optimizing application..."
php artisan optimize:clear

# Create storage link if it doesn't exist
echo "Creating storage link..."
php artisan storage:link

# Pass control to the CMD from Dockerfile
exec "$@"

