#!/bin/bash

php artisan migrate
php artisan doctrine:migrations:sync-metadata-storage
php artisan doctrine:migrations:migrate --no-interaction

