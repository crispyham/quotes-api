# Use an official PHP image with Apache
FROM php:8.2-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Set working directory inside the container
WORKDIR /var/www/html

# Copy project files to the container
COPY . .

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Enable Apache rewrite module
RUN a2enmod rewrite

# Expose port 80 for the web server
EXPOSE 80

# Start Apache when the container launches
CMD ["apache2-foreground"]
