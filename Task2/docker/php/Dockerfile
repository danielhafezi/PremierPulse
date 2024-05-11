FROM php:7.4-fpm

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli
# mysqli is optional, depends on your PHP database needs

# Install additional utilities
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl
