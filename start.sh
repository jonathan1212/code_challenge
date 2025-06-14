#!/bin/bash

set -e  # Exit on error

export APP_ENV=local

if [ ! -f .env ]; then
  cp .env.example .env
fi

composer install --no-interaction --prefer-dist

php artisan key:generate

php artisan config:clear
php artisan config:cache

php artisan doctrine:migrations:sync-metadata-storage
php artisan doctrine:migrations:migrate --no-interaction
