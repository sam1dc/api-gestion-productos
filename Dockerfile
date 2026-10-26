FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP requeridas por Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_sqlite pdo_mysql bcmath zip opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache para rutas de Laravel
RUN a2enmod rewrite

# Configurar Apache con el archivo dedicado
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar manifiestos de Composer para aprovechar la cache de capas de Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

# Copiar el codigo fuente del proyecto
COPY . .

# Preparar archivo de entorno por defecto si no se pasa por variables de entorno
RUN cp .env.example .env

# Asignar permisos a storage, bootstrap/cache y database
RUN mkdir -p database storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

# Configurar script de inicio
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Puerto por defecto (Render inyecta su propio $PORT)
EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
