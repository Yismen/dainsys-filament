#!/bin/sh

echo "Running database migrations..."
php artisan migrate --force

echo "Optimizing application..."
php artisan optimize

echo "Starting PHP-FPM..."
exec "$@"
