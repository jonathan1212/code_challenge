#!/bin/bash

# Default container name
CONTAINER_NAME="laravel_app"

# Allow custom name as argument
if [ ! -z "$1" ]; then
  CONTAINER_NAME="$1"
fi

# Log into the container
echo "🔐 Logging into container: $CONTAINER_NAME"
docker exec -it $CONTAINER_NAME bash
