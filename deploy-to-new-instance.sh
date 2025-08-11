#!/bin/bash

echo "🚀 Déploiement vers la nouvelle instance..."
echo ""

# Nouvelle IP de l'instance
NEW_IP="34.38.85.123"
INSTANCE_NAME="med-predictor-instance"
ZONE="europe-west1-b"

echo "=== Informations de l'instance ==="
echo "Nom: $INSTANCE_NAME"
echo "Zone: $ZONE"
echo "IP: $NEW_IP"
echo ""

# Vérifier que l'archive existe
if [ ! -f "med-predictor-complete.tar.gz" ]; then
    echo "❌ Archive med-predictor-complete.tar.gz non trouvée"
    echo "💡 Créez d'abord l'archive avec: ./deploy-complete.sh"
    exit 1
fi

echo "✅ Archive trouvée: $(du -h med-predictor-complete.tar.gz | cut -f1)"
echo ""

# Copier l'archive vers l'instance
echo "=== Copie de l'archive vers l'instance ==="
echo "Copie en cours..."
gcloud compute scp med-predictor-complete.tar.gz fit@$INSTANCE_NAME:/tmp/ --zone=$ZONE

if [ $? -eq 0 ]; then
    echo "✅ Archive copiée avec succès"
else
    echo "❌ Échec de la copie"
    exit 1
fi

echo ""

# Se connecter à l'instance et déployer
echo "=== Déploiement sur l'instance ==="
echo "Connexion et déploiement en cours..."

gcloud compute ssh fit@$INSTANCE_NAME --zone=$ZONE --command="
echo '=== Extraction de l\'archive ==='
cd /opt/laravel-app
sudo tar -xzf /tmp/med-predictor-complete.tar.gz --strip-components=1
sudo chown -R www-data:www-data /opt/laravel-app

echo '=== Installation des dépendances ==='
composer install --no-dev --optimize-autoloader
npm install
npm run build

echo '=== Configuration Laravel ==='
cp .env.example .env
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo '=== Permissions ==='
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

echo '=== Démarrage du service ==='
sudo systemctl start laravel-app
sudo systemctl status laravel-app

echo '=== Test de l\'application ==='
curl -I http://localhost:8000
curl -I http://localhost

echo '✅ Déploiement terminé !'
"

echo ""
echo "🎉 Déploiement terminé !"
echo ""
echo "=== Test de l'application ==="
echo "Testez maintenant: http://$NEW_IP"
echo ""
echo "=== Commandes utiles ==="
echo "Connexion SSH: gcloud compute ssh fit@$INSTANCE_NAME --zone=$ZONE"
echo "Logs Laravel: sudo journalctl -u laravel-app -f"
echo "Logs Nginx: sudo journalctl -u nginx -f" 