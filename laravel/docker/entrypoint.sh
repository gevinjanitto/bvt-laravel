#!/bin/bash
set -e

PORT="${PORT:-8080}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ -z "$APP_KEY" ]; then
  echo "WARNING: APP_KEY is not set. Generating a temporary key (sessions reset on every deploy). Set APP_KEY in Railway variables."
  export APP_KEY="$(php artisan key:generate --show)"
fi

php artisan storage:link --force || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
  if [ "${RUN_SEED:-true}" = "true" ]; then
    php artisan db:seed --force
  fi
fi

chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground
