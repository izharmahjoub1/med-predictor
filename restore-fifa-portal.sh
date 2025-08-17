#!/bin/bash

echo "🔄 RESTAURATION FIFA PORTAL - SCRIPT DE SAUVEGARDE"
echo "=================================================="

echo "📁 Sauvegardes disponibles :"
ls -la resources/views/fifa-portal-integrated.blade.php*

echo ""
echo "🔧 Options de restauration :"
echo "1. Restaurer la version ORIGINALE (recommandé)"
echo "2. Restaurer la version de sauvegarde"
echo "3. Annuler"

read -p "Choisissez une option (1-3): " choice

case $choice in
    1)
        echo "✅ Restauration de la version ORIGINALE..."
        cp resources/views/fifa-portal-integrated.blade.php.backup-ORIGINAL resources/views/fifa-portal-integrated.blade.php
        echo "✅ FIFA Portal restauré à la version originale !"
        ;;
    2)
        echo "✅ Restauration de la version de sauvegarde..."
        cp resources/views/fifa-portal-integrated.blade.php.backup-20250816-104016 resources/views/fifa-portal-integrated.blade.php
        echo "✅ FIFA Portal restauré à la version de sauvegarde !"
        ;;
    3)
        echo "❌ Restauration annulée."
        ;;
    *)
        echo "❌ Option invalide."
        ;;
esac

echo ""
echo "📋 Statut final :"
ls -la resources/views/fifa-portal-integrated.blade.php
echo ""
echo "🎯 FIFA Portal prêt à utiliser !"

