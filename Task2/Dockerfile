# Use the official PHP image with Apache
FROM php:7.4-apache

RUN apt-get update && apt-get upgrade -y \
    && apt-get clean && rm -rf /var/lib/apt/lists/* 

# Install pdo_mysql package and configure Apache
RUN docker-php-ext-install pdo_mysql && a2enmod headers
RUN sed -ri -e 's/^([ \t]*)(<\/VirtualHost>)/\1\tHeader set Access-Control-Allow-Origin "*"\n\1\2/g' /etc/apache2/sites-available/*.conf

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80
