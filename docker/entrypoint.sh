#!/bin/bash
set -e

# Configurar el puerto de Apache segun la variable PORT de Render (por defecto 8000)
PORT_HTTP=${PORT:-8000}
sed -i "s/80/${PORT_HTTP}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Asegurar archivo de base de datos SQLite con permisos
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

# Asegurar permisos de almacenamiento y cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generar APP_KEY si no esta definida
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Migrar y poblar la base de datos
php artisan migrate --force --seed

# Regenerar documentacion Swagger
php artisan l5-swagger:generate || true

echo "Servidor listo en el puerto $PORT_HTTP"

# Iniciar Apache en primer plano
exec apache2-foreground
