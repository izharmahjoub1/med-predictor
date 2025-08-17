#!/bin/bash

# 🏆 Script de restauration du portail joueur final intégré
# 📅 Créé le 15 Août 2025
# 🎯 Restaure la version finale avec logos des clubs FTF

echo "🏆 RESTAURATION DU PORTAL JOUEUR FINAL INTÉGRÉ"
echo "=============================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "portail-joueur-final-INTEGRE.blade.php" ]; then
    echo "❌ Erreur : Ce script doit être exécuté depuis le dossier de sauvegarde"
    echo "📍 Répertoire actuel : $(pwd)"
    exit 1
fi

# Créer une sauvegarde de la version actuelle
echo "📦 Création d'une sauvegarde de la version actuelle..."
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_DIR="../portail-joueur-backup-${TIMESTAMP}"
mkdir -p "$BACKUP_DIR"

if [ -f "../resources/views/portail-joueur-final-corrige-dynamique.blade.php" ]; then
    cp "../resources/views/portail-joueur-final-corrige-dynamique.blade.php" "$BACKUP_DIR/"
    echo "✅ Portail actuel sauvegardé dans : $BACKUP_DIR"
else
    echo "⚠️  Aucun portail actuel trouvé à sauvegarder"
fi

# Restaurer le portail principal
echo ""
echo "🔄 Restauration du portail principal..."
cp "portail-joueur-final-INTEGRE.blade.php" "../resources/views/portail-joueur-final-corrige-dynamique.blade.php"
echo "✅ Portail principal restauré"

# Restaurer le composant club-logo-working
echo ""
echo "🔄 Restauration du composant club-logo-working..."
cp "club-logo-working.blade.php" "../resources/views/components/"
echo "✅ Composant club-logo-working restauré"

# Restaurer les logos des clubs
echo ""
echo "🔄 Restauration des logos des clubs..."
if [ -d "clubs" ]; then
    cp -r "clubs" "../public/"
    echo "✅ Logos des clubs restaurés"
else
    echo "⚠️  Dossier des logos non trouvé"
fi

# Mettre à jour les permissions
echo ""
echo "🔐 Mise à jour des permissions..."
chmod 644 "../resources/views/portail-joueur-final-corrige-dynamique.blade.php"
chmod 644 "../resources/views/components/club-logo-working.blade.php"
chmod -R 644 "../public/clubs/" 2>/dev/null || true
echo "✅ Permissions mises à jour"

# Vider le cache Laravel
echo ""
echo "🧹 Nettoyage du cache Laravel..."
cd ..
php artisan cache:clear 2>/dev/null || echo "⚠️  Cache Laravel non accessible"
php artisan view:clear 2>/dev/null || echo "⚠️  Vues Laravel non accessibles"
echo "✅ Cache nettoyé"

# Vérification finale
echo ""
echo "🔍 Vérification finale..."
if [ -f "resources/views/portail-joueur-final-corrige-dynamique.blade.php" ]; then
    echo "✅ Portail principal : OK"
else
    echo "❌ Portail principal : MANQUANT"
fi

if [ -f "resources/views/components/club-logo-working.blade.php" ]; then
    echo "✅ Composant club-logo-working : OK"
else
    echo "❌ Composant club-logo-working : MANQUANT"
fi

if [ -d "public/clubs" ]; then
    CLUB_COUNT=$(find "public/clubs" -name "*.webp" | wc -l)
    echo "✅ Logos des clubs : $CLUB_COUNT fichiers trouvés"
else
    echo "❌ Logos des clubs : DOSSIER MANQUANT"
fi

echo ""
echo "🎉 RESTAURATION TERMINÉE !"
echo ""
echo "📋 Résumé :"
echo "   • Portail principal restauré"
echo "   • Composant club-logo-working restauré"
echo "   • Logos des clubs restaurés"
echo "   • Cache Laravel nettoyé"
echo ""
echo "🧪 Tests recommandés :"
echo "   • Visiter /test-portail-principal/135 (JS Kairouan)"
echo "   • Visiter /test-portail-principal/92 (CS Sfaxien)"
echo "   • Visiter /test-portail-principal/131 (Espérance de Tunis)"
echo ""
echo "📁 Sauvegarde de l'ancienne version : $BACKUP_DIR"
echo ""
echo "🚀 Le portail joueur est maintenant intégré avec les logos des clubs FTF !"

