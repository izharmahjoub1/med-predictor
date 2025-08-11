#!/bin/bash

echo "📊 Surveillance de l'application distante en temps réel..."
echo "Appuyez sur Ctrl+C pour arrêter la surveillance"
echo ""

# Fonction pour tester l'application
test_app() {
    local timestamp=$(date '+%H:%M:%S')
    
    # Test de connectivité
    if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
        echo "[$timestamp] ✅ Serveur accessible"
    else
        echo "[$timestamp] ❌ Serveur non accessible"
        return 1
    fi
    
    # Test HTTP
    local http_status=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 2>/dev/null)
    if [ "$http_status" = "200" ]; then
        echo "[$timestamp] ✅ HTTP 200 - Application fonctionnelle"
    elif [ "$http_status" = "502" ]; then
        echo "[$timestamp] ❌ HTTP 502 - Bad Gateway"
    elif [ "$http_status" = "503" ]; then
        echo "[$timestamp] ⚠️  HTTP 503 - Service Unavailable"
    elif [ "$http_status" = "404" ]; then
        echo "[$timestamp] ❌ HTTP 404 - Not Found"
    elif [ -z "$http_status" ]; then
        echo "[$timestamp] ❌ Pas de réponse HTTP"
    else
        echo "[$timestamp] ⚠️  HTTP $http_status"
    fi
    
    # Test de performance
    local load_time=$(timeout 10 curl -s -o /dev/null -w "%{time_total}" http://34.155.231.255 2>/dev/null)
    if [ ! -z "$load_time" ]; then
        if (( $(echo "$load_time < 2" | bc -l) )); then
            echo "[$timestamp] ✅ Chargement rapide (${load_time}s)"
        elif (( $(echo "$load_time < 5" | bc -l) )); then
            echo "[$timestamp] ⚠️  Chargement lent (${load_time}s)"
        else
            echo "[$timestamp] ❌ Chargement très lent (${load_time}s)"
        fi
    else
        echo "[$timestamp] ❌ Chargement bloqué"
    fi
    
    # Test des assets
    local css_status=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255/build/assets/app-GkHTs7Nb.css 2>/dev/null)
    if [ "$css_status" = "200" ]; then
        echo "[$timestamp] ✅ Assets CSS accessibles"
    else
        echo "[$timestamp] ❌ Assets CSS non accessibles"
    fi
    
    echo "---"
}

# Boucle de surveillance
while true; do
    test_app
    sleep 30  # Vérification toutes les 30 secondes
done 