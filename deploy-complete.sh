#!/bin/bash

echo "🚀 Déploiement complet vers le serveur distant..."

# Vérifier que l'application locale fonctionne
echo "=== Vérification de l'application locale ==="
if curl -s http://localhost:8000 > /dev/null; then
    echo "✅ Application locale fonctionnelle"
else
    echo "❌ Application locale ne fonctionne pas"
    exit 1
fi

echo "=== Création de l'archive complète ==="
# Créer une archive de tout le projet (sans node_modules et vendor)
tar --exclude='node_modules' --exclude='vendor' --exclude='.git' --exclude='storage/logs/*' --exclude='storage/framework/cache/*' -czf med-predictor-complete.tar.gz .

echo "=== Copie vers le serveur distant ==="
scp med-predictor-complete.tar.gz fit@34.155.231.255:/tmp/

echo "=== Déploiement sur le serveur distant ==="
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
    
    echo "=== Installation des dépendances ==="
    composer install --no-dev --optimize-autoloader
    
    echo "=== Configuration de l'environnement ==="
    cp .env.example .env
    php artisan key:generate --force
    
    # Configuration identique à la locale
    sed -i 's/APP_ENV=.*/APP_ENV=local/' .env
    sed -i 's/APP_DEBUG=.*/APP_DEBUG=true/' .env
    sed -i 's/APP_URL=.*/APP_URL=http:\/\/34.155.231.255/' .env
    
    echo "=== Compilation des assets ==="
    npm install
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
    
    echo "✅ Déploiement complet terminé!"
EOF

# Nettoyer l'archive locale
rm med-predictor-complete.tar.gz

echo "✅ Déploiement terminé!"
echo "🌐 Testez maintenant: http://34.155.231.255" 