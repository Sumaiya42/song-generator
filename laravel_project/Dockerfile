# Step 1: Use PHP + Apache base image
FROM php:8.2-apache

# Step 2: Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
&& docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring zip \
&& a2enmod rewrite



# Step 3: Set working directory
WORKDIR /var/www/html

# Step 4: Copy project files
COPY . .

# Step 5: Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Step 6: Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Step 7: Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Step 8: Set Apache DocumentRoot to Laravel public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Step 9: Expose port 80
EXPOSE 80

# Step 10: Start Apache
CMD ["apache2-foreground"]
