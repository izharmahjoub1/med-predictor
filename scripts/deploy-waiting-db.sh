#!/bin/bash

# 🚀 Script de déploiement avec attente de la base de données
# Déploiement de Med Predictor FIT sur Google Cloud

set -e

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_ID="med-predictor-fit"
REGION="europe-west1"
SERVICE_NAME="med-predictor"
DB_INSTANCE="med-predictor-db"
DB_NAME="med_predictor"
DB_USER="med_predictor_user"

echo -e "${BLUE}🚀 Déploiement de Med Predictor FIT sur Google Cloud${NC}"
echo -e "${BLUE}🌐 Domaine : fit.tbhc.uk${NC}"
echo -e "${BLUE}📅 Date : $(date)${NC}"
echo ""

# Configuration du projet
echo -e "${YELLOW}⚙️ Configuration du projet Google Cloud...${NC}"
gcloud config set project $PROJECT_ID
gcloud config set run/region $REGION

echo -e "${GREEN}✅ Projet configuré : $PROJECT_ID${NC}"
echo ""

# Attendre que l'instance Cloud SQL soit prête
echo -e "${YELLOW}⏳ Attente de l'instance Cloud SQL...${NC}"

while true; do
    STATUS=$(gcloud sql instances describe $DB_INSTANCE --format='value(state)')
    
    if [ "$STATUS" = "RUNNABLE" ]; then
        echo -e "${GREEN}✅ Instance Cloud SQL prête !${NC}"
        break
    elif [ "$STATUS" = "PENDING_CREATE" ]; then
        echo "⏳ Instance en cours de création... (statut: $STATUS)"
        sleep 30
    else
        echo "⏳ Statut actuel: $STATUS"
        sleep 30
    fi
done

echo ""

# Créer la base de données
echo -e "${YELLOW}🗄️ Création de la base de données...${NC}"

if ! gcloud sql databases describe $DB_NAME --instance=$DB_INSTANCE &> /dev/null; then
    echo "Création de la base de données..."
    gcloud sql databases create $DB_NAME --instance=$DB_INSTANCE
    echo -e "${GREEN}✅ Base de données créée${NC}"
else
    echo -e "${GREEN}✅ Base de données existe déjà${NC}"
fi

# Créer l'utilisateur
echo -e "${YELLOW}👤 Création de l'utilisateur de base de données...${NC}"

if ! gcloud sql users describe $DB_USER --instance=$DB_INSTANCE &> /dev/null; then
    echo "Création de l'utilisateur..."
    
    # Demander le mot de passe
    echo -n "Entrez le mot de passe pour l'utilisateur $DB_USER : "
    read -s DB_USER_PASSWORD
    echo ""
    
    gcloud sql users create $DB_USER \
        --instance=$DB_INSTANCE \
        --password=$DB_USER_PASSWORD
    
    echo -e "${GREEN}✅ Utilisateur créé${NC}"
else
    echo -e "${GREEN}✅ Utilisateur existe déjà${NC}"
fi

echo ""

# Build et déploiement Docker
echo -e "${YELLOW}📦 Build de l'image Docker...${NC}"

# Construire l'image avec le Dockerfile de déploiement
docker build -f Dockerfile.deploy -t gcr.io/$PROJECT_ID/$SERVICE_NAME .

echo -e "${GREEN}✅ Image Docker construite${NC}"
echo ""

# Push vers Google Container Registry
echo -e "${YELLOW}⬆️ Push de l'image...${NC}"

gcloud auth configure-docker
docker tag gcr.io/$PROJECT_ID/$SERVICE_NAME:latest gcr.io/$PROJECT_ID/$SERVICE_NAME:$(date +%Y%m%d-%H%M%S)
docker push gcr.io/$PROJECT_ID/$SERVICE_NAME:latest

echo -e "${GREEN}✅ Image poussée${NC}"
echo ""

# Déploiement sur Cloud Run
echo -e "${YELLOW}🚀 Déploiement sur Cloud Run...${NC}"

gcloud run deploy $SERVICE_NAME \
    --image gcr.io/$PROJECT_ID/$SERVICE_NAME:latest \
    --platform managed \
    --region $REGION \
    --allow-unauthenticated \
    --set-env-vars "APP_ENV=production" \
    --set-env-vars "APP_URL=https://fit.tbhc.uk" \
    --add-cloudsql-instances $PROJECT_ID:$REGION:$DB_INSTANCE \
    --memory 2Gi \
    --cpu 2 \
    --max-instances 3 \
    --timeout 300

echo -e "${GREEN}✅ Service déployé sur Cloud Run${NC}"
echo ""

# Obtenir l'URL du service
SERVICE_URL=$(gcloud run services describe $SERVICE_NAME --region=$REGION --format='value(status.url)')

echo -e "${GREEN}🌐 Service déployé avec succès !${NC}"
echo -e "${BLUE}URL du service : $SERVICE_URL${NC}"
echo ""

# Configuration du domaine personnalisé
echo -e "${YELLOW}🔗 Configuration du domaine fit.tbhc.uk...${NC}"

gcloud run domain-mappings create \
    --service $SERVICE_NAME \
    --domain fit.tbhc.uk \
    --region $REGION

echo -e "${GREEN}✅ Domaine fit.tbhc.uk configuré${NC}"
echo ""

# Migration de la base de données
echo -e "${YELLOW}🗄️ Migration de la base de données...${NC}"

gcloud run jobs create migrate-med-predictor \
    --image gcr.io/$PROJECT_ID/$SERVICE_NAME:latest \
    --region $REGION \
    --command="php" \
    --args="artisan,migrate,--force" \
    --add-cloudsql-instances $PROJECT_ID:$REGION:$DB_INSTANCE \
    --set-env-vars "APP_ENV=production"

gcloud run jobs execute migrate-med-predictor --region=$REGION

echo -e "${GREEN}✅ Migration terminée${NC}"
echo ""

# Résumé final
echo -e "${GREEN}🎉 Déploiement terminé avec succès !${NC}"
echo ""
echo -e "${BLUE}📋 Résumé :${NC}"
echo -e "  • Projet : $PROJECT_ID"
echo -e "  • Service : $SERVICE_NAME"
echo -e "  • Base de données : $DB_INSTANCE"
echo -e "  • URL du service : $SERVICE_URL"
echo -e "  • Domaine : https://fit.tbhc.uk"
echo ""
echo -e "${BLUE}🔧 Prochaines étapes :${NC}"
echo -e "  1. Configurer le DNS dans votre registrar"
echo -e "  2. Tester l'application"
echo -e "  3. Configurer Google Workspace"
echo ""
echo -e "${GREEN}🚀 Votre plateforme FIT est maintenant en ligne !${NC}"
