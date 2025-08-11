#!/bin/bash

echo "🔧 Configuration automatique de l'instance Laravel..."
echo ""

# Mise à jour du système
echo "=== Mise à jour du système ==="
sudo apt-get update
sudo apt-get upgrade -y

# Installation des dépendances système
echo "=== Installation des dépendances ==="
sudo apt-get install -y software-properties-common curl wget unzip git

# Installation PHP 8.1
echo "=== Installation PHP 8.1 ==="
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update
sudo apt-get install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl php8.1-cli

# Installation Nginx
echo "=== Installation Nginx ==="
sudo apt-get install -y nginx

# Installation Composer
echo "=== Installation Composer ==="
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Installation Node.js
echo "=== Installation Node.js ==="
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Création du répertoire de l'application
echo "=== Configuration des répertoires ==="
sudo mkdir -p /opt/laravel-app
sudo chown -R www-data:www-data /opt/laravel-app

# Configuration Nginx
echo "=== Configuration Nginx ==="
sudo tee /etc/nginx/sites-available/laravel > /dev/null << 'EOF'
server {
    listen 80;
    server_name fit.tbhc.uk;
    root /opt/laravel-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo systemctl restart nginx

# Configuration du service Laravel
echo "=== Configuration du service Laravel ==="
sudo tee /etc/systemd/system/laravel-app.service > /dev/null << 'EOF'
[Unit]
Description=Laravel Application
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/opt/laravel-app
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable laravel-app

# Configuration des permissions
echo "=== Configuration des permissions ==="
sudo chown -R www-data:www-data /opt/laravel-app
sudo chmod -R 755 /opt/laravel-app

echo ""
echo "✅ Configuration terminée !"
echo ""
echo "=== Prochaines étapes ==="
echo "1. Déployez l'application depuis votre machine locale"
echo "2. Configurez le fichier .env"
echo "3. Installez les dépendances Composer et npm"
echo "4. Compilez les assets"
echo "5. Démarrez le service Laravel"
echo ""
echo "=== Commandes utiles ==="
echo "sudo systemctl status laravel-app"
echo "sudo systemctl status nginx"
echo "sudo journalctl -u laravel-app -f" 