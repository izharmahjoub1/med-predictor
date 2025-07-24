# Guide de Déploiement et Configuration - Microservice FIT

## Table des matières

1. [Installation et Configuration Initiale](#installation-et-configuration-initiale)
2. [Configuration des Bases de Données](#configuration-des-bases-de-données)
3. [Configuration OAuth2](#configuration-oauth2)
4. [Déploiement en Production](#déploiement-en-production)
5. [Configuration SSL/TLS](#configuration-ssltls)
6. [Tests et Validation](#tests-et-validation)
7. [Monitoring et Alertes](#monitoring-et-alertes)
8. [Maintenance et Sauvegardes](#maintenance-et-sauvegardes)
9. [Dépannage](#dépannage)

---

## 1. Installation et Configuration Initiale

### Prérequis

-   Node.js 18+ et npm
-   Docker (optionnel, pour les bases de données)
-   Git

### Installation Automatique

```bash
# Cloner le repository
git clone <repository-url>
cd fit-service

# Rendre le script d'installation exécutable
chmod +x scripts/setup.sh

# Exécuter l'installation automatique
./scripts/setup.sh
```

### Installation Manuelle

```bash
# 1. Installer les dépendances
npm install

# 2. Copier le fichier d'environnement
cp env.example .env

# 3. Générer les clés de sécurité
openssl rand -base64 64 > .env.jwt_secret
openssl rand -hex 32 > .env.encryption_key
openssl rand -hex 16 > .env.encryption_iv
openssl rand -base64 32 > .env.totp_secret

# 4. Mettre à jour .env avec les clés générées
sed -i "s/JWT_SECRET=.*/JWT_SECRET=$(cat .env.jwt_secret)/" .env
sed -i "s/ENCRYPTION_KEY=.*/ENCRYPTION_KEY=$(cat .env.encryption_key)/" .env
sed -i "s/ENCRYPTION_IV=.*/ENCRYPTION_IV=$(cat .env.encryption_iv)/" .env
sed -i "s/TOTP_SECRET=.*/TOTP_SECRET=$(cat .env.totp_secret)/" .env

# 5. Nettoyer les fichiers temporaires
rm .env.jwt_secret .env.encryption_key .env.encryption_iv .env.totp_secret
```

---

## 2. Configuration des Bases de Données

### Option A: Installation via Docker (Recommandée)

```bash
# Démarrer les bases de données
docker-compose -f docker-compose.databases.yml up -d

# Vérifier le statut
docker-compose -f docker-compose.databases.yml ps
```

### Option B: Installation Manuelle

#### MongoDB

**Ubuntu/Debian:**

```bash
# Installer MongoDB
wget -qO - https://www.mongodb.org/static/pgp/server-6.0.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/6.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-6.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org

# Démarrer MongoDB
sudo systemctl start mongod
sudo systemctl enable mongod

# Créer la base de données
mongosh --eval "use fit_database"
```

**macOS:**

```bash
# Installer via Homebrew
brew tap mongodb/brew
brew install mongodb-community

# Démarrer MongoDB
brew services start mongodb-community

# Créer la base de données
mongosh --eval "use fit_database"
```

#### PostgreSQL

**Ubuntu/Debian:**

```bash
# Installer PostgreSQL
sudo apt-get update
sudo apt-get install -y postgresql postgresql-contrib

# Démarrer PostgreSQL
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Créer l'utilisateur et la base de données
sudo -u postgres createuser -P fit_user
sudo -u postgres createdb -O fit_user fit_database
```

**macOS:**

```bash
# Installer via Homebrew
brew install postgresql

# Démarrer PostgreSQL
brew services start postgresql

# Créer l'utilisateur et la base de données
createuser -P fit_user
createdb -O fit_user fit_database
```

#### Redis

**Ubuntu/Debian:**

```bash
# Installer Redis
sudo apt-get update
sudo apt-get install -y redis-server

# Démarrer Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

**macOS:**

```bash
# Installer via Homebrew
brew install redis

# Démarrer Redis
brew services start redis
```

### Vérification de la Connectivité

```bash
# Vérifier MongoDB
mongosh --eval "db.runCommand('ping')"

# Vérifier PostgreSQL
PGPASSWORD=fit_password psql -h localhost -U fit_user -d fit_database -c "SELECT 1;"

# Vérifier Redis
redis-cli ping
```

---

## 3. Configuration OAuth2

### Catapult Connect

1. **Créer un compte développeur:**

    - Aller sur [Catapult Developer Portal](https://developer.catapultsports.com/)
    - Créer un compte développeur

2. **Créer une application:**

    - Créer une nouvelle application
    - Configurer les URLs de redirection: `http://localhost:3000/api/v1/oauth2/catapult/callback`
    - Noter le Client ID et Client Secret

3. **Mettre à jour .env:**

```bash
CATAPULT_CLIENT_ID=your-catapult-client-id
CATAPULT_CLIENT_SECRET=your-catapult-client-secret
```

### Apple HealthKit

1. **Créer un identifiant d'application:**

    - Aller sur [Apple Developer Portal](https://developer.apple.com/)
    - Créer un nouvel identifiant d'application
    - Activer HealthKit

2. **Créer un certificat de service:**

    - Créer un certificat de service pour HealthKit
    - Télécharger le certificat et la clé privée

3. **Mettre à jour .env:**

```bash
APPLE_CLIENT_ID=your-apple-client-id
APPLE_CLIENT_SECRET=your-apple-client-secret
```

### Garmin Connect

1. **Créer un compte développeur:**

    - Aller sur [Garmin Developer Portal](https://developer.garmin.com/)
    - Créer un compte développeur

2. **Créer une application:**

    - Créer une nouvelle application
    - Configurer les URLs de redirection: `http://localhost:3000/api/v1/oauth2/garmin/callback`
    - Noter le Consumer Key et Consumer Secret

3. **Mettre à jour .env:**

```bash
GARMIN_CLIENT_ID=your-garmin-client-id
GARMIN_CLIENT_SECRET=your-garmin-client-secret
```

---

## 4. Déploiement en Production

### Préparation

```bash
# 1. Configurer l'environnement de production
cp .env .env.production

# 2. Mettre à jour les variables de production
sed -i 's/NODE_ENV=development/NODE_ENV=production/' .env.production
sed -i 's/LOG_LEVEL=info/LOG_LEVEL=warn/' .env.production
```

### Déploiement Automatique

```bash
# Déploiement avec Let's Encrypt
./scripts/deploy-production.sh -d api.fit-service.com -e admin@fit-service.com -p

# Déploiement avec certificat SSL personnalisé
./scripts/deploy-production.sh -d api.fit-service.com -c /path/to/cert.pem -k /path/to/key.pem -p
```

### Déploiement Manuel

```bash
# 1. Installer PM2
npm install -g pm2

# 2. Installer les dépendances de production
npm ci --only=production

# 3. Démarrer le service
pm2 start ecosystem.config.js --env production

# 4. Sauvegarder la configuration PM2
pm2 save

# 5. Configurer le démarrage automatique
pm2 startup
```

---

## 5. Configuration SSL/TLS

### Option A: Let's Encrypt (Gratuit)

```bash
# 1. Installer Certbot
sudo apt-get update
sudo apt-get install -y certbot

# 2. Obtenir le certificat
sudo certbot certonly --standalone \
    --email admin@fit-service.com \
    --agree-tos \
    --no-eff-email \
    -d api.fit-service.com

# 3. Configurer le renouvellement automatique
sudo crontab -e
# Ajouter: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Option B: Certificat Commercial

1. **Obtenir un certificat SSL** d'un fournisseur (DigiCert, GlobalSign, etc.)
2. **Placer les fichiers:**

```bash
sudo mkdir -p /etc/ssl/fit-service
sudo cp certificate.pem /etc/ssl/fit-service/
sudo cp private-key.pem /etc/ssl/fit-service/
sudo chown -R $USER:$USER /etc/ssl/fit-service
sudo chmod 600 /etc/ssl/fit-service/private-key.pem
```

### Configuration Nginx

```bash
# 1. Installer Nginx
sudo apt-get install -y nginx

# 2. Copier la configuration
sudo cp nginx-fit-service.conf /etc/nginx/sites-available/fit-service

# 3. Activer le site
sudo ln -s /etc/nginx/sites-available/fit-service /etc/nginx/sites-enabled/

# 4. Tester la configuration
sudo nginx -t

# 5. Redémarrer Nginx
sudo systemctl reload nginx
```

---

## 6. Tests et Validation

### Tests Unitaires

```bash
# Exécuter tous les tests unitaires
npm run test:unit

# Exécuter un test spécifique
npm run test:unit -- --grep "OAuth2Service"
```

### Tests d'Intégration

```bash
# Préparer l'environnement de test
export NODE_ENV=test
export TEST_DATABASE_URL=mongodb://localhost:27017/fit_test_database

# Exécuter les tests d'intégration
npm run test:integration
```

### Tests de Sécurité

```bash
# Exécuter les tests de sécurité
npm run test:security

# Audit des dépendances
npm audit
npm audit fix
```

### Tests de Performance

```bash
# Installer Artillery
npm install -g artillery

# Exécuter les tests de charge
artillery run tests/performance/load-test.yml
```

### Validation Manuelle

```bash
# 1. Vérifier le service de santé
curl http://localhost:3000/health

# 2. Tester l'authentification
curl -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     http://localhost:3000/api/v1/oauth2/services

# 3. Tester la synchronisation
curl -X POST -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     http://localhost:3000/api/v1/devices/sync
```

---

## 7. Monitoring et Alertes

### Configuration du Monitoring

```bash
# 1. Rendre le script de monitoring exécutable
chmod +x scripts/monitoring.sh

# 2. Ajouter au cron (vérification toutes les 5 minutes)
crontab -e
# Ajouter: */5 * * * * /path/to/fit-service/scripts/monitoring.sh
```

### Configuration des Alertes Email

```bash
# 1. Installer Postfix (si pas déjà installé)
sudo apt-get install -y postfix

# 2. Configurer les variables d'alerte dans .env
ALERT_EMAIL_ENABLED=true
ALERT_EMAIL_HOST=smtp.gmail.com
ALERT_EMAIL_PORT=587
ALERT_EMAIL_USER=your-email@gmail.com
ALERT_EMAIL_PASSWORD=your-app-password
ALERT_EMAIL_FROM=fit-alerts@yourdomain.com
```

### Configuration des Alertes Slack

```bash
# 1. Créer un webhook Slack
# Aller sur https://api.slack.com/apps
# Créer une nouvelle app et configurer un webhook

# 2. Mettre à jour .env
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
SLACK_CHANNEL=#fit-alerts
```

### Métriques et Logs

```bash
# 1. Voir les logs en temps réel
pm2 logs fit-service

# 2. Monitorer les ressources
pm2 monit

# 3. Voir les statistiques
pm2 show fit-service

# 4. Voir les logs d'application
tail -f logs/fit-service.log
```

---

## 8. Maintenance et Sauvegardes

### Sauvegardes Automatiques

```bash
# 1. Rendre le script de sauvegarde exécutable
chmod +x scripts/backup.sh

# 2. Ajouter au cron (sauvegarde quotidienne à 2h du matin)
crontab -e
# Ajouter: 0 2 * * * /path/to/fit-service/scripts/backup.sh
```

### Sauvegardes Manuelles

```bash
# Sauvegarder MongoDB
mongodump --uri="mongodb://localhost:27017/fit_database" --out=./backups/manual-mongo

# Sauvegarder PostgreSQL
PGPASSWORD=fit_password pg_dump -h localhost -U fit_user fit_database > ./backups/manual-postgres.sql

# Sauvegarder Redis
redis-cli --rdb ./backups/manual-redis.rdb

# Sauvegarder les fichiers de configuration
tar -czf ./backups/manual-config.tar.gz .env.production ecosystem.config.js
```

### Restauration

```bash
# Restaurer MongoDB
mongorestore --uri="mongodb://localhost:27017/fit_database" ./backups/manual-mongo

# Restaurer PostgreSQL
PGPASSWORD=fit_password psql -h localhost -U fit_user fit_database < ./backups/manual-postgres.sql

# Restaurer Redis
redis-cli --rdb ./backups/manual-redis.rdb

# Restaurer la configuration
tar -xzf ./backups/manual-config.tar.gz
```

### Mises à Jour

```bash
# 1. Sauvegarder avant mise à jour
./scripts/backup.sh

# 2. Arrêter le service
pm2 stop fit-service

# 3. Mettre à jour le code
git pull origin main

# 4. Installer les nouvelles dépendances
npm install

# 5. Redémarrer le service
pm2 start fit-service

# 6. Vérifier le statut
pm2 status
curl http://localhost:3000/health
```

---

## 9. Dépannage

### Problèmes Courants

#### Service ne démarre pas

```bash
# 1. Vérifier les logs
pm2 logs fit-service

# 2. Vérifier la configuration
node -c src/app.js

# 3. Vérifier les variables d'environnement
node -e "require('dotenv').config(); console.log(process.env.NODE_ENV)"
```

#### Erreurs de base de données

```bash
# 1. Vérifier la connectivité MongoDB
mongosh --eval "db.runCommand('ping')"

# 2. Vérifier la connectivité PostgreSQL
PGPASSWORD=fit_password psql -h localhost -U fit_user -d fit_database -c "SELECT 1;"

# 3. Vérifier la connectivité Redis
redis-cli ping
```

#### Erreurs OAuth2

```bash
# 1. Vérifier les credentials
grep -E "CLIENT_ID|CLIENT_SECRET" .env

# 2. Tester les URLs de redirection
curl -I "https://connect.catapultsports.com/oauth/authorize"

# 3. Vérifier les logs OAuth2
grep "oauth" logs/fit-service.log
```

#### Problèmes SSL/TLS

```bash
# 1. Vérifier les certificats
openssl x509 -in /etc/ssl/fit-service/certificate.pem -text -noout

# 2. Vérifier la configuration Nginx
sudo nginx -t

# 3. Vérifier les permissions
ls -la /etc/ssl/fit-service/
```

### Logs et Debugging

```bash
# 1. Activer le mode debug
export LOG_LEVEL=debug
pm2 restart fit-service

# 2. Voir les logs en temps réel
pm2 logs fit-service --lines 100

# 3. Analyser les erreurs
grep "ERROR" logs/fit-service.log | tail -20

# 4. Vérifier les performances
pm2 monit
```

### Support et Contact

Pour obtenir de l'aide supplémentaire :

1. **Documentation:** Consultez les fichiers README.md et les commentaires dans le code
2. **Logs:** Vérifiez les logs dans `logs/fit-service.log`
3. **Tests:** Exécutez les tests pour identifier les problèmes
4. **Monitoring:** Utilisez les scripts de monitoring pour diagnostiquer les problèmes

---

## Commandes Utiles

### Gestion du Service

```bash
# Démarrer le service
pm2 start fit-service

# Arrêter le service
pm2 stop fit-service

# Redémarrer le service
pm2 restart fit-service

# Voir le statut
pm2 status

# Voir les logs
pm2 logs fit-service

# Monitorer en temps réel
pm2 monit
```

### Gestion des Bases de Données

```bash
# MongoDB
mongosh fit_database
db.stats()

# PostgreSQL
psql -h localhost -U fit_user -d fit_database
\dt

# Redis
redis-cli
INFO
```

### Maintenance

```bash
# Sauvegarde
./scripts/backup.sh

# Monitoring
./scripts/monitoring.sh

# Nettoyage des logs
find logs/ -name "*.log" -mtime +30 -delete

# Nettoyage des sauvegardes
find backups/ -name "fit-backup-*" -mtime +30 -delete
```

---

Ce guide couvre l'ensemble du processus de déploiement et de configuration du microservice FIT. Suivez les étapes dans l'ordre et assurez-vous de tester chaque étape avant de passer à la suivante.
