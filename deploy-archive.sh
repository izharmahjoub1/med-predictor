#!/bin/bash

echo "🚀 Déploiement de l'archive sur le serveur distant..."

ssh fit@34.155.231.255 << 'EOF'
    cd /opt
    
    echo "=== Sauvegarde de l'ancienne version ==="
    if [ -d "laravel-app" ]; then
        mv laravel-app laravel-app-backup-$(date +%Y%m%d-%H%M%S)
    fi
    
    echo "=== Extraction de la nouvelle version ==="
    mkdir laravel-app
    cd laravel-app
    tar -xzf /tmp/med-predictor-complete.tar.gz
    rm /tmp/med-predictor-complete.tar.gz
    
    echo "=== Installation des dépendances PHP ==="
    composer install --no-dev --optimize-autoloader
    
    echo "=== Configuration de l'environnement ==="
    cp .env.example .env
    php artisan key:generate --force
    
    # Configuration identique à la locale
    sed -i 's/APP_ENV=.*/APP_ENV=local/' .env
    sed -i 's/APP_DEBUG=.*/APP_DEBUG=true/' .env
    sed -i 's/APP_URL=.*/APP_URL=http:\/\/34.155.231.255/' .env
    
    echo "=== Installation des dépendances Node.js ==="
    npm install
    
    echo "=== Compilation des assets ==="
    npm run build
    
    echo "=== Configuration des permissions ==="
    sudo chown -R www-data:www-data .
    sudo chmod -R 755 .
    sudo chmod -R 775 storage/
    sudo chmod -R 775 bootstrap/cache/
    
    echo "=== Nettoyage et optimisation ==="
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "=== Redémarrage des services ==="
    sudo systemctl restart laravel-app
    sudo systemctl restart nginx
    
    echo "=== Test de l'application ==="
    sleep 5
    curl -I http://localhost:8000
    curl -s http://localhost:8000 | head -5
    
    echo "✅ Déploiement terminé!"
EOF

echo "✅ Déploiement terminé!"
echo "🌐 Testez maintenant: http://34.155.231.255" 