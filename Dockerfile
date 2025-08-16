# Use an official PHP image with Apache
FROM php:8.1-apache

# Copy project files into container
COPY . /var/www/html/

# Enable Apache rewrite module
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
