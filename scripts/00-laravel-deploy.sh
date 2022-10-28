#!/usr/bin/env bash
echo "Copy .env"
php -r "file_exists('.env') || copy('.env.example', '.env');"

echo "Install Dependencies"
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

echo "Generate key"
php artisan key:generate

echo "Directory Permissions"
chmod -R 777 storage bootstrap/cache

echo "Caching config..."
php artisan optimize

echo "Running migrations..."
php artisan migrate:fresh --seed

echo "Link files"
php artisan storage:link

echo "Run test"
php artisan test
