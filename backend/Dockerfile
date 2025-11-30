# Production Dockerfile for Laravel Backend
ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-fpm

# Install production dependencies only
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    postgresql-client \
    && docker-php-ext-install pdo_pgsql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure OPcache for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy application files
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and set permissions for entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

USER www-data

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
