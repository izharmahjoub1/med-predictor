#!/bin/bash

echo "🔧 Correction des problèmes de déploiement..."

# 1. Mise à jour de PHP vers 8.2
echo "📦 Mise à jour de PHP vers 8.2..."
sudo apt-get update
sudo apt-get install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update
sudo apt-get install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-json php8.2-tokenizer php8.2-fileinfo php8.2-opcache

# 2. Mise à jour de Node.js vers 20
echo "📦 Mise à jour de Node.js vers 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# 3. Vérification des versions
echo "✅ Vérification des versions..."
php --version
node --version
npm --version

# 4. Correction des permissions
echo "🔐 Correction des permissions..."
sudo chown -R www-data:www-data /opt/laravel-app
sudo chmod -R 755 /opt/laravel-app

# 5. Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
cd /opt/laravel-app
sudo -u www-data composer install --no-dev --optimize-autoloader

# 6. Installation des dépendances Node.js
echo "📦 Installation des dépendances Node.js..."
sudo -u www-data npm install

# 7. Compilation des assets
echo "🔨 Compilation des assets..."
sudo -u www-data npm run build

# 8. Configuration Laravel
echo "⚙️ Configuration Laravel..."
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# 9. Permissions finales
echo "🔐 Permissions finales..."
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# 10. Redémarrage des services
echo "🔄 Redémarrage des services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sudo systemctl restart laravel-app

# 11. Vérification
echo "✅ Vérification finale..."
sudo systemctl status laravel-app
curl -I http://localhost:8000
curl -I http://localhost

echo "🎉 Déploiement terminé !" 