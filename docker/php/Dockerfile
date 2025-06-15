FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user=1000
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libreadline-dev \
    libicu-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ✅ Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Xdebug (for development)
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

## Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user

RUN git config --global --add safe.directory /var/www

# Set working directory
WORKDIR /var/www

#USER $user
