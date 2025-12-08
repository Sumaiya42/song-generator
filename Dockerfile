# Step 1: Use PHP + Apache base image
FROM php:8.2-apache

# Step 2: Install system dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Step 3: Enable Apache mod_rewrite
RUN a2enmod rewrite

# Step 4: Set working directory
WORKDIR /var/www/html

# Step 5: Copy Laravel project files
COPY laravel_project/ /var/www/html/

# Step 6: Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Step 7: Serve Laravel's public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Step 8: Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Step 9: Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Step 10: Expose port 80
EXPOSE 80

# Step 11: Start Apache
CMD ["apache2-foreground"]
