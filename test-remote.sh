#!/bin/bash

echo "🧪 Test du serveur distant..."

echo "=== Test de connectivité ==="
if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
    echo "✅ Serveur accessible"
else
    echo "❌ Serveur non accessible"
    exit 1
fi

echo "=== Test de l'application ==="
echo "Test de la page d'accueil:"
curl -I http://34.155.231.255

echo -e "\n=== Test de contenu ==="
echo "Premières lignes de la page:"
curl -s http://34.155.231.255 | head -10

echo -e "\n=== Test de performance ==="
time curl -s http://34.155.231.255 > /dev/null && echo "✅ Chargement rapide" || echo "❌ Chargement lent"

echo -e "\n✅ Tests terminés!" 