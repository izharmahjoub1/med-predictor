#!/bin/bash

# 🏟️ TEST COMPLET DE TOUTES LES ROUTES
# Script pour tester toutes les routes principales de l'application

echo "🏟️  TEST COMPLET DE TOUTES LES ROUTES"
echo "====================================="
echo ""

# Configuration
BASE_URL="http://localhost:8000"
ROUTES=(
    # Routes principales
    "/"
    "/login"
    "/dashboard"
    
    # Club Management
    "/club-management/dashboard"
    "/club-management/players"
    "/club-management/players/import"
    "/club-management/players/bulk-import"
    "/club-management/players/export"
    "/club-management/teams"
    "/club-management/licenses"
    "/club-management/lineups"
    
    # FIFA Connect
    "/fifa/dashboard"
    "/fifa/connectivity"
    "/fifa/players/search"
    "/fifa/sync-dashboard"
    "/fifa/statistics"
    
    # Player Dashboard
    "/player-dashboard"
    
    # Healthcare
    "/healthcare/dashboard"
    "/health-records"
    "/medical-predictions/dashboard"
    
    # Competition Management
    "/competition-management"
    "/league-championship"
    
    # Transfers
    "/transfers"
    
    # Daily Passport
    "/daily-passport"
    
    # Back Office
    "/back-office/dashboard"
    "/admin/registration-requests"
    "/user-management/dashboard"
    "/role-management"
    
    # Referee
    "/referee/dashboard"
    "/referee/match-assignments"
    
    # Rankings
    "/rankings"
    
    # Match Sheet
    "/match-sheet"
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
    local status_code=$(curl -s -o /dev/null -w "%{http_code}" "$url")
    
    case $status_code in
        200)
            echo -e "${GREEN}✅${NC} ${route} ... ${GREEN}OK (200)${NC}"
            ;;
        302)
            echo -e "${BLUE}🔄${NC} ${route} ... ${BLUE}OK (302 - Redirection)${NC}"
            ;;
        404)
            echo -e "${RED}❌${NC} ${route} ... ${RED}ERREUR (404 - Not Found)${NC}"
            ;;
        500)
            echo -e "${RED}💥${NC} ${route} ... ${RED}ERREUR (500 - Server Error)${NC}"
            ;;
        *)
            echo -e "${YELLOW}⚠️${NC} ${route} ... ${YELLOW}STATUT ${status_code}${NC}"
            ;;
    esac
}

# Fonction pour afficher le résumé
show_summary() {
    echo ""
    echo "📊 RÉSUMÉ DES TESTS"
    echo "==================="
    echo ""
    echo -e "${GREEN}✅ Routes fonctionnelles (200/302)${NC}"
    echo -e "${RED}❌ Routes avec erreurs (404/500)${NC}"
    echo -e "${YELLOW}⚠️ Routes avec statuts inattendus${NC}"
    echo ""
    echo "🔍 Pour tester une route spécifique :"
    echo "   curl -I http://localhost:8000/route-name"
    echo ""
}

# Test de connectivité
echo "🔍 Test de connectivité au serveur..."
if curl -s --head "$BASE_URL" > /dev/null; then
    echo -e "${GREEN}✅ Serveur accessible${NC}"
    echo ""
else
    echo -e "${RED}❌ Serveur inaccessible${NC}"
    echo "Vérifiez que le serveur Laravel est démarré :"
    echo "   php artisan serve --host=localhost --port=8000"
    exit 1
fi

# Test de toutes les routes
echo "🧪 Test de toutes les routes..."
echo ""

for route in "${ROUTES[@]}"; do
    test_route "$route"
done

# Affichage du résumé
show_summary

echo "🎉 Test terminé !" 