# Use PHP 8.3 FPM as base image
FROM php:8.3-fpm

# Build arguments with defaults
ARG user=pooja
ARG uid=1002
ARG gid=1002

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sudo \
    zip \
    unzip \
    netcat-openbsd \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set PHP upload limits
RUN echo "upload_max_filesize=50M\npost_max_size=50M" > /usr/local/etc/php/conf.d/uploads.ini

# Copy Composer from official Composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create system group and user
RUN groupadd -g $gid $user \
    && useradd -m -u $uid -g $gid -G www-data $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user /var/www

# Copy only composer files first
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy project files
COPY . .

# Copy default environment file
COPY .env.example .env

# Fix Laravel storage and cache permissions
RUN chown -R $user:$user \
    /var/www/storage \
    /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configure Git to trust this directory
RUN git config --global --add safe.directory /var/www

# Generate Laravel app key
RUN php artisan key:generate

# Set user for runtime
USER $user

# Default command
CMD ["php-fpm"]
