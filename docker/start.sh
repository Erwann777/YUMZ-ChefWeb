#!/usr/bin/env bash
set -e

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage (jika perlu)..."
php artisan storage:link || true

echo "Starting server on port ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"