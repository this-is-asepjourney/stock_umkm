#!/bin/bash
set -e

# Run standard Laravel optimization commands
echo "Optimizing application..."
php artisan optimize:clear

# Create storage link if it doesn't exist
echo "Creating storage link..."
php artisan storage:link

# Uncomment the following line if you want to run migrations automatically on container startup.
# Note: In a production environment like Dokploy, you might want to run this as a Pre-deploy command instead.
# echo "Running migrations..."
# php artisan migrate --force

# Pass control to the CMD from Dockerfile
exec "$@"
