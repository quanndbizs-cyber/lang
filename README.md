# Sao hè - PHP + SQLite cho Debian 12/13

Web app nhỏ để ghi nhận thành tích, cộng/trừ sao, đổi thưởng và upload ảnh minh chứng.

## Cài nhanh

```bash
unzip sao-he-php-debian.zip
cd sao-he-php
sudo ./install_debian.sh
```

Mở trình duyệt:

```text
http://IP_MAY_DEBIAN/
```

Ví dụ:

```text
http://192.168.1.50/
```

## Lý do dùng script mới

Debian 12 thường dùng PHP 8.2, còn Debian 13/Trixie có thể dùng PHP 8.4 hoặc bản PHP mặc định khác. Vì vậy script mới cài package generic:

```bash
sudo apt install -y nginx php-fpm php-sqlite3 php-gd php-mbstring sqlite3 rsync unzip
```

Sau đó tự detect PHP-FPM socket, ví dụ:

```text
/run/php/php8.4-fpm.sock
```

## Cấu trúc sau khi cài

```text
/srv/sao-he/public/index.php
/srv/sao-he/database/
/srv/sao-he/public/uploads/
/srv/sao-he/backup/
```

## Backup dữ liệu

Dữ liệu chính nằm ở:

```text
/srv/sao-he/database/summer.db
/srv/sao-he/public/uploads/
```

Backup nhanh:

```bash
sudo tar -czf /srv/sao-he/backup/sao-he-$(date +%F).tar.gz \
  /srv/sao-he/database/summer.db \
  /srv/sao-he/public/uploads
```

## Kiểm tra lỗi thường gặp

Kiểm tra PHP-FPM:

```bash
systemctl status 'php*-fpm' --no-pager
ls -l /run/php/
```

Kiểm tra Nginx:

```bash
sudo nginx -t
sudo systemctl status nginx --no-pager
```

Log:

```bash
sudo tail -n 100 /var/log/nginx/error.log
```

## Ghi chú bảo mật

Bản này phù hợp chạy trong LAN gia đình. Nếu public ra Internet, nên thêm đăng nhập hoặc Basic Auth qua Nginx.
