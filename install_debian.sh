#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/srv/sao-he"
SRC_DIR="$(cd "$(dirname "$0")" && pwd)"

if [[ $EUID -ne 0 ]]; then
  echo "Please run as root: sudo ./install_debian.sh"
  exit 1
fi

apt update
apt install -y nginx php-fpm php-sqlite3 php-gd php-mbstring sqlite3 rsync unzip

PHP_VER="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
PHP_FPM_SERVICE="php${PHP_VER}-fpm"
PHP_FPM_SOCKET="/run/php/php${PHP_VER}-fpm.sock"

mkdir -p "$APP_DIR"
rsync -a --delete "$SRC_DIR/" "$APP_DIR/"

chown -R www-data:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR/database" "$APP_DIR/public/uploads" "$APP_DIR/backup"

cat > /etc/nginx/sites-available/sao-he <<NGINX
server {
    listen 80;
    server_name _;

    root /srv/sao-he/public;
    index index.php index.html;

    client_max_body_size 8M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCKET};
    }

    location ~ /\. {
        deny all;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/sao-he /etc/nginx/sites-enabled/sao-he
rm -f /etc/nginx/sites-enabled/default

systemctl enable --now nginx "$PHP_FPM_SERVICE"
nginx -t
systemctl reload nginx

echo
echo "Done. PHP-FPM: ${PHP_FPM_SERVICE}, socket: ${PHP_FPM_SOCKET}"
echo "Open: http://SERVER_IP/"
