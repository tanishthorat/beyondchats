# Use the official PHP image with Apache
FROM php:8.2-apache

# 1. Install development packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    unzip \
    zip \
    git \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# 3. Configure Apache DocumentRoot to point to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set working directory
WORKDIR /var/www/html

# 6. Copy application files
COPY . .

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 8. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html

# 9. Expose port 80
EXPOSE 80
