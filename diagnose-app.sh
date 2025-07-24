#!/bin/bash

echo "🔍 Diagnostic de l'Application FIFA Vue.js"
echo "=========================================="

# Vérifier les processus
echo ""
echo "📊 Processus en cours :"
if pgrep -f "php artisan serve" > /dev/null; then
    echo "✅ Serveur Laravel actif"
else
    echo "❌ Serveur Laravel inactif"
fi

if pgrep -f "vite" > /dev/null; then
    echo "✅ Serveur Vite actif"
else
    echo "ℹ️  Serveur Vite inactif (normal en production)"
fi

# Vérifier les fichiers d'assets
echo ""
echo "📁 Assets construits :"
if [ -f "public/build/assets/app-D365NUng.js" ]; then
    echo "✅ JavaScript construit : app-D365NUng.js"
else
    echo "❌ JavaScript construit manquant"
fi

if [ -f "public/build/assets/app-D2oWiM6l.css" ]; then
    echo "✅ CSS construit : app-D2oWiM6l.css"
else
    echo "❌ CSS construit manquant"
fi

# Tester les URLs
echo ""
echo "🌐 Test des URLs :"

URLS=(
    "http://localhost:8000"
    "http://localhost:8000/simple-test"
    "http://localhost:8000/test"
    "http://localhost:8000/test-simple"
)

for url in "${URLS[@]}"; do
    status=$(curl -s -o /dev/null -w "%{http_code}" "$url")
    if [ "$status" = "200" ]; then
        echo "✅ $url (HTTP $status)"
    else
        echo "❌ $url (HTTP $status)"
    fi
done

# Vérifier les assets
echo ""
echo "📦 Test des assets :"
ASSETS=(
    "http://localhost:8000/build/assets/app-D365NUng.js"
    "http://localhost:8000/build/assets/app-D2oWiM6l.css"
)

for asset in "${ASSETS[@]}"; do
    status=$(curl -s -o /dev/null -w "%{http_code}" "$asset")
    if [ "$status" = "200" ]; then
        echo "✅ $asset (HTTP $status)"
    else
        echo "❌ $asset (HTTP $status)"
    fi
done

# Résumé
echo ""
echo "📋 Résumé :"
echo "• Accédez à http://localhost:8000/simple-test pour tester l'application Vue.js"
echo "• Accédez à http://localhost:8000 pour l'application principale"
echo "• Accédez à http://localhost:8000/test pour la page de test FIFA"

echo ""
echo "🔧 Si l'application ne s'affiche pas :"
echo "1. Vérifiez la console du navigateur (F12)"
echo "2. Vérifiez que les assets se chargent correctement"
echo "3. Essayez de vider le cache du navigateur"
echo "4. Redémarrez avec : ./start-fifa-app.sh" 