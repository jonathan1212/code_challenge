#!/bin/bash

# Script to manage Laravel Docker setup

# ./docker-laravel.sh up
# ./docker-laravel.sh stop
# ./docker-laravel.sh pull
# ./docker-laravel.sh logs

APP_NAME="laravel-app"

case "$1" in
  up)
    echo "🔼 Starting containers..."
    docker-compose up -d
    ;;
  stop)
    echo "🛑 Stopping containers..."
    docker-compose down
    ;;
  pull)
    echo "📥 Pulling latest images..."
    #docker-compose pull
    docker-compose down
    docker-compose build --no-cache
    docker-compose up -d
    ;;
  build)
    echo "🔧 Building containers... -- run if new configuration added"
    docker-compose build
    docker-compose up -d
    ;;
  restart)
    echo "♻️ Restarting containers..."
    docker-compose down && docker-compose up -d
    ;;
  logs)
    echo "📋 Showing logs..."
    docker-compose logs -f
    ;;
  *)
    echo "Usage: $0 {up|stop|pull|build|restart|logs}"
    exit 1
esac


# docker-compose down --volumes
# docker-compose build --no-cache
# docker-compose up -d


# docker ps
# docker exec -it redis redis-cli ping

##To confirm Redis is installed inside the container:
#docker exec -it laravel-app php -m | grep redis

# docker exec -it laravel-app php -v
