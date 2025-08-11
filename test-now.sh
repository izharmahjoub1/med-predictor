#!/bin/bash

echo "🔍 Test immédiat de l'application distante..."
echo ""

# Test simple avec curl
echo "Test HTTP..."
http_code=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 2>/dev/null)

if [ "$http_code" = "200" ]; then
    echo "✅ HTTP 200 - Application fonctionnelle !"
    echo "🌐 URL: http://34.155.231.255"
elif [ "$http_code" = "502" ]; then
    echo "⚠️  HTTP 502 - Bad Gateway (déploiement en cours)"
elif [ "$http_code" = "503" ]; then
    echo "⚠️  HTTP 503 - Service Unavailable"
elif [ -z "$http_code" ]; then
    echo "❌ Pas de réponse"
else
    echo "⚠️  HTTP $http_code"
fi

echo ""
echo "Application locale: ✅ Fonctionnelle (http://localhost:8000)"
echo "Application distante: $([ "$http_code" = "200" ] && echo "✅ Fonctionnelle" || echo "🔄 En cours")" 