#!/bin/bash

# ========================================
# SCRIPT DE RESTAURATION FIFA PORTAL
# ========================================
# Ce script restaure la sauvegarde FIFA Portal
# ========================================

echo "🔄 DÉBUT DE LA RESTAURATION FIFA PORTAL..."
echo "📁 Dossier de sauvegarde: $(pwd)"
echo ""

# Vérifier que nous sommes dans le bon dossier
if [ ! -f "SAUVEGARDE-DESCRIPTION.txt" ]; then
    echo "❌ ERREUR: Ce script doit être exécuté depuis le dossier de sauvegarde"
    exit 1
fi

# Trouver les fichiers de sauvegarde les plus récents
VUE_SAUVEGARDE=$(ls -t fifa-portal-integrated-*.blade.php | head -1)
CONTROLEUR_SAUVEGARDE=$(ls -t FIFATestController-*.php | head -1)
ROUTES_SAUVEGARDE=$(ls -t web-routes-*.php | head -1)
MODELE_SAUVEGARDE=$(ls -t Player-model-*.php | head -1)
COMPOSANT_SAUVEGARDE=$(ls -t fifa-association-logo-*.blade.php | head -1)

echo "📄 Fichiers de sauvegarde trouvés:"
echo "   Vue: $VUE_SAUVEGARDE"
echo "   Contrôleur: $CONTROLEUR_SAUVEGARDE"
echo "   Routes: $ROUTES_SAUVEGARDE"
echo "   Modèle: $MODELE_SAUVEGARDE"
echo "   Composant: $COMPOSANT_SAUVEGARDE"
echo ""

# Demander confirmation
read -p "⚠️  Êtes-vous sûr de vouloir restaurer cette sauvegarde ? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Restauration annulée"
    exit 1
fi

echo "🚀 Restauration en cours..."

# Restaurer les fichiers
echo "📄 Restauration de la vue principale..."
cp "$VUE_SAUVEGARDE" "../../resources/views/fifa-portal-integrated.blade.php"

echo "🎮 Restauration du contrôleur..."
cp "$CONTROLEUR_SAUVEGARDE" "../../app/Http/Controllers/FIFATestController.php"

echo "🛣️ Restauration des routes..."
cp "$ROUTES_SAUVEGARDE" "../../routes/web.php"

echo "👤 Restauration du modèle..."
cp "$MODELE_SAUVEGARDE" "../../app/Models/Player.php"

echo "🧩 Restauration du composant..."
cp "$COMPOSANT_SAUVEGARDE" "../../resources/views/components/fifa-association-logo.blade.php"

# Vider les caches
echo "🧹 Vidage des caches..."
cd ../..
php artisan view:clear
php artisan route:clear
php artisan cache:clear

echo ""
echo "✅ RESTAURATION TERMINÉE AVEC SUCCÈS !"
echo "🌐 Testez maintenant: http://localhost:8001/fifa-portal"
echo ""
echo "📝 Fichiers restaurés:"
echo "   - Vue principale FIFA Portal"
echo "   - Contrôleur FIFA"
echo "   - Routes web"
echo "   - Modèle Player"
echo "   - Composants Blade"
echo ""
echo "🎯 Le FIFA Portal devrait maintenant être exactement comme dans la sauvegarde !"

