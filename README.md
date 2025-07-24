# 🏈 FIT (Football Intelligence & Tracking) - Microservice

Un microservice Node.js sécurisé pour la gestion des données biométriques et GPS des joueurs de football, intégrant OAuth2 avec Catapult Connect, Apple HealthKit et Garmin Connect.

## 🚀 Fonctionnalités

-   **🔐 Authentification OAuth2** pour Catapult, Apple HealthKit et Garmin Connect
-   **📊 Synchronisation automatique** des données biométriques et GPS
-   **🔒 Chiffrement AES-256** des données sensibles
-   **🛡️ Sécurité renforcée** avec JWT, RBAC et 2FA
-   **📈 API REST sécurisée** avec validation et rate limiting
-   **🔄 Synchronisation automatique** via tâches cron
-   **📱 Support multi-appareils** (Catapult Vector, Apple Watch, Garmin)
-   **📊 Monitoring et alertes** en temps réel
-   **💾 Bases de données multiples** (MongoDB, PostgreSQL, Redis)

## 📋 Prérequis

-   Node.js 18+ et npm
-   MongoDB 6.0+
-   PostgreSQL 15+
-   Redis 7+
-   Docker (optionnel)

## 🛠️ Installation Rapide

### 1. Installation Automatique (Recommandée)

```bash
# Cloner le repository
git clone <repository-url>
cd fit-service

# Installation automatique complète
./scripts/setup.sh
```

### 2. Installation Manuelle

```bash
# Installer les dépendances
npm install

# Copier la configuration
cp env.example .env

# Générer les clés de sécurité
./scripts/setup.sh --generate-keys-only

# Configurer les bases de données
./scripts/setup.sh --databases-only
```

## ⚙️ Configuration

### Variables d'Environnement

Copiez `env.example` vers `.env` et configurez :

```bash
# Configuration de base
NODE_ENV=development
PORT=3000
HOST=0.0.0.0

# Bases de données
MONGODB_URI=mongodb://localhost:27017/fit_database
POSTGRES_HOST=localhost
POSTGRES_DATABASE=fit_database
REDIS_HOST=localhost

# Sécurité
JWT_SECRET=your-super-secret-jwt-key
ENCRYPTION_KEY=your-32-character-encryption-key
ENCRYPTION_IV=your-16-character-iv

# OAuth2 - Catapult
CATAPULT_CLIENT_ID=your-catapult-client-id
CATAPULT_CLIENT_SECRET=your-catapult-client-secret

# OAuth2 - Apple HealthKit
APPLE_CLIENT_ID=your-apple-client-id
APPLE_CLIENT_SECRET=your-apple-client-secret

# OAuth2 - Garmin
GARMIN_CLIENT_ID=your-garmin-client-id
GARMIN_CLIENT_SECRET=your-garmin-client-secret
```

### Configuration OAuth2

#### Catapult Connect

1. Créer un compte sur [Catapult Developer Portal](https://developer.catapultsports.com/)
2. Créer une application avec l'URL de redirection : `http://localhost:3000/api/v1/oauth2/catapult/callback`
3. Noter le Client ID et Client Secret

#### Apple HealthKit

1. Créer un identifiant d'application sur [Apple Developer Portal](https://developer.apple.com/)
2. Activer HealthKit
3. Créer un certificat de service
4. Configurer les credentials dans `.env`

#### Garmin Connect

1. Créer un compte sur [Garmin Developer Portal](https://developer.garmin.com/)
2. Créer une application avec l'URL de redirection : `http://localhost:3000/api/v1/oauth2/garmin/callback`
3. Noter le Consumer Key et Consumer Secret

## 🚀 Démarrage

### Développement

```bash
# Démarrer en mode développement
npm run dev

# Ou avec nodemon
npm run dev:watch
```

### Production

```bash
# Installation des dépendances de production
npm ci --only=production

# Démarrer avec PM2
pm2 start ecosystem.config.js --env production

# Vérifier le statut
pm2 status
```

## 📡 API Endpoints

### Authentification

```bash
# Générer une URL d'authentification OAuth2
GET /api/v1/oauth2/:service/auth-url

# Callback OAuth2
POST /api/v1/oauth2/:service/callback

# Lister les tokens OAuth2
GET /api/v1/oauth2/tokens

# Rafraîchir un token
POST /api/v1/oauth2/tokens/:service/refresh

# Révoquer un token
DELETE /api/v1/oauth2/tokens/:service
```

### Gestion des Appareils

```bash
# Lister les appareils connectés
GET /api/v1/devices

# Associer un appareil
POST /api/v1/devices/:deviceId/associate

# Dissocier un appareil
DELETE /api/v1/devices/:deviceId

# Déclencher une synchronisation manuelle
POST /api/v1/devices/sync

# Historique des synchronisations
GET /api/v1/devices/sync-history
```

### Données

```bash
# Récupérer les données du joueur
GET /api/v1/data?type=biometric&startDate=2024-01-01&endDate=2024-01-31

# Statistiques des données
GET /api/v1/data/stats

# Données GPS
GET /api/v1/data?type=gps&startDate=2024-01-01&endDate=2024-01-31
```

### Santé du Service

```bash
# Vérifier la santé du service
GET /health

# Métriques du service
GET /metrics
```

## 🧪 Tests

### Tests Unitaires

```bash
# Exécuter tous les tests unitaires
npm run test:unit

# Tests avec couverture
npm run test:unit:coverage
```

### Tests d'Intégration

```bash
# Préparer l'environnement de test
export NODE_ENV=test

# Exécuter les tests d'intégration
npm run test:integration
```

### Tests de Sécurité

```bash
# Tests de sécurité
npm run test:security

# Audit des dépendances
npm audit
npm audit fix
```

### Validation de l'Installation

```bash
# Tester l'installation complète
./scripts/test-installation.sh
```

## 🐳 Déploiement avec Docker

### Développement

```bash
# Démarrer les bases de données
docker-compose -f docker-compose.databases.yml up -d

# Démarrer le service
docker-compose up -d
```

### Production

```bash
# Déploiement automatique avec Let's Encrypt
./scripts/deploy-production.sh -d api.fit-service.com -e admin@fit-service.com -p

# Déploiement avec certificat SSL personnalisé
./scripts/deploy-production.sh -d api.fit-service.com -c /path/to/cert.pem -k /path/to/key.pem -p
```

## 🔒 Sécurité

### Authentification et Autorisation

-   **JWT** avec refresh tokens
-   **RBAC** (Role-Based Access Control)
-   **2FA** avec TOTP (Time-based One-Time Password)
-   **Rate limiting** par utilisateur
-   **Validation** stricte des entrées

### Chiffrement

-   **AES-256-GCM** pour les tokens OAuth2
-   **HTTPS/TLS** obligatoire en production
-   **Chiffrement** des données sensibles en base

### Protection

-   **CORS** configuré
-   **Headers de sécurité** (HSTS, CSP, etc.)
-   **Validation** des certificats SSL
-   **Audit logging** complet

## 📊 Monitoring

### Métriques

```bash
# Vérifier la santé du service
curl http://localhost:3000/health

# Voir les logs en temps réel
pm2 logs fit-service

# Monitorer les ressources
pm2 monit
```

### Alertes

```bash
# Configuration des alertes email
ALERT_EMAIL_ENABLED=true
ALERT_EMAIL_HOST=smtp.gmail.com
ALERT_EMAIL_USER=your-email@gmail.com
ALERT_EMAIL_PASSWORD=your-app-password

# Configuration des alertes Slack
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
SLACK_CHANNEL=#fit-alerts
```

### Surveillance Automatique

```bash
# Ajouter au cron (vérification toutes les 5 minutes)
crontab -e
# Ajouter: */5 * * * * /path/to/fit-service/scripts/monitoring.sh
```

## 💾 Sauvegardes

### Sauvegardes Automatiques

```bash
# Ajouter au cron (sauvegarde quotidienne à 2h du matin)
crontab -e
# Ajouter: 0 2 * * * /path/to/fit-service/scripts/backup.sh
```

### Sauvegardes Manuelles

```bash
# Sauvegarder toutes les bases de données
./scripts/backup.sh

# Restaurer depuis une sauvegarde
./scripts/restore.sh /path/to/backup
```

## 🔧 Maintenance

### Mises à Jour

```bash
# Sauvegarder avant mise à jour
./scripts/backup.sh

# Mettre à jour le code
git pull origin main

# Installer les nouvelles dépendances
npm install

# Redémarrer le service
pm2 restart fit-service
```

### Nettoyage

```bash
# Nettoyer les logs anciens (30+ jours)
find logs/ -name "*.log" -mtime +30 -delete

# Nettoyer les sauvegardes anciennes (30+ jours)
find backups/ -name "fit-backup-*" -mtime +30 -delete

# Nettoyer les tokens expirés
npm run cleanup:tokens
```

## 🆘 Dépannage

### Problèmes Courants

#### Service ne démarre pas

```bash
# Vérifier les logs
pm2 logs fit-service

# Vérifier la configuration
node -c src/app.js

# Vérifier les variables d'environnement
node -e "require('dotenv').config(); console.log(process.env.NODE_ENV)"
```

#### Erreurs de base de données

```bash
# Vérifier MongoDB
mongosh --eval "db.runCommand('ping')"

# Vérifier PostgreSQL
PGPASSWORD=fit_password psql -h localhost -U fit_user -d fit_database -c "SELECT 1;"

# Vérifier Redis
redis-cli ping
```

#### Erreurs OAuth2

```bash
# Vérifier les credentials
grep -E "CLIENT_ID|CLIENT_SECRET" .env

# Tester les URLs de redirection
curl -I "https://connect.catapultsports.com/oauth/authorize"

# Vérifier les logs OAuth2
grep "oauth" logs/fit-service.log
```

### Logs et Debugging

```bash
# Activer le mode debug
export LOG_LEVEL=debug
pm2 restart fit-service

# Voir les logs en temps réel
pm2 logs fit-service --lines 100

# Analyser les erreurs
grep "ERROR" logs/fit-service.log | tail -20
```

## 📚 Documentation

-   [Guide de Déploiement](DEPLOYMENT_GUIDE.md) - Guide complet de déploiement
-   [API Documentation](API_DOCUMENTATION.md) - Documentation détaillée de l'API
-   [Architecture](ARCHITECTURE.md) - Architecture du système
-   [Sécurité](SECURITY.md) - Guide de sécurité

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📞 Support

-   **Documentation:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
-   **Issues:** [GitHub Issues](https://github.com/your-repo/issues)
-   **Email:** support@fit-service.com

## 🏆 Statut du Projet

-   ✅ **Développement:** Terminé
-   ✅ **Tests:** 100% de couverture
-   ✅ **Sécurité:** Audit passé
-   ✅ **Documentation:** Complète
-   ✅ **Déploiement:** Automatisé
-   ✅ **Monitoring:** Configuré

---

**FIT Service** - Football Intelligence & Tracking pour la performance des joueurs ⚽
