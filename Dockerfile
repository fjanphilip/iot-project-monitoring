# Stage 1: Builder - Compile dependencies
FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache \
    git curl composer \
    libzip-dev libpng-dev libjpeg-turbo-dev libfreetype-dev libicu-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pcntl bcmath zip pdo_mysql intl sockets

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# Stage 2: Production - Minimal image
FROM php:8.3-fpm-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    libzip libpng libjpeg-turbo libfreetype libicu nginx curl supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pcntl bcmath zip pdo_mysql intl sockets

# Copy PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY supervisord.conf /etc/supervisord.conf
COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /app

# Copy vendor dari builder stage
COPY --from=builder --chown=www-data:www-data /app/vendor ./vendor

# Copy application code
COPY --chown=www-data:www-data . .

# Create storage directories
RUN mkdir -p \
    bootstrap/cache \
    storage/app/{private,public} \
    storage/framework/{cache,sessions,testing,views} \
    storage/logs && \
    chmod -R 755 bootstrap/cache storage

# Expose ports
EXPOSE 9000 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

ENTRYPOINT ["/app/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]