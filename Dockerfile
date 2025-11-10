# Multi-stage Dockerfile for Laravel application with PHP 8.2
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis and PCOV coverage driver
RUN apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        make \
    && pecl install redis pcov \
    && docker-php-ext-enable redis pcov \
    && apk del .build-deps

RUN echo "pcov.enabled=1" > /usr/local/etc/php/conf.d/pcov.ini \
    && echo "pcov.exclude=\"~vendor~\"" >> /usr/local/etc/php/conf.d/pcov.ini \
    && echo "pcov.directory=\"/var/www/html\"" >> /usr/local/etc/php/conf.d/pcov.ini


# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Development stage
FROM base AS development

# Install development dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

# Production stage
FROM base AS production

# Install production dependencies only
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copy application code
COPY . .

# Generate optimized autoloader for production
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

# Optimize Laravel for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
