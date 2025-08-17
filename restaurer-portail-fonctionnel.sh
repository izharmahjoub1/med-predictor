#!/bin/bash

# 🎯 Script de restauration du portail joueur fonctionnel
# 📅 Créé le : $(date)
# ✅ Version : Drapeaux fonctionnels

echo "🎯 RESTAURATION DU PORTAL JOUEUR FONCTIONNEL"
echo "=============================================="
echo ""

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Erreur : Ce script doit être exécuté depuis la racine du projet Laravel${NC}"
    exit 1
fi

echo -e "${BLUE}📁 Vérification des sauvegardes disponibles...${NC}"

# Vérifier la sauvegarde principale
if [ -f "resources/views/portail-joueur-FONCTIONNEL-DRAPEAUX-OK.blade.php" ]; then
    echo -e "${GREEN}✅ Sauvegarde principale trouvée${NC}"
    SOURCE_FILE="resources/views/portail-joueur-FONCTIONNEL-DRAPEAUX-OK.blade.php"
else
    echo -e "${YELLOW}⚠️  Sauvegarde principale non trouvée, recherche d'une sauvegarde de sécurité...${NC}"
    
    # Chercher la sauvegarde de sécurité la plus récente
    BACKUP_FILE=$(ls -t backups/portail-joueur-FONCTIONNEL-DRAPEAUX-OK-*.blade.php 2>/dev/null | head -1)
    
    if [ -n "$BACKUP_FILE" ]; then
        echo -e "${GREEN}✅ Sauvegarde de sécurité trouvée : $BACKUP_FILE${NC}"
        SOURCE_FILE="$BACKUP_FILE"
    else
        echo -e "${RED}❌ Aucune sauvegarde fonctionnelle trouvée !${NC}"
        exit 1
    fi
fi

echo ""
echo -e "${BLUE}🔄 Restauration en cours...${NC}"

# Créer une sauvegarde de l'état actuel
if [ -f "resources/views/portail-joueur-final-corrige-dynamique.blade.php" ]; then
    BACKUP_NAME="portail-joueur-ETAT-ACTUEL-$(date +%Y%m%d-%H%M%S).blade.php"
    cp "resources/views/portail-joueur-final-corrige-dynamique.blade.php" "backups/$BACKUP_NAME"
    echo -e "${YELLOW}💾 Sauvegarde de l'état actuel créée : $BACKUP_NAME${NC}"
fi

# Restaurer le fichier fonctionnel
cp "$SOURCE_FILE" "resources/views/portail-joueur-final-corrige-dynamique.blade.php"
echo -e "${GREEN}✅ Portail restauré avec succès !${NC}"

echo ""
echo -e "${BLUE}🧹 Nettoyage du cache...${NC}"

# Nettoyer le cache Laravel
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo -e "${GREEN}✅ Cache nettoyé !${NC}"

echo ""
echo -e "${GREEN}🎉 RESTAURATION TERMINÉE AVEC SUCCÈS !${NC}"
echo ""
echo -e "${BLUE}📋 Récapitulatif :${NC}"
echo "   • Portail restauré depuis : $SOURCE_FILE"
echo "   • Cache Laravel nettoyé"
echo "   • État actuel sauvegardé dans backups/"
echo ""
echo -e "${BLUE}🔗 Testez maintenant :${NC}"
echo "   • Portail joueur : http://localhost:8000/joueur/7"
echo "   • Vérifiez que les drapeaux s'affichent correctement"
echo ""
echo -e "${YELLOW}💡 En cas de problème, utilisez :${NC}"
echo "   • php artisan view:clear"
echo "   • php artisan config:clear"
echo "   • Vérifiez les logs : tail -f storage/logs/laravel.log"

