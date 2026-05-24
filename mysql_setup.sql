svitechkl@gmail.com
jksvismy@gmail.com
svitech.com.my
svi.tech.my@gmail.com


sudo usermod -a -G www-data suadmin;
sudo chown -R suadmin:www-data /var/www/html/danahibah;
sudo chmod -R 775 /var/www/html/danahibah/;
sudo nginx -t
  607  cat /etc/nginx/sites-available/plantsense.conf
  608  sudo mv /tmp/ssl/* /etc/ssl/
  609  cd /etc/ssl/

sudo cp /etc/nginx/sites-available/plantsense.conf /etc/nginx/sites-available/danahibah.conf
sudo ln -s /etc/nginx/sites-available/danahibah.dxtrace.com /etc/nginx/sites-enabled/

server {
    listen 80;
    server_name danahibah.dxtrace.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name danahibah.dxtrace.com;

    root /var/www/html/danahibah;
    index index.php index.html;

    ssl_certificate /etc/ssl/dxtrace.crt;
    ssl_certificate_key /etc/ssl/dxtrace.key;

    location /app/public/ {
        try_files $uri $uri/ @public_rewrite;
    }

    location @public_rewrite {
        rewrite ^/app/public/(.*)$ /app/public/index.php?url=$1 last;
    }

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
 }
}

CREATE DATABASE danahibah_db;
USE danahibah_db;

CREATE USER 'dhuser'@'%' IDENTIFIED BY 'Chennai@2026';
GRANT ALL PRIVILEGES ON danahibah_db.* TO 'dhuser'@'%';
CREATE USER 'dhuser'@'localhost' IDENTIFIED BY 'Chennai@2026';
GRANT ALL PRIVILEGES ON danahibah_db.* TO 'dhuser'@'localhost';

FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS danahibah_db;

DROP USER IF EXISTS 'dhuser'@'localhost';
DROP USER IF EXISTS 'dhuser'@'%';

CREATE USER 'dhuser'@'localhost' IDENTIFIED BY 'Chennai@2026';
CREATE USER 'dhuser'@'%' IDENTIFIED BY 'Chennai@2026';

GRANT ALL PRIVILEGES ON danahibah_db.* TO 'dhuser'@'localhost';
GRANT ALL PRIVILEGES ON danahibah_db.* TO 'dhuser'@'%';

FLUSH PRIVILEGES;

ttps://danahibah.dxtrace.com/login.php
https://danahibah.dxtrace.com/
suadmin
admin
comm
mgmt