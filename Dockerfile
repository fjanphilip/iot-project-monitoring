FROM dunglas/frankenphp:1-php8.4

# 1. Install dependencies dasar & PHP extensions (Tanpa Node.js!)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libzip-dev libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j1 gd pcntl bcmath zip pdo_mysql intl sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 2. Install PHP Dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# 3. Salin seluruh kode aplikasi (Termasuk folder public/build yang sudah Anda kirim)
COPY . .

# 4. Bersihkan cache & set permission
RUN rm -rf bootstrap/cache/*.php && \
    chown -R www-data:www-data storage bootstrap/cache

ENV FRANKENPHP_CONFIG="worker ./public/index.php"
EXPOSE 80