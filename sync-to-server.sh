#!/bin/bash

echo "🔄 Synchronisation de la configuration locale vers le serveur distant..."

# Vérifier que l'application locale fonctionne
echo "=== Vérification de l'application locale ==="
if curl -s http://localhost:8000 > /dev/null; then
    echo "✅ Application locale fonctionnelle"
else
    echo "❌ Application locale ne fonctionne pas"
    exit 1
fi

echo "=== Copie des assets compilés ==="
# Créer une archive des assets compilés
tar -czf build-assets.tar.gz -C public build/

echo "=== Copie vers le serveur distant ==="
scp build-assets.tar.gz fit@34.155.231.255:/tmp/

echo "=== Configuration du serveur distant ==="
ssh fit@34.155.231.255 << 'EOF'
    cd /opt/laravel-app
    
    echo "=== Arrêt des services ==="
    sudo systemctl stop laravel-app
    sudo systemctl stop nginx
    
    echo "=== Extraction des assets ==="
    tar -xzf /tmp/build-assets.tar.gz -C public/
    rm /tmp/build-assets.tar.gz
    
    echo "=== Configuration identique à la locale ==="
    # Copier la configuration .env locale
    cp .env.example .env
    php artisan key:generate --force
    
    # Configuration identique à la locale
    sed -i 's/APP_ENV=.*/APP_ENV=local/' .env
    sed -i 's/APP_DEBUG=.*/APP_DEBUG=true/' .env
    sed -i 's/APP_URL=.*/APP_URL=http:\/\/34.155.231.255/' .env
    
    echo "=== Nettoyage des caches ==="
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    
    echo "=== Optimisation ==="
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "=== Correction des permissions ==="
    sudo chown -R www-data:www-data .
    sudo chmod -R 755 .
    sudo chmod -R 775 storage/
    sudo chmod -R 775 bootstrap/cache/
    
    echo "=== Redémarrage des services ==="
    sudo systemctl start laravel-app
    sleep 3
    sudo systemctl start nginx
    
    echo "=== Test de l'application ==="
    curl -I http://localhost:8000
    curl -s http://localhost:8000 | head -5
    
    echo "✅ Configuration synchronisée!"
EOF

# Nettoyer l'archive locale
rm build-assets.tar.gz

echo "✅ Synchronisation terminée!"
echo "🌐 Testez maintenant: http://34.155.231.255" 