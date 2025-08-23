# 🚀 Guide de Déploiement - Plateforme FIT sur fit.tbhc.uk

## 🎯 **Objectif**

Déployer la plateforme Med Predictor FIT sur le domaine `fit.tbhc.uk` en utilisant Google Workspace pour l'hébergement et la gestion.

## 🌐 **Configuration du domaine**

### **1. Vérification du domaine tbhc.uk**
- **Domaine principal :** `tbhc.uk`
- **Sous-domaine cible :** `fit.tbhc.uk`
- **Registrar :** Vérifier que vous avez accès à la gestion DNS

### **2. Configuration Google Workspace**
- **Compte Google Workspace :** `admin@tbhc.uk`
- **Services activés :** Gmail, Drive, Sites, Cloud Platform
- **Utilisateurs :** Créer les comptes pour l'équipe

## 🏗️ **Options de déploiement**

### **Option 1 : Google Cloud Platform (Recommandée)**
- **Avantages :** Scalabilité, performance, intégration Google
- **Coût :** ~$50-200/mois selon l'usage
- **Complexité :** Moyenne

### **Option 2 : Google Cloud Run**
- **Avantages :** Serverless, auto-scaling, économique
- **Coût :** ~$20-100/mois
- **Complexité :** Faible

### **Option 3 : Google App Engine**
- **Avantages :** Gestion automatique, monitoring intégré
- **Coût :** ~$30-150/mois
- **Complexité :** Faible

## 🔧 **Déploiement Google Cloud Platform**

### **1. Configuration du projet GCP**
```bash
# Installer Google Cloud CLI
curl https://sdk.cloud.google.com | bash
exec -l $SHELL

# Initialiser le projet
gcloud init
gcloud config set project med-predictor-fit

# Activer les APIs nécessaires
gcloud services enable cloudbuild.googleapis.com
gcloud services enable run.googleapis.com
gcloud services enable sqladmin.googleapis.com
gcloud services enable compute.googleapis.com
```

### **2. Configuration de la base de données**
```bash
# Créer une instance Cloud SQL
gcloud sql instances create med-predictor-db \
    --database-version=MYSQL_8_0 \
    --tier=db-f1-micro \
    --region=europe-west1 \
    --root-password=YOUR_STRONG_PASSWORD

# Créer la base de données
gcloud sql databases create med_predictor \
    --instance=med-predictor-db

# Créer un utilisateur
gcloud sql users create med_predictor_user \
    --instance=med-predictor-db \
    --password=USER_PASSWORD
```

### **3. Configuration des variables d'environnement**
```bash
# Créer un fichier .env.production
cat > .env.production << EOF
APP_NAME="Med Predictor FIT"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fit.tbhc.uk

DB_CONNECTION=mysql
DB_HOST=/cloudsql/PROJECT_ID:europe-west1:med-predictor-db
DB_PORT=3306
DB_DATABASE=med_predictor
DB_USERNAME=med_predictor_user
DB_PASSWORD=USER_PASSWORD

GOOGLE_SPEECH_API_KEY=your_google_cloud_api_key
GOOGLE_CLOUD_PROJECT=med-predictor-fit

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
EOF
```

### **4. Configuration Docker**
```dockerfile
# Dockerfile
FROM php:8.1-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du projet
COPY . /var/www

# Installer les dépendances
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Configurer les permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# Exposer le port 80
EXPOSE 80

# Démarrer les services
CMD ["php-fpm"]
```

### **5. Configuration Nginx**
```nginx
# nginx.conf
server {
    listen 80;
    server_name fit.tbhc.uk;
    root /var/www/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### **6. Déploiement avec Cloud Build**
```yaml
# cloudbuild.yaml
steps:
  # Build de l'image Docker
  - name: 'gcr.io/cloud-builders/docker'
    args: ['build', '-t', 'gcr.io/$PROJECT_ID/med-predictor:$COMMIT_SHA', '.']
  
  # Push de l'image
  - name: 'gcr.io/cloud-builders/docker'
    args: ['push', 'gcr.io/$PROJECT_ID/med-predictor:$COMMIT_SHA']
  
  # Déploiement sur Cloud Run
  - name: 'gcr.io/google.com/cloudsdktool/cloud-sdk'
    entrypoint: gcloud
    args:
      - 'run'
      - 'deploy'
      - 'med-predictor'
      - '--image'
      - 'gcr.io/$PROJECT_ID/med-predictor:$COMMIT_SHA'
      - '--region'
      - 'europe-west1'
      - '--platform'
      - 'managed'
      - '--allow-unauthenticated'

images:
  - 'gcr.io/$PROJECT_ID/med-predictor:$COMMIT_SHA'
```

## 🔐 **Configuration SSL et domaine**

### **1. Configuration DNS**
```bash
# Ajouter les enregistrements DNS dans votre registrar
# Type A : fit.tbhc.uk -> IP_GOOGLE_CLOUD
# Type CNAME : www.fit.tbhc.uk -> fit.tbhc.uk
```

### **2. Configuration SSL automatique**
```bash
# Google Cloud gère automatiquement les certificats SSL
# Assurez-vous que le domaine pointe vers votre instance
```

## 📧 **Configuration email Google Workspace**

### **1. Configuration SMTP**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@tbhc.uk
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tbhc.uk
MAIL_FROM_NAME="Med Predictor FIT"
```

### **2. Création des comptes utilisateurs**
```bash
# Dans Google Workspace Admin
# Créer les comptes pour l'équipe :
# - izhar@tbhc.uk (Lead Developer)
# - dev2@tbhc.uk (Développeur)
# - qa@tbhc.uk (Tester QA)
# - uat@tbhc.uk (Tester UAT)
```

## 🚀 **Script de déploiement automatisé**

### **1. Script de déploiement**
```bash
#!/bin/bash
# deploy.sh

set -e

echo "🚀 Déploiement de Med Predictor FIT..."

# Variables
PROJECT_ID="med-predictor-fit"
REGION="europe-west1"
SERVICE_NAME="med-predictor"

# Build et déploiement
echo "📦 Build de l'application..."
gcloud builds submit --tag gcr.io/$PROJECT_ID/$SERVICE_NAME

echo "🚀 Déploiement sur Cloud Run..."
gcloud run deploy $SERVICE_NAME \
    --image gcr.io/$PROJECT_ID/$SERVICE_NAME \
    --platform managed \
    --region $REGION \
    --allow-unauthenticated \
    --set-env-vars "APP_ENV=production" \
    --set-env-vars "APP_URL=https://fit.tbhc.uk"

echo "✅ Déploiement terminé !"
echo "🌐 URL : https://fit.tbhc.uk"
```

### **2. Script de migration de base**
```bash
#!/bin/bash
# migrate.sh

echo "🗄️ Migration de la base de données..."

# Exécuter les migrations
gcloud run jobs create migrate-med-predictor \
    --image gcr.io/$PROJECT_ID/med-predictor \
    --region $REGION \
    --command="php" \
    --args="artisan,migrate,--force"

echo "✅ Migration terminée !"
```

## 📊 **Monitoring et maintenance**

### **1. Configuration Google Cloud Monitoring**
```bash
# Activer le monitoring
gcloud services enable monitoring.googleapis.com

# Créer des alertes
gcloud alpha monitoring policies create \
    --policy-from-file=monitoring-policy.yaml
```

### **2. Configuration des logs**
```bash
# Activer Cloud Logging
gcloud services enable logging.googleapis.com

# Configurer la rétention des logs
gcloud logging sinks create med-predictor-logs \
    storage.googleapis.com/$PROJECT_ID-logs \
    --log-filter="resource.type=cloud_run_revision"
```

## 🔄 **Pipeline CI/CD avec GitHub Actions**

### **1. Configuration GitHub Actions**
```yaml
# .github/workflows/deploy.yml
name: Deploy to Google Cloud

on:
  push:
    branches: [ main, develop-v3 ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup Google Cloud
      uses: google-github-actions/setup-gcloud@v0
      with:
        project_id: ${{ secrets.GCP_PROJECT_ID }}
        service_account_key: ${{ secrets.GCP_SA_KEY }}
    
    - name: Deploy to Cloud Run
      run: |
        gcloud run deploy med-predictor \
          --image gcr.io/${{ secrets.GCP_PROJECT_ID }}/med-predictor:${{ github.sha }} \
          --region europe-west1 \
          --platform managed \
          --allow-unauthenticated
```

## 📋 **Checklist de déploiement**

### **Pré-déploiement :**
- [ ] **Vérifier le domaine** `fit.tbhc.uk` est accessible
- [ ] **Configurer Google Workspace** avec les comptes utilisateurs
- [ ] **Préparer la base de données** Cloud SQL
- [ ] **Configurer les variables d'environnement** de production
- [ ] **Tester localement** avec Docker

### **Déploiement :**
- [ ] **Déployer sur Google Cloud Platform**
- [ ] **Configurer le domaine** et SSL
- [ ] **Migrer la base de données**
- [ ] **Tester l'application** en production
- [ ] **Configurer le monitoring** et les alertes

### **Post-déploiement :**
- [ ] **Former l'équipe** à l'utilisation
- [ ] **Configurer les sauvegardes** automatiques
- [ ] **Mettre en place le support** utilisateur
- [ ] **Documenter les procédures** de maintenance

## 💰 **Estimation des coûts**

### **Google Cloud Platform :**
- **Cloud Run :** $20-100/mois
- **Cloud SQL :** $30-150/mois
- **Cloud Build :** $10-50/mois
- **Monitoring :** $10-30/mois
- **Total estimé :** $70-330/mois

### **Google Workspace :**
- **5 utilisateurs :** $25-50/mois
- **Total estimé :** $95-380/mois

## 🆘 **Support et maintenance**

### **Contact :**
- **Lead Developer :** `izhar@tbhc.uk`
- **Support technique :** `support@tbhc.uk`
- **Documentation :** Voir les guides créés

### **Maintenance :**
- **Sauvegardes :** Quotidiennes automatiques
- **Mises à jour :** Mensuelles planifiées
- **Monitoring :** 24/7 avec alertes

---

**🎯 Votre plateforme FIT sera bientôt accessible sur fit.tbhc.uk !**

**📧 Contact :** `izhar@tbhc.uk`  
**🌐 URL :** `https://fit.tbhc.uk`  
**📚 Documentation :** Voir les guides de déploiement

**Dernière mise à jour :** 23 Août 2025  
**Version :** 1.0.0  
**Statut :** 🚀 Prêt pour le déploiement
