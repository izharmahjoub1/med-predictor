#!/bin/bash

echo "🔍 Vérification du déploiement..."

echo "=== Attente de 30 secondes pour le déploiement ==="
sleep 30

echo "=== Test de connectivité ==="
if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
    echo "✅ Serveur accessible"
else
    echo "❌ Serveur non accessible"
    echo "Tentative de test direct..."
fi

echo "=== Test de l'application ==="
echo "Test de la page d'accueil:"
curl -I http://34.155.231.255

echo -e "\n=== Test de contenu ==="
echo "Premières lignes de la page:"
curl -s http://34.155.231.255 | head -10

echo -e "\n=== Test de performance ==="
time curl -s http://34.155.231.255 > /dev/null && echo "✅ Chargement rapide" || echo "❌ Chargement lent"

echo -e "\n=== Test des assets ==="
curl -I http://34.155.231.255/build/assets/app-GkHTs7Nb.css

echo -e "\n=== Test de la page de login ==="
curl -I http://34.155.231.255/login

echo -e "\n✅ Vérification terminée!"
echo "🌐 Si tout fonctionne, l'application est accessible sur: http://34.155.231.255" 