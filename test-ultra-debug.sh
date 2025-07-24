#!/bin/bash

echo "🔴 TEST ULTRA DEBUG - Application Vue.js"
echo "========================================"

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec UltraDebug..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-DOmAixCj.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JavaScript: $JS_STATUS"
echo "   CSS: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-DOmAixCj.js"; then
    echo "   ✅ JavaScript correctement référencé"
else
    echo "   ❌ JavaScript non trouvé dans la page"
fi

# Test de l'écran de chargement
echo ""
echo "🎯 Vérification de l'écran de chargement..."
if curl -s http://localhost:8000 | grep -q 'style="display: none;"'; then
    echo "   ✅ Écran de chargement masqué"
else
    echo "   ❌ Écran de chargement visible"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir un ÉCRAN ROUGE PLEIN avec '🔴 ULTRA DEBUG VUE.JS'"
echo "3. Une alerte devrait apparaître : 'Vue.js fonctionne ! Composant UltraDebug monté.'"
echo "4. Le bouton 'CLICK ME' devrait incrémenter un compteur"
echo "5. Un timestamp devrait s'afficher et se mettre à jour"
echo ""
echo "🔧 Si vous ne voyez pas l'écran rouge :"
echo "   - Videz le cache du navigateur (Ctrl+F5)"
echo "   - Ouvrez la console (F12) et vérifiez les erreurs"
echo "   - Vérifiez que l'URL est bien http://localhost:8000"
echo "   - L'écran rouge doit couvrir TOUT l'écran (position: fixed)" 