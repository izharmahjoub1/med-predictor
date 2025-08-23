#!/bin/bash

# 🔗 Script de configuration DNS - fit.tbhc.uk
# Configuration des enregistrements DNS pour pointer vers Google Cloud

set -e

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="fit.tbhc.uk"
PROJECT_ID="med-predictor-fit"
REGION="europe-west1"
SERVICE_NAME="med-predictor"

echo -e "${BLUE}🔗 Configuration DNS pour fit.tbhc.uk${NC}"
echo -e "${BLUE}🌐 Domaine : $DOMAIN${NC}"
echo -e "${BLUE}📅 Date : $(date)${NC}"
echo ""

# Vérification des prérequis
echo -e "${YELLOW}🔍 Vérification des prérequis...${NC}"

# Vérifier que gcloud est installé
if ! command -v gcloud &> /dev/null; then
    echo -e "${RED}❌ Google Cloud CLI n'est pas installé${NC}"
    echo "Installez-le avec : curl https://sdk.cloud.google.com | bash"
    exit 1
fi

echo -e "${GREEN}✅ Prérequis vérifiés${NC}"
echo ""

# Configuration du projet
echo -e "${YELLOW}⚙️ Configuration du projet Google Cloud...${NC}"
gcloud config set project $PROJECT_ID

echo -e "${GREEN}✅ Projet configuré : $PROJECT_ID${NC}"
echo ""

# Obtenir l'URL du service Cloud Run
echo -e "${YELLOW}🔍 Récupération de l'URL du service...${NC}"

SERVICE_URL=$(gcloud run services describe $SERVICE_NAME --region=$REGION --format='value(status.url)')

if [ -z "$SERVICE_URL" ]; then
    echo -e "${RED}❌ Impossible de récupérer l'URL du service${NC}"
    echo "Vérifiez que le service est déployé : gcloud run services list"
    exit 1
fi

echo -e "${GREEN}✅ URL du service : $SERVICE_URL${NC}"
echo ""

# Extraire l'IP ou l'hostname du service
SERVICE_HOST=$(echo $SERVICE_URL | sed 's|https://||')

echo -e "${BLUE}📋 Informations DNS à configurer :${NC}"
echo ""
echo -e "${YELLOW}🔗 Enregistrements DNS à ajouter dans votre registrar :${NC}"
echo ""
echo -e "${BLUE}Type A :${NC}"
echo -e "  Nom : fit"
echo -e "  Valeur : $SERVICE_HOST"
echo -e "  TTL : 300"
echo ""
echo -e "${BLUE}Type CNAME :${NC}"
echo -e "  Nom : www.fit"
echo -e "  Valeur : fit.tbhc.uk"
echo -e "  TTL : 300"
echo ""
echo -e "${BLUE}Type TXT :${NC}"
echo -e "  Nom : fit"
echo -e "  Valeur : google-site-verification=YOUR_VERIFICATION_CODE"
echo -e "  TTL : 300"
echo ""

# Configuration du domaine personnalisé dans Google Cloud
echo -e "${YELLOW}🔗 Configuration du domaine personnalisé dans Google Cloud...${NC}"

# Vérifier si le mapping de domaine existe déjà
if gcloud run domain-mappings describe --domain=$DOMAIN --region=$REGION &> /dev/null; then
    echo -e "${GREEN}✅ Mapping de domaine existe déjà${NC}"
else
    echo "Création du mapping de domaine..."
    
    gcloud run domain-mappings create \
        --service $SERVICE_NAME \
        --domain $DOMAIN \
        --region $REGION
    
    echo -e "${GREEN}✅ Mapping de domaine créé${NC}"
fi

echo ""

# Vérification de la configuration DNS
echo -e "${YELLOW}🔍 Vérification de la configuration DNS...${NC}"

echo "Attendez quelques minutes que la propagation DNS se fasse..."
echo "Puis testez avec : nslookup $DOMAIN"
echo ""

# Test de résolution DNS
echo "Test de résolution DNS en cours..."
if nslookup $DOMAIN &> /dev/null; then
    echo -e "${GREEN}✅ Résolution DNS fonctionnelle${NC}"
else
    echo -e "${YELLOW}⚠️ Résolution DNS pas encore active (propagation en cours)${NC}"
fi

echo ""

# Configuration SSL automatique
echo -e "${YELLOW}🔐 Configuration SSL automatique...${NC}"

echo "Google Cloud gère automatiquement les certificats SSL pour les domaines personnalisés."
echo "Assurez-vous que votre domaine pointe vers le service Cloud Run."
echo ""

# Instructions pour le registrar
echo -e "${BLUE}📋 Instructions pour votre registrar DNS :${NC}"
echo ""
echo "1. Connectez-vous à votre registrar (GoDaddy, Namecheap, etc.)"
echo "2. Allez dans la section 'Gestion DNS' ou 'Zone DNS'"
echo "3. Ajoutez les enregistrements suivants :"
echo ""
echo -e "${YELLOW}Enregistrement A :${NC}"
echo "   Type : A"
echo "   Nom : fit"
echo "   Valeur : $SERVICE_HOST"
echo "   TTL : 300"
echo ""
echo -e "${YELLOW}Enregistrement CNAME :${NC}"
echo "   Type : CNAME"
echo "   Nom : www.fit"
echo "   Valeur : fit.tbhc.uk"
echo "   TTL : 300"
echo ""
echo "4. Sauvegardez les modifications"
echo "5. Attendez 5-15 minutes pour la propagation DNS"
echo ""

# Test de connectivité
echo -e "${YELLOW}🧪 Test de connectivité...${NC}"

echo "Une fois la propagation DNS terminée, testez :"
echo "  • https://$DOMAIN"
echo "  • https://www.$DOMAIN"
echo ""

# Vérification de la configuration
echo -e "${YELLOW}🔍 Vérification de la configuration...${NC}"

echo "Pour vérifier que tout fonctionne :"
echo "1. Testez l'URL : https://$DOMAIN"
echo "2. Vérifiez que le certificat SSL est valide"
echo "3. Testez la console vocale et toutes les fonctionnalités"
echo ""

# Configuration des sous-domaines supplémentaires
echo -e "${YELLOW}🔧 Configuration des sous-domaines supplémentaires...${NC}"

echo "Sous-domaines recommandés à configurer :"
echo "  • api.fit.tbhc.uk (pour les APIs)"
echo "  • admin.fit.tbhc.uk (pour l'administration)"
echo "  • docs.fit.tbhc.uk (pour la documentation)"
echo ""

# Résumé final
echo -e "${GREEN}🎉 Configuration DNS terminée !${NC}"
echo ""
echo -e "${BLUE}📋 Résumé de la configuration :${NC}"
echo -e "  • Domaine principal : $DOMAIN"
echo -e "  • Service Cloud Run : $SERVICE_NAME"
echo -e "  • URL du service : $SERVICE_URL"
echo -e "  • Hostname du service : $SERVICE_HOST"
echo ""
echo -e "${BLUE}🔧 Prochaines étapes :${NC}"
echo -e "  1. Configurer les enregistrements DNS dans votre registrar"
echo -e "  2. Attendre la propagation DNS (5-15 minutes)"
echo -e "  3. Tester l'accessibilité du domaine"
echo -e "  4. Vérifier le certificat SSL"
echo -e "  5. Tester toutes les fonctionnalités de l'application"
echo ""
echo -e "${BLUE}📧 Support :${NC}"
echo -e "  • Lead Developer : izhar@tbhc.uk"
echo -e "  • Documentation : Voir les guides créés"
echo ""
echo -e "${GREEN}🌐 Votre domaine fit.tbhc.uk sera bientôt accessible !${NC}"
