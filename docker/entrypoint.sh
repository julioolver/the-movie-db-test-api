#!/bin/sh

echo "Entrypoint script started"

# Ajusta permissões na pasta dbdata
if [ -d /var/lib/postgresql/data ]; then
  echo "Adjusting permissions for /var/lib/postgresql/data"
  chown -R postgres:postgres /var/lib/postgresql/data
  chmod -R 777 /var/lib/postgresql/data
fi
cp .env.example .env
# Verifica se o arquivo composer.json existe
if [ ! -f composer.json ]; then
  echo "Creating new Laravel project"
  composer create-project --prefer-dist laravel/laravel .
  cp .env.example .env
  php artisan key:generate
fi


composer install --optimize-autoloader
php artisan key:generate
php artisan migrate

echo "Adjusting permissions for storage and bootstrap/cache"
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chown -R www:www /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "Adjusting permissions for /var/www to be editable by host user"
chown -R www-data:www-data /var/www
chown -R www:www /var/www
chmod -R 777 /var/www

echo "Executing the original command"
exec "$@"