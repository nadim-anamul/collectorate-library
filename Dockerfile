# Multi-stage Dockerfile for Laravel Collectorate Library
# Stage 1: Build assets (simplified for now)
FROM node:18-alpine AS assets

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source files
COPY . .

# Build assets (skip for now, will be built in container)
# RUN npm run build:production

# Stage 2: PHP Base with extensions
FROM php:8.2-fpm-alpine AS php-base

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    sqlite-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_mysql \
        pdo_sqlite \
        zip \
        intl \
        xml \
        bcmath \
        opcache

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Stage 3: PHP Composer dependencies
FROM php-base AS vendor

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --optimize-autoloader

# Stage 4: Production image
FROM php-base AS production

# Install additional system dependencies for production
RUN apk add --no-cache \
    nginx \
    supervisor

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create application user
RUN addgroup -g 1000 -S appgroup && \
    adduser -u 1000 -S appuser -G appgroup

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=appuser:appgroup . .

# Copy vendor from composer stage
COPY --from=vendor --chown=appuser:appgroup /app/vendor ./vendor

# Copy node_modules for asset building
COPY --from=assets --chown=appuser:appgroup /app/node_modules ./node_modules

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions
RUN chown -R appuser:appgroup /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create necessary directories
RUN mkdir -p /var/log/nginx \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /run/nginx

# Switch to non-root user
USER appuser

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Stage 5: Development image
FROM production AS development

# Switch back to root for development setup
USER root

# Install development dependencies
RUN apk add --no-cache \
    git \
    vim \
    bash

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js for development
RUN apk add --no-cache nodejs npm

# Copy development configuration
COPY docker/php/php-dev.ini /usr/local/etc/php/conf.d/99-dev.ini

# Switch back to app user
USER appuser

# Install development dependencies
RUN composer install --no-scripts --no-autoloader

# Generate autoloader
RUN composer dump-autoload --optimize

# Expose port for development
EXPOSE 8000

# Development command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
