#!/bin/bash

echo "🔴 TEST FORCE DISPLAY - Application Vue.js"
echo "=========================================="

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec ForceDisplay..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-Dff0ca8m.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JavaScript: $JS_STATUS"
echo "   CSS: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-Dff0ca8m.js"; then
    echo "   ✅ JavaScript correctement référencé"
else
    echo "   ❌ JavaScript non trouvé dans la page"
fi

# Test de l'écran de chargement
echo ""
echo "🎯 Vérification de l'écran de chargement..."
if curl -s http://localhost:8000 | grep -q "fifa-loading"; then
    echo "   ✅ Écran de chargement présent"
else
    echo "   ❌ Écran de chargement absent"
fi

# Test du script de debug
echo ""
echo "🔧 Vérification du script de debug..."
if curl -s http://localhost:8000 | grep -q "Debug: Page loaded"; then
    echo "   ✅ Script de debug présent"
else
    echo "   ❌ Script de debug absent"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir d'abord l'écran bleu de chargement"
echo "3. Puis un ÉCRAN ROUGE PLEIN avec '🔴 FORCE DISPLAY VUE.JS'"
echo "4. Une alerte devrait apparaître : 'Vue.js fonctionne ! Composant ForceDisplay monté.'"
echo "5. Le bouton 'CLICK ME' devrait incrémenter un compteur"
echo "6. Un timestamp et l'URL devraient s'afficher"
echo ""
echo "🔧 Si vous ne voyez rien :"
echo "   - Ouvrez la console (F12) et vérifiez les messages de debug"
echo "   - Videz le cache du navigateur (Ctrl+F5)"
echo "   - Vérifiez que l'URL est bien http://localhost:8000"
echo ""
echo "📋 Messages de debug attendus dans la console :"
echo "   - '🔍 Debug: Page loaded'"
echo "   - '🔍 Debug: Vue app element: [object HTMLDivElement]'"
echo "   - '🔍 Debug: Loading screen: [object HTMLDivElement]'"
echo "   - '🔴 ForceDisplay component mounted!'" 