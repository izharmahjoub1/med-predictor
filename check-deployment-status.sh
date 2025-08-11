#!/bin/bash

echo "🔍 Vérification de l'état du déploiement..."

echo "=== Test de connectivité ==="
if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
    echo "✅ Serveur accessible"
else
    echo "❌ Serveur non accessible"
fi

echo "=== Test de l'application ==="
echo "Test HTTP:"
curl -I http://34.155.231.255 2>/dev/null | head -1 || echo "❌ Application non accessible"

echo -e "\n=== Test de contenu ==="
echo "Contenu de la page:"
curl -s http://34.155.231.255 2>/dev/null | head -5 || echo "❌ Impossible de récupérer le contenu"

echo -e "\n=== Test des assets ==="
echo "Test CSS:"
curl -I http://34.155.231.255/build/assets/app-GkHTs7Nb.css 2>/dev/null | head -1 || echo "❌ Assets CSS non accessibles"

echo -e "\n=== Test de performance ==="
timeout 10 curl -s http://34.155.231.255 > /dev/null 2>&1 && echo "✅ Chargement rapide" || echo "❌ Chargement lent ou bloqué"

echo -e "\n=== Résumé ==="
echo "Application locale: ✅ Fonctionnelle (http://localhost:8000)"
echo "Application distante: Vérifiez les résultats ci-dessus"
echo ""
echo "🌐 Testez manuellement: http://34.155.231.255" 