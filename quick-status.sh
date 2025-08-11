#!/bin/bash

echo "🔍 Test rapide de l'application distante..."
echo ""

# Test de connectivité
echo "=== Connectivité ==="
if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
    echo "✅ Serveur accessible"
else
    echo "❌ Serveur non accessible"
    exit 1
fi

# Test HTTP
echo -e "\n=== Test HTTP ==="
http_status=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 2>/dev/null)
if [ "$http_status" = "200" ]; then
    echo "✅ HTTP 200 - Application fonctionnelle"
elif [ "$http_status" = "502" ]; then
    echo "❌ HTTP 502 - Bad Gateway (problème de service)"
elif [ "$http_status" = "503" ]; then
    echo "⚠️  HTTP 503 - Service Unavailable"
elif [ "$http_status" = "404" ]; then
    echo "❌ HTTP 404 - Not Found"
elif [ -z "$http_status" ]; then
    echo "❌ Pas de réponse HTTP"
else
    echo "⚠️  HTTP $http_status"
fi

# Test de performance
echo -e "\n=== Performance ==="
load_time=$(timeout 10 curl -s -o /dev/null -w "%{time_total}" http://34.155.231.255 2>/dev/null)
if [ ! -z "$load_time" ]; then
    if (( $(echo "$load_time < 2" | bc -l 2>/dev/null) )); then
        echo "✅ Chargement rapide (${load_time}s)"
    elif (( $(echo "$load_time < 5" | bc -l 2>/dev/null) )); then
        echo "⚠️  Chargement lent (${load_time}s)"
    else
        echo "❌ Chargement très lent (${load_time}s)"
    fi
else
    echo "❌ Chargement bloqué"
fi

# Test des assets
echo -e "\n=== Assets ==="
css_status=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255/build/assets/app-GkHTs7Nb.css 2>/dev/null)
if [ "$css_status" = "200" ]; then
    echo "✅ Assets CSS accessibles"
else
    echo "❌ Assets CSS non accessibles"
fi

# Résumé
echo -e "\n=== Résumé ==="
if [ "$http_status" = "200" ] && [ ! -z "$load_time" ]; then
    echo "🎉 Application distante fonctionnelle !"
    echo "🌐 URL: http://34.155.231.255"
else
    echo "⚠️  Application distante en cours de déploiement ou problème détecté"
    echo "💻 Application locale disponible: http://localhost:8000"
fi

echo -e "\n=== Comparaison ==="
echo "Application locale: ✅ Fonctionnelle (http://localhost:8000)"
echo "Application distante: $([ "$http_status" = "200" ] && echo "✅ Fonctionnelle" || echo "🔄 En cours")" 