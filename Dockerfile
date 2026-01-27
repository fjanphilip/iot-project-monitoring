FROM dunglas/frankenphp:1-php8.3

# 1. Install dependencies sistem & ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pcntl \
        bcmath \
        zip \
        pdo_mysql

# 2. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Set Working Directory
WORKDIR /app

# 4. Salin file aplikasi
COPY . .

# 5. Install dependencies Laravel (tanpa dev untuk produksi)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6. Atur permissions agar webserver bisa menulis ke storage/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# 7. Aktifkan mode worker FrankenPHP untuk performa maksimal (Opsional tapi disarankan)
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Ekspos port 80 dan 443
EXPOSE 80
EXPOSE 443
EXPOSE 443/udp