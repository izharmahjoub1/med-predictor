#!/bin/bash

echo "🔧 Mise à jour de la configuration Nginx pour PHP 8.2..."

# Mise à jour de la configuration Nginx
sudo tee /etc/nginx/sites-available/laravel > /dev/null << 'EOF'
server {
    listen 80;
    server_name _;
    root /opt/laravel-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
EOF

# Redémarrage de Nginx
sudo systemctl restart nginx

echo "✅ Configuration Nginx mise à jour pour PHP 8.2" 