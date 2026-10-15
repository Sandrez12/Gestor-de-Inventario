#!/bin/sh
set -e

# Render (y otros proveedores) indican el puerto por la variable PORT.
PORT="${PORT:-80}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Sin APP_KEY se genera una temporal (las sesiones se reinician con cada despliegue).
if [ -z "${APP_KEY}" ]; then
  export APP_KEY="$(php artisan key:generate --show --no-interaction)"
fi

# Base de datos SQLite: el archivo debe pertenecer al usuario que ejecuta Apache.
if [ "${DB_CONNECTION}" = "sqlite" ]; then
  DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  mkdir -p "$(dirname "${DB_FILE}")"
  [ -f "${DB_FILE}" ] || touch "${DB_FILE}"
  chown -R www-data:www-data "$(dirname "${DB_FILE}")" storage bootstrap/cache
fi

as_www() { runuser -u www-data -- "$@"; }

as_www php artisan migrate --force --no-interaction

# Modo demostración: carga datos de ejemplo (idempotente) y habilita el acceso con un clic.
if [ "${DEMO_MODE}" = "true" ]; then
  as_www php artisan db:seed --force --no-interaction
fi

as_www php artisan config:cache --no-interaction
as_www php artisan route:cache --no-interaction
as_www php artisan view:cache --no-interaction

exec apache2-foreground
