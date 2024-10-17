# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies and required libraries
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    curl \
    supervisor  # Install Supervisor to manage multiple services \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/include/postgresql \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /var/www

# Copy the application code into the container
COPY . .

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set permissions for Laravel's storage and cache directories
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy the Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose the necessary ports
EXPOSE 80 9000

# Start Supervisor to manage PHP-FPM and Nginx
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
