#!/bin/sh

set -e

# Generate APP_KEY if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear caches
echo "Clearing application caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data /app/storage /app/bootstrap/cache

echo "Application startup complete!"

# Execute the command
exec "$@"
