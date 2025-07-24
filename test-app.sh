#!/bin/bash

echo "🧪 Test de l'Application FIFA Vue.js..."

# Test de la page principale
echo "📄 Test de la page principale..."
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000)
echo "   Status: $MAIN_STATUS"

# Test de la page de test
echo "📄 Test de la page de test FIFA..."
TEST_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/test)
echo "   Status: $TEST_STATUS"

# Test de la page de test simple
echo "📄 Test de la page de test simple..."
SIMPLE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/test-simple)
echo "   Status: $SIMPLE_STATUS"

# Test des assets
echo "📄 Test des assets..."
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-CyGJI4t7.js)
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app-D2oWiM6l.css)
echo "   JS Status: $JS_STATUS"
echo "   CSS Status: $CSS_STATUS"

# Résumé
echo ""
echo "📊 Résumé des tests:"
if [ "$MAIN_STATUS" = "200" ] && [ "$TEST_STATUS" = "200" ] && [ "$SIMPLE_STATUS" = "200" ] && [ "$JS_STATUS" = "200" ] && [ "$CSS_STATUS" = "200" ]; then
    echo "✅ Tous les tests sont passés ! L'application fonctionne correctement."
    echo ""
    echo "🎉 Vous pouvez maintenant accéder à :"
    echo "   • http://localhost:8000"
    echo "   • http://localhost:8000/test"
    echo "   • http://localhost:8000/test-simple"
else
    echo "❌ Certains tests ont échoué. Vérifiez les erreurs ci-dessus."
fi 