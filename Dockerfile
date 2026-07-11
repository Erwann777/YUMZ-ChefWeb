FROM php:8.3-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring gd \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js (untuk build assets Vite/Mix)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build && rm -rf node_modules

# Permissions untuk storage & cache
RUN chmod -R 775 storage bootstrap/cache

# Script startup (dijalankan saat container start, bukan saat build)
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache