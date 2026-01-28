FROM dunglas/frankenphp:1-php8.4

# Install basic tools & PHP extensions (Pecah per layer agar hemat RAM saat build)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libzip-dev libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j1 gd

RUN docker-php-ext-install -j1 pcntl bcmath zip pdo_mysql intl sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy dependency files
COPY composer.json composer.lock package.json package-lock.json ./

# Install Composer tanpa dev (Hemat RAM)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install NPM & Build (Langkah paling berat, Swap sangat dibutuhkan di sini)
RUN npm install && npm run build && rm -rf node_modules

# Copy sisa file
COPY . .

# Bersihkan sampah cache yang mungkin terbawa
RUN rm -rf bootstrap/cache/*.php && \
    chown -R www-data:www-data storage bootstrap/cache

ENV FRANKENPHP_CONFIG="worker ./public/index.php"
EXPOSE 80