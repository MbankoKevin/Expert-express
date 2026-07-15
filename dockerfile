# Start with PHP + Apache base image
FROM php:8.2-apache

# Enable PDO extensions (common for PHP + MySQL apps)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy your courier tracking project into Apache’s web directory
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (Apache default)
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
