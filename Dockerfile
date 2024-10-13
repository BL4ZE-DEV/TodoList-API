# Use the official PHP 8.3 FPM image
FROM php:8.3-fpm

# Install system dependencies and required libraries
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    curl

# Install PHP extensions with necessary libraries
RUN docker-php-ext-configure pgsql --with-pgsql=/usr/include/postgresql && \
    docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring

# Set the working directory
WORKDIR /var/www

# Copy the application code into the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose the necessary ports
EXPOSE 80 9000

# Start the PHP-FPM service
CMD ["php-fpm"]
