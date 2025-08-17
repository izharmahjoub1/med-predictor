#!/bin/bash

# 🔧 SCRIPT DE RESTAURATION - CORRECTION DE L'ÂGE
# Restaure la correction qui supprime les décimales de l'âge

echo "🔧 RESTAURATION DE LA CORRECTION DE L'ÂGE"
echo "=========================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "app/Models/Player.php" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet"
    exit 1
fi

# Créer une sauvegarde de la version actuelle
echo "📦 Création d'une sauvegarde de la version actuelle..."
cp app/Models/Player.php app/Models/Player.php.backup-$(date +%Y%m%d-%H%M%S)
cp resources/views/portail-joueur-final-corrige-dynamique.blade.php resources/views/portail-joueur-final-corrige-dynamique.blade.php.backup-$(date +%Y%m%d-%H%M%S)

echo "✅ Sauvegarde créée"

# Restaurer les fichiers corrigés
echo "🔄 Restauration des fichiers corrigés..."
cp Player.php app/Models/
cp portail-joueur-final-corrige-dynamique.blade.php resources/views/

echo "✅ Fichiers restaurés"

# Vérifier les permissions
echo "🔐 Mise à jour des permissions..."
chmod 644 app/Models/Player.php
chmod 644 resources/views/portail-joueur-final-corrige-dynamique.blade.php

echo "✅ Permissions mises à jour"

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear

echo "✅ Cache nettoyé"

echo ""
echo "🎉 CORRECTION DE L'ÂGE RESTAURÉE AVEC SUCCÈS !"
echo ""
echo "📋 Ce qui a été restauré :"
echo "   • Accesseur getAgeAttribute() avec cast (int)"
echo "   • Affichage de l'âge sans décimales dans le portail"
echo ""
echo "🧪 Pour tester :"
echo "   • Accédez au portail d'un joueur"
echo "   • Vérifiez que l'âge s'affiche sans décimales"
echo ""
echo "📁 Sauvegardes créées dans :"
echo "   • app/Models/Player.php.backup-*"
echo "   • resources/views/portail-joueur-final-corrige-dynamique.blade.php.backup-*"

