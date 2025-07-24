#!/bin/bash

# 🏟️ TEST COMPLET CLUB MANAGEMENT ROUTES
# Script pour tester toutes les routes du club management

echo "🏟️  TEST COMPLET DES ROUTES CLUB MANAGEMENT"
echo "=========================================="
echo ""

# Configuration
BASE_URL="http://localhost:8000"
ROUTES=(
    "/club-management/dashboard"
    "/club-management/players"
    "/club-management/players/import"
    "/club-management/players/bulk-import"
    "/club-management/players/export"
    "/club-management/teams"
    "/club-management/licenses"
    "/club-management/lineups"
)

# Couleurs pour l'affichage
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour tester une route
test_route() {
    local route=$1
    local url="${BASE_URL}${route}"
    
    echo -n "🔍 Test: ${route} ... "
    
    # Test avec curl
    response=$(curl -s -o /dev/null -w "%{http_code}" "$url")
    
    if [ "$response" = "302" ]; then
        echo -e "${GREEN}✅ OK (302 - Redirection login)${NC}"
    elif [ "$response" = "200" ]; then
        echo -e "${GREEN}✅ OK (200 - Page accessible)${NC}"
    elif [ "$response" = "404" ]; then
        echo -e "${RED}❌ ERREUR (404 - Route non trouvée)${NC}"
    elif [ "$response" = "500" ]; then
        echo -e "${RED}❌ ERREUR (500 - Erreur serveur)${NC}"
    else
        echo -e "${YELLOW}⚠️  ATTENTION (Code: $response)${NC}"
    fi
}

# Test de la connexion au serveur
echo -n "🌐 Test de connexion au serveur Laravel ... "
if curl -s -o /dev/null -w "%{http_code}" "$BASE_URL" | grep -q "302\|200"; then
    echo -e "${GREEN}✅ Serveur accessible${NC}"
else
    echo -e "${RED}❌ Serveur inaccessible${NC}"
    echo "Assurez-vous que le serveur Laravel est démarré :"
    echo "  php artisan serve --host=localhost --port=8000"
    exit 1
fi

echo ""

# Test de toutes les routes
echo "📋 Test des routes du club management :"
echo "--------------------------------------"

for route in "${ROUTES[@]}"; do
    test_route "$route"
done

echo ""
echo "🎯 Test des routes spécifiques :"
echo "-------------------------------"

# Test des routes avec paramètres
echo -n "🔍 Test: /club-management/players/bulk-import (route spécifique) ... "
bulk_import_response=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/club-management/players/bulk-import")
if [ "$bulk_import_response" = "302" ]; then
    echo -e "${GREEN}✅ OK (302 - Redirection login)${NC}"
else
    echo -e "${RED}❌ ERREUR (Code: $bulk_import_response)${NC}"
fi

echo ""
echo "📊 Résumé des tests :"
echo "===================="

# Vérification des routes Laravel
echo -n "🔧 Vérification des routes Laravel ... "
if php artisan route:list --name=club-management | grep -q "club-management"; then
    echo -e "${GREEN}✅ Routes définies${NC}"
else
    echo -e "${RED}❌ Routes manquantes${NC}"
fi

echo ""
echo "🎉 Tests terminés !"
echo ""
echo "💡 Notes :"
echo "  - Code 302 : Redirection vers login (normal pour les routes protégées)"
echo "  - Code 200 : Page accessible directement"
echo "  - Code 404 : Route non trouvée"
echo "  - Code 500 : Erreur serveur (vérifier les logs)"
echo ""
echo "🚀 Prochaines étapes :"
echo "  1. Se connecter pour tester les pages complètes"
echo "  2. Vérifier les fonctionnalités d'import en masse"
echo "  3. Tester la gestion des licences"
echo "  4. Valider l'interface utilisateur" 