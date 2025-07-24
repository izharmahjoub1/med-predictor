#!/bin/bash

echo "🟣 TEST SIMPLE VUE.JS - Version Ultra-Simplifiée"
echo "================================================"

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec Simple Vue.js..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-simple-DbsCtBaw.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JavaScript Simple: $JS_STATUS"
echo "   CSS: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-simple-DbsCtBaw.js"; then
    echo "   ✅ JavaScript simple correctement référencé"
else
    echo "   ❌ JavaScript simple non trouvé dans la page"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir d'abord l'écran bleu de chargement"
echo "3. Puis un ÉCRAN VIOLET avec '🟣 VUE.JS SIMPLE TEST' (si Vue.js fonctionne)"
echo "4. Une alerte devrait apparaître si Vue.js fonctionne"
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
echo "   - '🟣 Vue app créée et montée' (si Vue.js fonctionne)"
echo "   - '🟣 SimpleComponent mounted!' (si Vue.js fonctionne)"
echo "   - '🔍 Debug: Vue.js ne semble pas se monter' (si problème)"
echo ""
echo "🎯 Résultat attendu :"
echo "   - ÉCRAN VIOLET = Vue.js fonctionne, on peut corriger l'app principale"
echo "   - ÉCRAN ORANGE = Problème plus profond avec Vue.js"
echo "   - Rien = Problème de chargement des assets" 