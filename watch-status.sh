#!/bin/bash

echo "📊 Surveillance de l'application distante..."
echo "Appuyez sur Ctrl+C pour arrêter"
echo ""

while true; do
    clear
    echo "📊 Surveillance de l'application distante..."
    echo "Dernière vérification: $(date)"
    echo ""
    
    # Test HTTP
    http_code=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 2>/dev/null)
    
    if [ "$http_code" = "200" ]; then
        echo "🎉 SUCCÈS ! Application distante fonctionnelle !"
        echo "🌐 URL: http://34.155.231.255"
        echo ""
        echo "✅ L'application distante fonctionne comme la locale !"
        echo "💻 Application locale: http://localhost:8000"
    elif [ "$http_code" = "502" ]; then
        echo "🔄 DÉPLOIEMENT EN COURS..."
        echo "⚠️  HTTP 502 - Bad Gateway"
        echo ""
        echo "Le déploiement est en cours, attendez..."
        echo "💻 Application locale disponible: http://localhost:8000"
    elif [ "$http_code" = "503" ]; then
        echo "🔄 SERVICE EN COURS DE DÉMARRAGE..."
        echo "⚠️  HTTP 503 - Service Unavailable"
        echo ""
        echo "Les services se redémarrent..."
        echo "💻 Application locale disponible: http://localhost:8000"
    elif [ -z "$http_code" ]; then
        echo "❌ PROBLÈME DE CONNECTIVITÉ"
        echo "Pas de réponse du serveur"
        echo ""
        echo "Vérifiez la connectivité..."
        echo "💻 Application locale disponible: http://localhost:8000"
    else
        echo "⚠️  STATUT INCONNU"
        echo "HTTP $http_code"
        echo ""
        echo "Statut inattendu..."
        echo "💻 Application locale disponible: http://localhost:8000"
    fi
    
    echo ""
    echo "Actualisation dans 5 secondes... (Ctrl+C pour arrêter)"
    sleep 5
done 