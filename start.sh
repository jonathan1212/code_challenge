#!/bin/bash

set -e  # Exit on error

export APP_ENV=local

if [ ! -f .env ]; then
  touch .env || { echo "ERROR: Cannot create .env"; exit 1; }
  cp .env.example .env
fi

echo "Creating test database 'laravel_test'..."
docker-compose exec -T db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS code_challenge_testing;;"


docker-compose exec app composer install --no-interaction --prefer-dist
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan config:clear
#docker-compose exec app php artisan cache:clear


docker-compose exec app php artisan migrate
docker-compose exec app php artisan doctrine:migrations:sync-metadata-storage
docker-compose exec app php artisan doctrine:migrations:migrate --no-interaction

# Create test database
