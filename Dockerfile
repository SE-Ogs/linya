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

# Create all necessary Laravel directories first
RUN mkdir -p storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage \
    && chmod -R 775 bootstrap/cache

# Set ownership to www-data
RUN chown -R www-data:www-data /var/www/html

# Install dependencies without running post-install scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Create a startup script
RUN echo '#!/bin/bash\n\
# Ensure directories exist and have correct permissions\n\
mkdir -p /var/www/html/storage/framework/{cache/data,sessions,views} /var/www/html/bootstrap/cache\n\
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache\n\
\n\
# Only run artisan commands if .env exists\n\
if [ -f /var/www/html/.env ]; then\n\
    echo "Running Laravel setup commands..."\n\
    php artisan package:discover --ansi\n\
    php artisan config:clear\n\
    php artisan view:clear\n\
    php artisan route:clear\n\
    echo "Laravel setup completed."\n\
else\n\
    echo "No .env file found, skipping Laravel setup commands."\n\
fi\n\
\n\
# Start Apache\n\
echo "Starting Apache..."\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Use the startup script
CMD ["/usr/local/bin/start.sh"]
