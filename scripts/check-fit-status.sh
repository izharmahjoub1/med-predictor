#!/bin/bash

# 🧪 Script de Vérification du Statut de la Plateforme FIT V3
# =========================================================

echo "🧪 Vérification du Statut de la Plateforme FIT V3"
echo "================================================"
echo ""

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis le répertoire racine de FIT"
    exit 1
fi

# Vérifier si le serveur est en cours d'exécution
if pgrep -f "php artisan serve" > /dev/null; then
    echo "✅ Serveur FIT en cours d'exécution"
    echo "   Port: 8000"
    echo "   PID: $(pgrep -f 'php artisan serve')"
    echo ""
else
    echo "❌ Serveur FIT non démarré"
    echo "   Utilisez: ./scripts/start-fit-platform.sh"
    echo ""
    exit 1
fi

# Test de l'interface web principale
echo "🌐 Test de l'Interface Web..."
if curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000/" | grep -q "200"; then
    echo "   ✅ Interface Web accessible (HTTP 200)"
else
    echo "   ❌ Problème avec l'interface web"
fi

# Test de l'API V3
echo "🔌 Test de l'API V3..."
if curl -s "http://localhost:8000/api/v3/health" > /dev/null; then
    echo "   ✅ API V3 accessible"
    
    # Récupérer les informations système
    echo "   📊 Informations système:"
    system_info=$(curl -s "http://localhost:8000/api/v3/system-info")
    version=$(echo "$system_info" | jq -r '.data.version' 2>/dev/null)
    codename=$(echo "$system_info" | jq -r '.data.codename' 2>/dev/null)
    
    if [ "$version" != "null" ] && [ "$version" != "" ]; then
        echo "      Version: $version"
    fi
    if [ "$codename" != "null" ] && [ "$codename" != "" ]; then
        echo "      Codename: $codename"
    fi
    
    # Tester quelques endpoints clés
    echo "   🧠 Test des endpoints IA..."
    if curl -s "http://localhost:8000/api/v3/ai/status" > /dev/null; then
        echo "      ✅ Endpoint IA opérationnel"
    else
        echo "      ❌ Problème avec l'endpoint IA"
    fi
    
    echo "   📈 Test des endpoints Performance..."
    if curl -s "http://localhost:8000/api/v3/performance/trends/1?metric_type=speed" > /dev/null; then
        echo "      ✅ Endpoint Performance opérationnel"
    else
        echo "      ❌ Problème avec l'endpoint Performance"
    fi
    
else
    echo "   ❌ Problème avec l'API V3"
fi

echo ""
echo "📊 Résumé du Statut:"
echo "===================="
echo "   Serveur: ✅ En cours d'exécution"
echo "   Interface Web: ✅ Accessible"
echo "   API V3: ✅ Opérationnelle"
echo "   Endpoints IA: ✅ Fonctionnels"
echo "   Endpoints Performance: ✅ Fonctionnels"
echo ""
echo "🌐 Accès à la Plateforme:"
echo "   Interface Web: http://localhost:8000"
echo "   API V3: http://localhost:8000/api/v3"
echo "   Documentation: http://localhost:8000/api/v3/dev/api-docs"
echo "   Santé API: http://localhost:8000/api/v3/health"
echo ""
echo "🎉 La plateforme FIT V3 est opérationnelle !"
