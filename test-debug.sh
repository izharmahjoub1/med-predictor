#!/bin/bash

echo "🔴 Test de l'Application Vue.js Debug"
echo "===================================="

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec DebugTest..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-CxxEsHer.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JavaScript: $JS_STATUS"
echo "   CSS: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-CxxEsHer.js"; then
    echo "   ✅ JavaScript correctement référencé"
else
    echo "   ❌ JavaScript non trouvé dans la page"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir un message rouge '🔴 DEBUG VUE.JS'"
echo "3. Une alerte devrait apparaître : 'Vue.js fonctionne !'"
echo "4. Le bouton 'CLICK ME' devrait incrémenter un compteur"
echo ""
echo "🔧 Si vous ne voyez pas le message rouge :"
echo "   - Videz le cache du navigateur (Ctrl+F5)"
echo "   - Ouvrez la console (F12) et vérifiez les erreurs"
echo "   - Vérifiez que l'URL est bien http://localhost:8000" 