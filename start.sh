#!/bin/bash

set -e  # Exit on error

export APP_ENV=local

if [ ! -f .env ]; then
  touch .env || { echo "ERROR: Cannot create .env"; exit 1; }
  cp .env.example .env
fi

composer install --no-interaction --prefer-dist

php artisan key:generate

php artisan config:clear
php artisan config:cache

php artisan migrate
php artisan doctrine:migrations:sync-metadata-storage
php artisan doctrine:migrations:migrate --no-interaction
