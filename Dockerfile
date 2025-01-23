# Use the official PHP image with Apache
FROM php:8.3-apache AS base

# Copy the Apache virtual host configuration file
COPY ./docker/vhostt.conf /etc/apache2/sites-available/000-default.conf

# Set working directory inside the container
WORKDIR /var/www/translation-management

# Install necessary dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    locales \
    zip \
    libicu-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    curl \
    nano \
    openssh-client \
    nodejs \
    npm \
    awscli \
    xvfb \
    wkhtmltopdf \
    fontconfig \
    libxrender1 \
    libxext6 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip intl calendar \
    && apt-get clean

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set memory_limit in php.ini-development and php.ini-production
RUN sed -i "s/memory_limit = .*/memory_limit = 4028M/" /usr/local/etc/php/php.ini-development && \
    sed -i "s/memory_limit = .*/memory_limit = 4028M/" /usr/local/etc/php/php.ini-production

# Replace with your base image
RUN echo "memory_limit=4028M" > /usr/local/etc/php/conf.d/memory-limit.ini    

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Vue.js CLI globally
RUN npm install -g @vue/cli

# Use a separate stage to set up SSH keys
FROM base AS composer

# Copy SSH keys for saeesa package private repo
#COPY .ssh /root/.ssh

# Set permissions and configure SSH
#RUN chmod 700 /root/.ssh && \
#    chmod 600 /root/.ssh/id_ed25519 && \
#    chmod 644 /root/.ssh/id_ed25519.pub && \
#    ssh-keyscan github.com >> /root/.ssh/known_hosts

# Copy the application code to the container
COPY . /var/www/translation-management

# Run composer install
#RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Continue with the final stage
FROM base AS final

# Copy the application code and vendor files from the previous stage
COPY --from=composer /var/www/translation-management /var/www/translation-management

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/translation-management

# Create necessary directories
RUN mkdir -p /var/www/translation-management/storage/framework/sessions \
    /var/www/translation-management/storage/framework/views

# Set permissions for the application directories
RUN chown -R www-data:www-data /var/www/translation-management/storage /var/www/translation-management/bootstrap/cache \
    && chmod -R 775 /var/www/translation-management/storage /var/www/translation-management/bootstrap/cache

# Install Vue.js dependencies if needed (package.json must be in your project root)
WORKDIR /var/www/translation-management
RUN if [ -f package.json ]; then npm install; fi

# Build Vue.js assets for production (uncomment if needed)
#RUN if [ -f package.json ]; then npm run build; fi

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
