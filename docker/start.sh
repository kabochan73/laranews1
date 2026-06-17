#!/bin/bash
set -e

mkdir -p /var/log/supervisor

php /var/www/artisan migrate --force
php /var/www/artisan config:cache
php /var/www/artisan route:cache

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
