#!/bin/bash

echo "🧹 Nettoyage et configuration de l'instance..."
echo ""

# Nettoyer le terminal
clear

# Créer le répertoire de l'application
echo "=== Création du répertoire de l'application ==="
sudo mkdir -p /opt/laravel-app
sudo chown -R www-data:www-data /opt/laravel-app
sudo chmod -R 755 /opt/laravel-app

echo "✅ Répertoire créé avec succès"
echo ""

# Vérifier l'installation
echo "=== Vérification de l'installation ==="
echo "Services:"
sudo systemctl status nginx --no-pager -l
echo ""
sudo systemctl status php8.1-fpm --no-pager -l
echo ""

echo "Versions:"
php --version
echo ""
composer --version
echo ""
node --version
npm --version
echo ""

echo "Répertoires:"
ls -la /opt/laravel-app/
echo ""

echo "✅ Configuration terminée !"
echo ""
echo "=== Prochaines étapes ==="
echo "1. Revenez sur votre machine locale"
echo "2. Déployez l'application avec: ./deploy-archive.sh"
echo "3. L'application sera accessible sur: http://34.38.85.123" 