FROM php:8.2-cli

# Install system dependencies + NodeJS
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libzip-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Working directory
WORKDIR /var/www

# Copy files
COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Build Vite assets
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
RUN npm run build 
=======
RUN npm run build
>>>>>>> be7b541 (fix proxy)
=======
RUN npm run build 
>>>>>>> 21a9eb0 (perbaiki column phone db pasien)
=======
RUN npm run build
>>>>>>> 6006a35 (fix dockerfile railway deployment)

# Laravel cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# Permissions
RUN chmod -R 775 storage bootstrap/cache

<<<<<<< HEAD
<<<<<<< HEAD
=======
<<<<<<< HEAD
# Expose Railway port
=======
>>>>>>> 571ba84 (fix docker file)
>>>>>>> be7b541 (fix proxy)
=======
>>>>>>> 6006a35 (fix dockerfile railway deployment)
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000