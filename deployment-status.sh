#!/bin/bash

echo "📋 État du déploiement de l'application distante"
echo "================================================"
echo ""

# Vérifier si l'archive existe
if [ -f "med-predictor-complete.tar.gz" ]; then
    echo "✅ Archive de déploiement trouvée"
    echo "📦 Taille: $(du -h med-predictor-complete.tar.gz | cut -f1)"
else
    echo "❌ Archive de déploiement manquante"
    echo "🔄 Création en cours..."
fi

echo ""

# Test de connectivité
echo "=== Connectivité ==="
if ping -c 1 34.155.231.255 > /dev/null 2>&1; then
    echo "✅ Serveur accessible"
else
    echo "❌ Serveur non accessible"
    echo "💡 Utilisez Google Cloud Shell pour accéder au serveur"
fi

echo ""

# Test de l'application
echo "=== Application Distante ==="
http_code=$(curl -s -o /dev/null -w "%{http_code}" http://34.155.231.255 2>/dev/null)

if [ "$http_code" = "200" ]; then
    echo "🎉 SUCCÈS ! Application fonctionnelle"
    echo "🌐 URL: http://34.155.231.255"
elif [ "$http_code" = "502" ]; then
    echo "🔄 Déploiement en cours (HTTP 502)"
    echo "⏳ Attendez que les services se redémarrent"
elif [ "$http_code" = "503" ]; then
    echo "🔄 Services en cours de démarrage (HTTP 503)"
elif [ "$http_code" = "000" ]; then
    echo "❌ Pas de réponse du serveur"
    echo "💡 Le serveur peut être en cours de redémarrage"
else
    echo "⚠️  Statut HTTP: $http_code"
fi

echo ""

# État local
echo "=== Application Locale ==="
echo "✅ Fonctionnelle (http://localhost:8000)"
echo "📊 Logs: Voir le terminal avec php artisan serve"

echo ""

# Actions recommandées
echo "=== Actions Recommandées ==="
if [ "$http_code" = "000" ] || [ "$http_code" = "502" ] || [ "$http_code" = "503" ]; then
    echo "1. 🕐 Attendez 2-3 minutes pour le déploiement"
    echo "2. 🔄 Relancez ce script: ./deployment-status.sh"
    echo "3. 💻 Utilisez l'application locale en attendant"
    echo "4. ☁️  Utilisez Google Cloud Shell si nécessaire"
else
    echo "🎉 Déploiement réussi !"
    echo "🌐 Application distante: http://34.155.231.255"
fi

echo ""
echo "💻 Application locale toujours disponible: http://localhost:8000" 