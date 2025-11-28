#!/bin/bash
# Test runner script that uses SQLite in-memory database
# This prevents tests from affecting your PostgreSQL production database

# Check if running inside Docker container
if [ -f /.dockerenv ]; then
    # Inside Docker: run tests directly with SQLite
    DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test "$@"
else
    # Outside Docker: run via docker compose
    docker compose exec \
      -e DB_CONNECTION=sqlite \
      -e DB_DATABASE=:memory: \
      app php artisan test "$@"
fi
