#!/bin/bash
set -e

echo "Waiting for database to be ready..."

# Wait for PostgreSQL to be ready
max_attempts=30
attempt=0
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -c '\q' 2>/dev/null; do
    attempt=$((attempt + 1))
    if [ $attempt -ge $max_attempts ]; then
        echo "Database connection timeout after $max_attempts attempts"
        exit 1
    fi
    echo "Database not ready, waiting... (attempt $attempt/$max_attempts)"
    sleep 2
done

echo "Database is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders only if tables are empty
echo "Checking if seeding is needed..."
table_count=$(PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -t -c "SELECT COUNT(*) FROM stations;" 2>/dev/null | tr -d ' ')

if [ "$table_count" = "0" ] || [ -z "$table_count" ]; then
    echo "Running seeders..."
    php artisan db:seed --force
else
    echo "Database already seeded, skipping seeders..."
fi

echo "Application ready!"

# Start PHP-FPM
exec php-fpm
