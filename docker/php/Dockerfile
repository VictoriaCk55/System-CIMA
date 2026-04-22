# Usar PHP 8.2 con FPM
FROM php:8.2-fpm

# Instalar extensiones necesarias para PostgreSQL y Laravel
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Configurar permisos
RUN chown -R www-data:www-data /var/www

# Exponer el puerto de PHP-FPM
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]