FROM php:8.3-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setear directorio de trabajo
WORKDIR /app

# Copiar archivos del proyecto
COPY . .

# Permisos
RUN chown -R www-data:www-data /app

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]