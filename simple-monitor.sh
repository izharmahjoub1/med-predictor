#!/bin/bash

echo "📊 Surveillance simplifiée de l'application distante..."
echo "Appuyez sur Ctrl+C pour arrêter"
echo ""

while true; do
    timestamp=$(date '+%H:%M:%S')
    
    # Test simple avec curl
    if curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 > /tmp/http_status 2>/dev/null; then
        http_status=$(cat /tmp/http_status)
        if [ "$http_status" = "200" ]; then
            echo "[$timestamp] ✅ Application fonctionnelle (HTTP 200)"
        else
            echo "[$timestamp] ⚠️  HTTP $http_status"
        fi
    else
        echo "[$timestamp] ❌ Pas de réponse"
    fi
    
    sleep 10
done 