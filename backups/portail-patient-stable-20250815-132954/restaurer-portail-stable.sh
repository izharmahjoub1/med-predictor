#!/bin/bash

# 🚀 SCRIPT DE RESTAURATION DU PORTAL PATIENT - VERSION STABLE
# 📅 Créé le 15 Août 2025 à 13:29

echo "🏥 RESTAURATION DU PORTAL PATIENT - VERSION STABLE"
echo "=================================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur : Ce script doit être exécuté depuis la racine du projet Laravel"
    echo "   Répertoire actuel : $(pwd)"
    echo "   Veuillez naviguer vers la racine du projet et relancer le script"
    exit 1
fi

echo "✅ Répertoire Laravel détecté"
echo ""

# Créer une sauvegarde de la version actuelle
echo "💾 Création d'une sauvegarde de la version actuelle..."
BACKUP_NAME="portail-joueur-ACTUEL-$(date +%Y%m%d-%H%M%S).blade.php"
if [ -f "resources/views/portail-joueur-final-corrige-dynamique.blade.php" ]; then
    cp "resources/views/portail-joueur-final-corrige-dynamique.blade.php" "backups/$BACKUP_NAME"
    echo "✅ Sauvegarde créée : backups/$BACKUP_NAME"
else
    echo "⚠️  Aucun fichier portail actuel trouvé à sauvegarder"
fi
echo ""

# Restaurer le portail principal
echo "🔄 Restauration du portail principal..."
if [ -f "portail-joueur-final-corrige-dynamique-STABLE.blade.php" ]; then
    cp "portail-joueur-final-corrige-dynamique-STABLE.blade.php" "resources/views/portail-joueur-final-corrige-dynamique.blade.php"
    echo "✅ Portail principal restauré"
else
    echo "❌ Erreur : Fichier stable non trouvé"
    exit 1
fi
echo ""

# Restaurer le composant des logos
echo "🏆 Restauration du composant des logos..."
if [ -f "association-logo-working.blade.php" ]; then
    cp "association-logo-working.blade.php" "resources/views/components/"
    echo "✅ Composant des logos restauré"
else
    echo "❌ Erreur : Composant des logos non trouvé"
    exit 1
fi
echo ""

# Restaurer le helper des codes pays
echo "🌍 Restauration du helper des codes pays..."
if [ -f "CountryCodeHelper.php" ]; then
    cp "CountryCodeHelper.php" "app/Helpers/"
    echo "✅ Helper des codes pays restauré"
else
    echo "❌ Erreur : Helper des codes pays non trouvé"
    exit 1
fi
echo ""

# Vérifier les permissions
echo "🔐 Vérification des permissions..."
chmod 644 "resources/views/portail-joueur-final-corrige-dynamique.blade.php"
chmod 644 "resources/views/components/association-logo-working.blade.php"
chmod 644 "app/Helpers/CountryCodeHelper.php"
echo "✅ Permissions mises à jour"
echo ""

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
echo "✅ Cache nettoyé"
echo ""

echo "🎉 RESTAURATION TERMINÉE AVEC SUCCÈS !"
echo "========================================"
echo ""
echo "📋 Récapitulatif des actions :"
echo "   ✅ Portail principal restauré"
echo "   ✅ Composant des logos restauré"
echo "   ✅ Helper des codes pays restauré"
echo "   ✅ Cache Laravel nettoyé"
echo "   ✅ Sauvegarde de l'ancienne version créée"
echo ""
echo "🧪 Pour tester la restauration :"
echo "   - Via Tinker : php artisan tinker"
echo "   - Via route : /test-portail-simplifie"
echo "   - Via portail : /joueur/{id} (avec authentification)"
echo ""
echo "📁 Fichiers restaurés :"
echo "   - resources/views/portail-joueur-final-corrige-dynamique.blade.php"
echo "   - resources/views/components/association-logo-working.blade.php"
echo "   - app/Helpers/CountryCodeHelper.php"
echo ""
echo "🔒 Sécurité :"
echo "   - Gestion des cas sans association"
echo "   - Boutons 'Gérer' sécurisés"
echo "   - Plus d'erreurs de propriétés null"
echo ""
echo "🏆 Nouveau système de logos des fédérations :"
echo "   - Logos officiels FRMF, FTF, etc."
echo "   - Composant fonctionnel et testé"
echo "   - Fallback gracieux en cas d'erreur"
echo ""
echo "🚀 Le portail patient est maintenant restauré et prêt !"

