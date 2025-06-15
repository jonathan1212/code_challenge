#!/bin/bash

set -e

# Clear Laravel config cache
docker-compose exec app php artisan config:clear

# Discover all test files
mapfile -t files < <(find tests/ -type f -name "*Test.php" | sort)

if [ ${#files[@]} -eq 0 ]; then
  echo "❌ No test files found."
  exit 1
fi

# Handle CLI arguments
if [ "$1" == "all" ]; then
  echo "▶️ Running all tests..."
  docker-compose exec app php artisan test
  exit 0
elif [[ "$1" =~ ^[0-9]+$ ]] && (( $1 >= 1 && $1 <= ${#files[@]} )); then
  selected="${files[$(( $1 - 1 ))]}"
  echo "▶️ Running test: $selected"
  docker-compose exec app php artisan test "$selected"
  exit 0
fi

# Interactive selection
echo ""
echo "📋 Select a test to run:"
for i in "${!files[@]}"; do
  printf "%2d) %s\n" "$((i+1))" "${files[$i]}"
done

