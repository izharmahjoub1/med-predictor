#!/bin/bash

# 🚀 Script de déploiement automatique - Med Predictor FIT
# Déploiement sur Google Cloud Platform pour fit.tbhc.uk

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

# Vérification des prérequis
echo -e "${YELLOW}🔍 Vérification des prérequis...${NC}"

# Vérifier que gcloud est installé
if ! command -v gcloud &> /dev/null; then
    echo -e "${RED}❌ Google Cloud CLI n'est pas installé${NC}"
    echo "Installez-le avec : curl https://sdk.cloud.google.com | bash"
    exit 1
fi

# Vérifier que docker est installé
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker n'est pas installé${NC}"
    exit 1
fi

# Vérifier que le projet est configuré
if ! gcloud config get-value project &> /dev/null; then
    echo -e "${RED}❌ Aucun projet Google Cloud configuré${NC}"
    echo "Configurez le projet avec : gcloud init"
    exit 1
fi

echo -e "${GREEN}✅ Prérequis vérifiés${NC}"
echo ""

# Configuration du projet
echo -e "${YELLOW}⚙️ Configuration du projet Google Cloud...${NC}"
gcloud config set project $PROJECT_ID
gcloud config set run/region $REGION

echo -e "${GREEN}✅ Projet configuré : $PROJECT_ID${NC}"
echo ""

# Activation des APIs nécessaires
echo -e "${YELLOW}🔌 Activation des APIs Google Cloud...${NC}"
gcloud services enable cloudbuild.googleapis.com
gcloud services enable run.googleapis.com
gcloud services enable sqladmin.googleapis.com
gcloud services enable compute.googleapis.com
gcloud services enable monitoring.googleapis.com
gcloud services enable logging.googleapis.com

echo -e "${GREEN}✅ APIs activées${NC}"
echo ""

# Configuration de la base de données Cloud SQL
echo -e "${YELLOW}🗄️ Configuration de la base de données...${NC}"

# Vérifier si l'instance existe déjà
if ! gcloud sql instances describe $DB_INSTANCE &> /dev/null; then
    echo "Création de l'instance Cloud SQL..."
    
    # Demander le mot de passe root
    echo -n "Entrez le mot de passe root pour la base de données : "
    read -s DB_ROOT_PASSWORD
    echo ""
    
    gcloud sql instances create $DB_INSTANCE \
        --database-version=MYSQL_8_0 \
        --tier=db-f1-micro \
        --region=$REGION \
        --root-password=$DB_ROOT_PASSWORD \
        --storage-type=SSD \
        --storage-size=10GB \
        --backup-start-time="02:00" \
        --maintenance-window-day=SUN \
        --maintenance-window-hour=03
    
    echo -e "${GREEN}✅ Instance Cloud SQL créée${NC}"
else
    echo -e "${GREEN}✅ Instance Cloud SQL existe déjà${NC}"
fi

# Créer la base de données si elle n'existe pas
if ! gcloud sql databases describe $DB_NAME --instance=$DB_INSTANCE &> /dev/null; then
    echo "Création de la base de données..."
    gcloud sql databases create $DB_NAME --instance=$DB_INSTANCE
    echo -e "${GREEN}✅ Base de données créée${NC}"
else
    echo -e "${GREEN}✅ Base de données existe déjà${NC}"
fi

# Créer l'utilisateur si il n'existe pas
if ! gcloud sql users describe $DB_USER --instance=$DB_INSTANCE &> /dev/null; then
    echo "Création de l'utilisateur de base de données..."
    
    # Demander le mot de passe utilisateur
    echo -n "Entrez le mot de passe pour l'utilisateur $DB_USER : "
    read -s DB_USER_PASSWORD
    echo ""
    
    gcloud sql users create $DB_USER \
        --instance=$DB_INSTANCE \
        --password=$DB_USER_PASSWORD
    
    echo -e "${GREEN}✅ Utilisateur de base de données créé${NC}"
else
    echo -e "${GREEN}✅ Utilisateur de base de données existe déjà${NC}"
fi

echo ""

# Configuration des variables d'environnement
echo -e "${YELLOW}🔧 Configuration des variables d'environnement...${NC}"

# Créer le fichier .env.production
cat > .env.production << EOF
APP_NAME="Med Predictor FIT"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fit.tbhc.uk

DB_CONNECTION=mysql
DB_HOST=/cloudsql/$PROJECT_ID:$REGION:$DB_INSTANCE
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_USER_PASSWORD

GOOGLE_SPEECH_API_KEY=your_google_cloud_api_key
GOOGLE_CLOUD_PROJECT=$PROJECT_ID

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@tbhc.uk
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tbhc.uk
MAIL_FROM_NAME="Med Predictor FIT"

LOG_CHANNEL=stack
LOG_LEVEL=info
EOF

echo -e "${GREEN}✅ Fichier .env.production créé${NC}"
echo ""

# Build de l'image Docker
echo -e "${YELLOW}📦 Build de l'image Docker...${NC}"

# Construire l'image
docker build -t gcr.io/$PROJECT_ID/$SERVICE_NAME .

echo -e "${GREEN}✅ Image Docker construite${NC}"
echo ""

# Push de l'image vers Google Container Registry
echo -e "${YELLOW}⬆️ Push de l'image vers Google Container Registry...${NC}"

# Configurer Docker pour utiliser gcloud comme authentification
gcloud auth configure-docker

# Tagger et pousser l'image
docker tag gcr.io/$PROJECT_ID/$SERVICE_NAME:latest gcr.io/$PROJECT_ID/$SERVICE_NAME:$(date +%Y%m%d-%H%M%S)
docker push gcr.io/$PROJECT_ID/$SERVICE_NAME:latest

echo -e "${GREEN}✅ Image poussée vers Google Container Registry${NC}"
echo ""

# Déploiement sur Cloud Run
echo -e "${YELLOW}🚀 Déploiement sur Google Cloud Run...${NC}"

# Déployer le service
gcloud run deploy $SERVICE_NAME \
    --image gcr.io/$PROJECT_ID/$SERVICE_NAME:latest \
    --platform managed \
    --region $REGION \
    --allow-unauthenticated \
    --set-env-vars "APP_ENV=production" \
    --set-env-vars "APP_URL=https://fit.tbhc.uk" \
    --set-env-vars "DB_HOST=/cloudsql/$PROJECT_ID:$REGION:$DB_INSTANCE" \
    --set-env-vars "DB_DATABASE=$DB_NAME" \
    --set-env-vars "DB_USERNAME=$DB_USER" \
    --set-env-vars "GOOGLE_CLOUD_PROJECT=$PROJECT_ID" \
    --add-cloudsql-instances $PROJECT_ID:$REGION:$DB_INSTANCE \
    --memory 2Gi \
    --cpu 2 \
    --max-instances 10 \
    --timeout 300

echo -e "${GREEN}✅ Service déployé sur Cloud Run${NC}"
echo ""

# Obtenir l'URL du service
SERVICE_URL=$(gcloud run services describe $SERVICE_NAME --region=$REGION --format='value(status.url)')

echo -e "${GREEN}🌐 Service déployé avec succès !${NC}"
echo -e "${BLUE}URL du service : $SERVICE_URL${NC}"
echo ""

# Configuration du domaine personnalisé
echo -e "${YELLOW}🔗 Configuration du domaine personnalisé...${NC}"

# Mapper le domaine fit.tbhc.uk au service
gcloud run domain-mappings create \
    --service $SERVICE_NAME \
    --domain fit.tbhc.uk \
    --region $REGION

echo -e "${GREEN}✅ Domaine fit.tbhc.uk configuré${NC}"
echo ""

# Migration de la base de données
echo -e "${YELLOW}🗄️ Migration de la base de données...${NC}"

# Créer un job Cloud Run pour la migration
gcloud run jobs create migrate-med-predictor \
    --image gcr.io/$PROJECT_ID/$SERVICE_NAME:latest \
    --region $REGION \
    --command="php" \
    --args="artisan,migrate,--force" \
    --add-cloudsql-instances $PROJECT_ID:$REGION:$DB_INSTANCE \
    --set-env-vars "APP_ENV=production" \
    --set-env-vars "DB_HOST=/cloudsql/$PROJECT_ID:$REGION:$DB_INSTANCE" \
    --set-env-vars "DB_DATABASE=$DB_NAME" \
    --set-env-vars "DB_USERNAME=$DB_USER"

# Exécuter la migration
gcloud run jobs execute migrate-med-predictor --region=$REGION

echo -e "${GREEN}✅ Migration de la base de données terminée${NC}"
echo ""

# Configuration du monitoring
echo -e "${YELLOW}📊 Configuration du monitoring...${NC}"

# Créer un sink de logs
gcloud logging sinks create med-predictor-logs \
    storage.googleapis.com/$PROJECT_ID-logs \
    --log-filter="resource.type=cloud_run_revision AND resource.labels.service_name=$SERVICE_NAME"

echo -e "${GREEN}✅ Monitoring configuré${NC}"
echo ""

# Test de l'application
echo -e "${YELLOW}🧪 Test de l'application...${NC}"

# Attendre que le service soit prêt
sleep 30

# Tester l'endpoint principal
if curl -f "$SERVICE_URL" &> /dev/null; then
    echo -e "${GREEN}✅ Application accessible${NC}"
else
    echo -e "${RED}❌ Application non accessible${NC}"
fi

echo ""

# Résumé final
echo -e "${GREEN}🎉 Déploiement terminé avec succès !${NC}"
echo ""
echo -e "${BLUE}📋 Résumé du déploiement :${NC}"
echo -e "  • Projet : $PROJECT_ID"
echo -e "  • Région : $REGION"
echo -e "  • Service : $SERVICE_NAME"
echo -e "  • Base de données : $DB_INSTANCE"
echo -e "  • URL du service : $SERVICE_URL"
echo -e "  • Domaine personnalisé : https://fit.tbhc.uk"
echo ""
echo -e "${BLUE}🔧 Prochaines étapes :${NC}"
echo -e "  1. Configurer les variables d'environnement dans .env.production"
echo -e "  2. Configurer le DNS pour pointer fit.tbhc.uk vers le service"
echo -e "  3. Tester toutes les fonctionnalités de l'application"
echo -e "  4. Configurer les sauvegardes automatiques"
echo -e "  5. Mettre en place le monitoring et les alertes"
echo ""
echo -e "${BLUE}📧 Support :${NC}"
echo -e "  • Lead Developer : izhar@tbhc.uk"
echo -e "  • Documentation : Voir les guides créés"
echo ""
echo -e "${GREEN}🚀 Votre plateforme FIT est maintenant en ligne !${NC}"
