#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/srv/learning-hub"
SRC_DIR="$(cd "$(dirname "$0")" && pwd)"

if [[ $EUID -ne 0 ]]; then
  echo "Vui lòng chạy với quyền root: sudo ./install_debian.sh"
  exit 1
fi

echo "=== Cài đặt gói PHP 8.4 / Nginx / SQLite3 trên Debian ==="
apt update
apt install -y nginx php-fpm php-sqlite3 php-gd php-mbstring sqlite3 rsync unzip

PHP_VER="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
PHP_FPM_SERVICE="php${PHP_VER}-fpm"
PHP_FPM_SOCKET="/run/php/php${PHP_VER}-fpm.sock"

echo "=== Đồng bộ mã nguồn ứng dụng vào $APP_DIR ==="
mkdir -p "$APP_DIR"
rsync -a --delete "$SRC_DIR/" "$APP_DIR/"

mkdir -p "$APP_DIR/database" "$APP_DIR/public/uploads" "$APP_DIR/data"
chown -R www-data:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR/database" "$APP_DIR/public/uploads" "$APP_DIR/data"

echo "=== Cấu hình Nginx Virtual Host ==="
cat > /etc/nginx/sites-available/learning-hub <<NGINX
server {
    listen 80;
    server_name _;

    root ${APP_DIR}/public;
    index index.php index.html;

    client_max_body_size 64M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCKET};
        fastcgi_read_timeout 300;
    }

    # Stream media files efficiently
    location /data/ {
        alias ${APP_DIR}/data/;
        autoindex off;
        add_header Accept-Ranges bytes;
    }

    location ~ /\. {
        deny all;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/learning-hub /etc/nginx/sites-enabled/learning-hub
rm -f /etc/nginx/sites-enabled/default

systemctl enable --now nginx "$PHP_FPM_SERVICE"
nginx -t
systemctl reload nginx

echo
echo "=========================================================="
echo " Cài đặt hoàn tất! PHP-FPM: ${PHP_FPM_SERVICE} (${PHP_FPM_SOCKET})"
echo " Truy cập trình duyệt: http://SERVER_IP/"
echo "=========================================================="
