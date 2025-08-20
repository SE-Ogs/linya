FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    libpq-dev \
    curl \
    unzip && \
    rm -rf /var/lib/apt/lists/*

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql zip

# Set the Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && chmod +x /usr/local/bin/composer

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# Install dependencies without running scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Expose port 80
EXPOSE 80

# Start Apache and run Laravel setup commands
CMD php artisan package:discover --ansi && \
    php artisan config:cache && \
    apache2-foreground
