#!/bin/bash

echo "🟢 TEST PROGRESSIVE VUE.JS - Version avec Gestion d'Erreurs"
echo "=========================================================="

# Test de la page principale
echo ""
echo "📄 Test de la page principale avec Progressive Vue.js..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test des assets
echo ""
echo "📦 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-progressive-BDh5zURN.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-progressive-BDnGeHos.css)
echo "   JavaScript Progressive: $JS_STATUS"
echo "   CSS Progressive: $CSS_STATUS"

# Test du contenu de la page
echo ""
echo "🔍 Vérification du contenu..."
if curl -s http://localhost:8000 | grep -q "app-progressive-BDh5zURN.js"; then
    echo "   ✅ JavaScript progressive correctement référencé"
else
    echo "   ❌ JavaScript progressive non trouvé dans la page"
fi

echo ""
echo "🎯 Instructions :"
echo "1. Ouvrez http://localhost:8000 dans votre navigateur"
echo "2. Vous devriez voir d'abord l'écran bleu de chargement"
echo "3. Puis un ÉCRAN VERT avec '🟢 BASIC TEST VUE.JS' (composant de test)"
echo "4. Ouvrez la console (F12) pour voir les messages d'import"
echo ""
echo "🔧 Messages de debug attendus dans la console :"
echo "   - '🟢 Application Vue.js progressive montée avec succès'"
echo "   - '✅ [Composant] importé avec succès' (pour les composants qui existent)"
echo "   - '❌ Erreur import [Composant]: [message]' (pour les composants manquants)"
echo ""
echo "🎯 Résultat attendu :"
echo "   - ÉCRAN VERT = Vue.js fonctionne avec gestion d'erreurs"
echo "   - Messages dans la console = Diagnostic des imports"
echo "   - Navigation fonctionnelle = Router Vue.js opérationnel"
echo ""
echo "📋 URLs à tester :"
echo "   - http://localhost:8000 (page d'accueil avec BasicTest)"
echo "   - http://localhost:8000/dashboard (FifaDashboard ou fallback)"
echo "   - http://localhost:8000/players (PlayersList ou fallback)"
echo "   - http://localhost:8000/test (FifaTestPage ou fallback)"
echo "   - http://localhost:8000/simple-test (SimpleTest)" 