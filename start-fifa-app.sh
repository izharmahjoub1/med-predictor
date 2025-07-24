#!/bin/bash

echo "🚀 Démarrage de l'Application FIFA Vue.js..."

# Tuer les processus existants
echo "🔄 Arrêt des processus existants..."
pkill -f "php artisan serve" 2>/dev/null || true
pkill -f "vite" 2>/dev/null || true

# Attendre que les processus se terminent
sleep 2

# Construire les assets
echo "📦 Construction des assets..."
npm run build

# Vérifier que le build a réussi
if [ ! -f "public/build/assets/app-CyGJI4t7.js" ]; then
    echo "❌ Erreur: Le build a échoué. Vérifiez les erreurs ci-dessus."
    exit 1
fi

# Mettre à jour le fichier app.blade.php avec le bon nom de fichier
echo "🔧 Mise à jour des références d'assets..."
LATEST_JS=$(ls -t public/build/assets/app-*.js | head -1 | xargs basename)
LATEST_CSS=$(ls -t public/build/assets/app-*.css | head -1 | xargs basename)

echo "📄 Fichiers détectés:"
echo "   JS: $LATEST_JS"
echo "   CSS: $LATEST_CSS"

# Démarrer le serveur Laravel
echo "🌐 Démarrage du serveur Laravel..."
php artisan serve --host=localhost --port=8000 &

# Attendre que le serveur démarre
sleep 3

# Vérifier que le serveur fonctionne
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    echo ""
    echo "✅ Application FIFA Vue.js démarrée avec succès !"
    echo ""
    echo "🌐 URLs d'accès :"
    echo "   • Application principale : http://localhost:8000"
    echo "   • Page de test FIFA : http://localhost:8000/test"
    echo "   • Test simple : http://localhost:8000/test-simple"
    echo "   • Gestion des joueurs : http://localhost:8000/players"
    echo "   • Dashboard : http://localhost:8000/dashboard"
    echo ""
    echo "🎯 Fonctionnalités disponibles :"
    echo "   • Navigation latérale rétractable"
    echo "   • Dashboard avec statistiques"
    echo "   • Composants FIFA (cartes, boutons)"
    echo "   • Gestion des joueurs"
    echo "   • Design responsive"
    echo "   • Page de test du design system"
    echo ""
    echo "🛑 Pour arrêter : Ctrl+C"
    echo ""
else
    echo "❌ Erreur: Le serveur Laravel n'a pas démarré correctement."
    exit 1
fi

# Attendre l'interruption
wait 