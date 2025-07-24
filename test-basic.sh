#!/bin/bash

echo "🟢 TEST BASIC - Application Vue.js avec Debug"
echo "============================================="

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec BasicTest..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-ytpSsg--.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JavaScript: $JS_STATUS"
echo "   CSS: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-ytpSsg--.js"; then
    echo "   ✅ JavaScript correctement référencé"
else
    echo "   ❌ JavaScript non trouvé dans la page"
fi

# Test du script de debug
echo ""
echo "🔧 Vérification du script de debug..."
if curl -s http://localhost:8000 | grep -q "VUE.JS NE SE MONTE PAS"; then
    echo "   ✅ Script de debug de fallback présent"
else
    echo "   ❌ Script de debug de fallback absent"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir d'abord l'écran bleu de chargement"
echo "3. Puis un ÉCRAN VERT avec '🟢 BASIC TEST VUE.JS' (si Vue.js fonctionne)"
echo "4. OU un ÉCRAN ORANGE avec '🟠 VUE.JS NE SE MONTE PAS' (si problème)"
echo "5. Une alerte devrait apparaître si Vue.js fonctionne"
echo ""
echo "🔧 Si vous ne voyez rien :"
echo "   - Ouvrez la console (F12) et vérifiez les messages de debug"
echo "   - Attendez 5 secondes pour voir le fallback orange"
echo "   - Videz le cache du navigateur (Ctrl+F5)"
echo "   - Vérifiez que l'URL est bien http://localhost:8000"
echo ""
echo "📋 Messages de debug attendus dans la console :"
echo "   - '🔍 Debug: Page loaded'"
echo "   - '🔍 Debug: Vue app element: [object HTMLDivElement]'"
echo "   - '🔍 Debug: Loading screen: [object HTMLDivElement]'"
echo "   - '🔍 Debug: After 2 seconds'"
echo "   - '🔍 Debug: After 5 seconds'"
echo "   - '🟢 BasicTest component mounted!' (si Vue.js fonctionne)"
echo "   - '🔍 Debug: Vue.js ne semble pas se monter' (si problème)" 