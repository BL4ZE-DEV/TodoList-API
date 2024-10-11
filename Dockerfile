# Use official PHP image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    curl \
    nginx  # Install Nginx

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx configuration
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Expose HTTP port 80
EXPOSE 80

# Start both Nginx and PHP-FPM services
CMD service nginx start && php-fpm
