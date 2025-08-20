FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    libpq-dev \
    curl \
    unzip \
    supervisor && \
    rm -rf /var/lib/apt/lists/*

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql zip

# Set the Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && chmod +x /usr/local/bin/composer

# Install project dependencies for production
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ensure Laravel cache directories exist
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
 && chmod -R 777 storage bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Remove .env file (will be replaced by environment variables)
RUN rm -f .env

# Create supervisord config for Laravel queue worker
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Start supervisor (manages Apache and queue workers)
CMD ["/usr/bin/supervisord", "-n"]
