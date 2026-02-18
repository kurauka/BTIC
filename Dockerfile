FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod +x /var/www/html/entrypoint.sh

# Use entrypoint script to handle PORT env var
ENTRYPOINT ["/var/www/html/entrypoint.sh"]
