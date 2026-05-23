FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libzip-dev \
    nodejs \
    npm

# PHP Extensions
RUN docker-php-ext-install gd zip pdo pdo_mysql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies
RUN npm install

# Build Vite assets
<<<<<<< HEAD
<<<<<<< HEAD
RUN npm run build 
=======
RUN npm run build
>>>>>>> be7b541 (fix proxy)
=======
RUN npm run build 
>>>>>>> 21a9eb0 (perbaiki column phone db pasien)

# Laravel cache
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

<<<<<<< HEAD
=======
<<<<<<< HEAD
# Expose Railway port
=======
>>>>>>> 571ba84 (fix docker file)
>>>>>>> be7b541 (fix proxy)
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=${PORT}