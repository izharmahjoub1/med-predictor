#!/bin/bash

# 🚀 Script de Démarrage de la Plateforme FIT V3
# ===============================================

echo "🚀 Démarrage de la Plateforme FIT V3..."
echo "======================================"
echo ""

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis le répertoire racine de FIT"
    echo "   Veuillez naviguer vers le répertoire med-predictor et relancer le script"
    exit 1
fi

# Vérifier si PHP est installé
if ! command -v php &> /dev/null; then
    echo "❌ Erreur: PHP n'est pas installé ou n'est pas dans le PATH"
    exit 1
fi

# Vérifier si Composer est installé
if ! command -v composer &> /dev/null; then
    echo "❌ Erreur: Composer n'est pas installé ou n'est pas dans le PATH"
    exit 1
fi

echo "✅ Vérifications de base terminées"
echo ""

# Vérifier si le serveur est déjà en cours d'exécution
if pgrep -f "php artisan serve" > /dev/null; then
    echo "🔄 Le serveur FIT est déjà en cours d'exécution"
    echo "   URL: http://localhost:8000"
    echo "   API V3: http://localhost:8000/api/v3"
    echo ""
    echo "📊 Statut de la plateforme:"
    echo "   - Interface Web: http://localhost:8000"
    echo "   - API V3: http://localhost:8000/api/v3"
    echo "   - Documentation: http://localhost:8000/api/v3/dev/api-docs"
    echo ""
    echo "🛑 Pour arrêter le serveur: Ctrl+C"
    echo ""
    
    # Tester la santé de l'API V3
    echo "🧪 Test de l'API V3..."
    if curl -s "http://localhost:8000/api/v3/health" > /dev/null; then
        echo "   ✅ API V3 opérationnelle"
    else
        echo "   ❌ Problème avec l'API V3"
    fi
    
    exit 0
fi

echo "🔧 Installation des dépendances..."
composer install --no-dev --optimize-autoloader

echo "🔧 Nettoyage du cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🔧 Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Démarrage du serveur FIT V3..."
echo ""
echo "📊 La plateforme FIT V3 sera accessible sur:"
echo "   - Interface Web: http://localhost:8000"
echo "   - API V3: http://localhost:8000/api/v3"
echo "   - Documentation: http://localhost:8000/api/v3/dev/api-docs"
echo ""
echo "🛑 Pour arrêter le serveur: Ctrl+C"
echo ""

# Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=8000
