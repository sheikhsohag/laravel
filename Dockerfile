# ----------------------------------------------------------------------------------
# Stage 1: PHP Dependencies and Extensions (php_builder)
# This stage installs all necessary system packages and PHP extensions (like zip, gd).
# ----------------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS php_builder

# Set user and group IDs (from .env/shell, defaults to 1000:1000)
ARG PUID=1000
ARG PGID=1000

# Install production and common dev system dependencies and PHP extensions
RUN apk update \
    && apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    libtool \
    \
    # Install all necessary runtime libraries, including freetype for gd
    && apk add --no-cache \
    git \
    unzip \
    libpq \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu \
    libzip \
    \
     # Install Redis extension
    && pecl install redis \
    && docker-php-ext-enable redis \
    \
    # Configure and install PHP extensions
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    opcache \
    bcmath \
    gd \
    intl \
    exif \
    zip \
    \
    # Clean up the build dependencies to reduce image size
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# ----------------------------------------------------------------------------------
# Stage 2: Composer Dependency Installation (composer_installer)
# Inherit from php_builder to ensure correct PHP version (8.3) and platform extensions.
# ----------------------------------------------------------------------------------
FROM php_builder AS composer_installer

# Install Composer binary
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
# FIX: Copy entire application context so 'artisan' is available.
COPY . .

# FIX: Add --no-scripts to prevent the post-autoload-dump script (which fails due to missing dev dependencies like Telescope) from running.
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# ----------------------------------------------------------------------------------
# Stage 3: Node/NPM Frontend Build (node_builder)
# This stage compiles all JavaScript/CSS assets.
# ----------------------------------------------------------------------------------
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy lock files and install dependencies first (better caching)
COPY package.json package-lock.json ./

# 1. Install all dependencies strictly according to package-lock.json
RUN npm ci

# 2. Explicitly install the missing dependency for Rollup/Vite resolution
RUN npm install laravel-echo pusher-js --no-save

# Copy the rest of the application files (like resources/js/echo.js)
COPY . .

# 3. Run the build (Vite generates assets in public/build)
RUN npm run build

# ----------------------------------------------------------------------------------
# Stage 4: Final Production Image (app)
# This stage combines the compiled PHP environment and the application files.
# ----------------------------------------------------------------------------------
FROM php_builder AS app

# Copy necessary files from the installer stages
WORKDIR /var/www/html
# Copy application code (excluding vendor, node_modules)
COPY . .

# Copy vendor directory from composer_installer stage
COPY --from=composer_installer /var/www/html/vendor /var/www/html/vendor

# Copy compiled frontend assets from node_builder stage
# This single command copies the entire public/build folder, including manifest.json.
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Set user and group IDs
ARG PUID=1000
ARG PGID=1000

# Create system user and set up permissions
RUN set -eux; \
    # FIX: Safely remove the existing user/group from the base image before re-adding them with specific IDs
    deluser www-data 2>/dev/null || true; \
    delgroup www-data 2>/dev/null || true; \
    \
    addgroup -g $PGID www-data; \
    adduser -u $PUID -D -S -G www-data www-data

# Configure permissions for storage/cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && find /var/www/html -type f -print0 | xargs -0 chmod 664 \
    && find /var/www/html -type d -print0 | xargs -0 chmod 775

# Set the working user
USER www-data

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
