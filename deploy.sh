#!/bin/bash
set -e

echo "🚀 Starting deployment process..."

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

# Navigate to backend directory
cd backend

# Run tests before deployment
echo "🧪 Running tests..."
if ./test.sh; then
    print_success "All tests passed!"
else
    print_error "Tests failed! Deployment aborted."
    exit 1
fi

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
docker compose exec app composer install --no-dev --optimize-autoloader

# Run migrations
echo "🗄️  Running database migrations..."
docker compose exec app php artisan migrate --force

# Clear and cache configuration
echo "⚙️  Optimizing application..."
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Restart services
echo "🔄 Restarting services..."
docker compose restart app

print_success "Deployment completed successfully!"
echo "🎉 Application is now live!"
