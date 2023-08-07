# Use the official PHP 7.4 Apache image as the base image
FROM php:7.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    mariadb-client 
# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql zip
# Install PHP extensions if needed (e.g., pdo_mysql)
# RUN docker-php-ext-install pdo_mysql
# Set the working directory inside the container
WORKDIR /var/www/html
# Copy the backend files to the container
COPY . /var/www/html/

# Expose the port on which Apache runs (default is 80)
EXPOSE 80

# Start Apache when the container starts
CMD ["apache2-foreground"]
