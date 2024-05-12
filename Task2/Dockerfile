# Use the official PHP image with Apache
FROM php:7.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy files and directories from the application
COPY public/ /var/www/html/
COPY src/ /var/www/private/

# Set the working directory
WORKDIR /var/www/html

# Expose web port
EXPOSE 80
