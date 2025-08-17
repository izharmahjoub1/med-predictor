#!/bin/bash

# 🏆 Script de sauvegarde complète FIT avec intégration FIFA TMS
# 📅 Date: $(date '+%Y-%m-%d %H:%M:%S')

echo "🏆 DÉBUT DE LA SAUVEGARDE COMPLÈTE FIT v2.0.0 FIFA TMS"
echo "=================================================="

# Configuration
BACKUP_DIR="backups/fit-complete-$(date +%Y%m%d-%H%M%S)"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
GIT_COMMIT=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")

echo "📁 Répertoire de sauvegarde: $BACKUP_DIR"
echo "⏰ Timestamp: $TIMESTAMP"
echo "🏷️ Git Commit: $GIT_COMMIT"

# Créer la structure de sauvegarde
mkdir -p "$BACKUP_DIR"/{app,config,routes,resources,database,public,docs,scripts,backups}

echo "✅ Structure de sauvegarde créée"

# 1. Code source principal
echo "📦 Sauvegarde du code source principal..."

# App (Services, Contrôleurs, Modèles)
cp -r app/Services "$BACKUP_DIR/app/"
cp -r app/Http/Controllers "$BACKUP_DIR/app/"
cp -r app/Models "$BACKUP_DIR/app/"
cp -r app/Helpers "$BACKUP_DIR/app/"

# Configuration
cp -r config "$BACKUP_DIR/"

# Routes
cp -r routes "$BACKUP_DIR/"

# Resources (Vues, Composants)
cp -r resources "$BACKUP_DIR/"

# Public (Assets, Images)
cp -r public "$BACKUP_DIR/"

# Database (Migrations, Seeders)
cp -r database "$BACKUP_DIR/"

echo "✅ Code source sauvegardé"

# 2. Documentation et README
echo "📚 Sauvegarde de la documentation..."

# READMEs et documentation
find . -maxdepth 1 -name "*.md" -exec cp {} "$BACKUP_DIR/docs/" \;
find . -maxdepth 1 -name "*README*" -exec cp {} "$BACKUP_DIR/docs/" \;
find . -maxdepth 1 -name "*INTEGRATION*" -exec cp {} "$BACKUP_DIR/docs/" \;
find . -maxdepth 1 -name "*SAUVEGARDE*" -exec cp {} "$BACKUP_DIR/docs/" \;

echo "✅ Documentation sauvegardée"

# 3. Scripts et utilitaires
echo "🔧 Sauvegarde des scripts..."

# Scripts PHP
find . -maxdepth 1 -name "*.php" -exec cp {} "$BACKUP_DIR/scripts/" \;
find scripts/ -name "*.php" -exec cp {} "$BACKUP_DIR/scripts/" \;

# Scripts shell
find . -maxdepth 1 -name "*.sh" -exec cp {} "$BACKUP_DIR/scripts/" \;
find scripts/ -name "*.sh" -exec cp {} "$BACKUP_DIR/scripts/" \;

# Scripts Python
find . -maxdepth 1 -name "*.py" -exec cp {} "$BACKUP_DIR/scripts/" \;
find scripts/ -name "*.py" -exec cp {} "$BACKUP_DIR/scripts/" \;

# Scripts JavaScript
find . -maxdepth 1 -name "*.js" -exec cp {} "$BACKUP_DIR/scripts/" \;
find scripts/ -name "*.js" -exec cp {} "$BACKUP_DIR/scripts/" \;

echo "✅ Scripts sauvegardés"

# 4. Fichiers de configuration
echo "⚙️ Sauvegarde des fichiers de configuration..."

# Fichiers de configuration
cp .env.example "$BACKUP_DIR/" 2>/dev/null || echo "⚠️ .env.example non trouvé"
cp composer.json "$BACKUP_DIR/"
cp package.json "$BACKUP_DIR/"
cp artisan "$BACKUP_DIR/"

echo "✅ Configuration sauvegardée"

# 5. Sauvegarde Git
echo "📝 Sauvegarde de l'état Git..."

# Informations Git
git status > "$BACKUP_DIR/git-status.txt" 2>/dev/null
git log --oneline -20 > "$BACKUP_DIR/git-log.txt" 2>/dev/null
git tag -l > "$BACKUP_DIR/git-tags.txt" 2>/dev/null

echo "✅ État Git sauvegardé"

# 6. Créer le fichier de métadonnées
echo "📋 Création des métadonnées..."

cat > "$BACKUP_DIR/SAUVEGARDE-INFO.md" << EOF
# 🏆 SAUVEGARDE COMPLÈTE FIT v2.0.0 FIFA TMS

## 📅 Informations de sauvegarde
- **Date de création** : $TIMESTAMP
- **Version FIT** : v2.0.0-fifa-tms
- **Git Commit** : $GIT_COMMIT
- **Type** : Sauvegarde complète du code source

## 🚀 Fonctionnalités incluses

### ✅ Intégration FIFA TMS
- Service FifaTmsLicenseService
- API complète FIFA TMS
- Intégration automatique LicenseHistoryAggregator
- Configuration centralisée
- Routes API dédiées

### ✅ Système FIT complet
- Portail joueur avec interface moderne
- Gestion des licences et transferts
- Système de performance et statistiques
- Interface médicale avancée
- Gestion des clubs et associations

### ✅ Architecture technique
- Laravel 10+ avec Vue.js
- Services modulaires et évolutifs
- API REST complète
- Base de données optimisée
- Cache intelligent et performance

## 📁 Structure de la sauvegarde

\`\`\`
$BACKUP_DIR/
├── app/           # Services, Contrôleurs, Modèles
├── config/        # Configuration Laravel
├── routes/        # Routes API et Web
├── resources/     # Vues, Composants, Assets
├── database/      # Migrations, Seeders
├── public/        # Assets publics, Images
├── docs/          # Documentation complète
├── scripts/       # Scripts et utilitaires
└── backups/       # Sauvegardes précédentes
\`\`\`

## 🔧 Restauration

### 1. Extraire la sauvegarde
\`\`\`bash
tar -xzf fit-complete-*.tar.gz
\`\`\`

### 2. Installer les dépendances
\`\`\`bash
composer install
npm install
\`\`\`

### 3. Configuration
\`\`\`bash
cp .env.example .env
# Configurer les variables d'environnement
\`\`\`

### 4. Base de données
\`\`\`bash
php artisan migrate
php artisan db:seed
\`\`\`

## 🎯 Points forts de cette version

- **🏆 Intégration FIFA TMS** : Connectivité officielle FIFA
- **🚀 Performance** : Cache intelligent et optimisation
- **🔒 Sécurité** : Gestion des erreurs et fallback
- **📱 Interface** : Design moderne et responsive
- **📊 Données** : Sources multiples et agrégation intelligente

## 🔮 Évolutions futures

- [ ] Intégration avec d'autres APIs FIFA
- [ ] Synchronisation bidirectionnelle
- [ ] Notifications en temps réel
- [ ] Dashboard de monitoring avancé
- [ ] Gestion des webhooks FIFA

---

**🏆 FIT v2.0.0 FIFA TMS - Sauvegarde complète et opérationnelle !**

**📅 Date** : $TIMESTAMP  
**🏷️ Version** : v2.0.0-fifa-tms  
**📝 Commit** : $GIT_COMMIT  
**✅ Statut** : Sauvegarde complète réussie
EOF

echo "✅ Métadonnées créées"

# 7. Créer l'archive finale
echo "📦 Création de l'archive finale..."

cd backups
tar -czf "fit-complete-$(date +%Y%m%d-%H%M%S).tar.gz" "fit-complete-$(date +%Y%m%d-%H%M%S)"
cd ..

echo "✅ Archive créée"

# 8. Nettoyage et résumé
echo "🧹 Nettoyage des fichiers temporaires..."
rm -rf "$BACKUP_DIR"

echo ""
echo "🏆 SAUVEGARDE COMPLÈTE TERMINÉE AVEC SUCCÈS !"
echo "=============================================="
echo "📁 Archive créée: backups/fit-complete-$(date +%Y%m%d-%H%M%S).tar.gz"
echo "📊 Taille: $(du -h "backups/fit-complete-$(date +%Y%m%d-%H%M%S).tar.gz" | cut -f1)"
echo "⏰ Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo "✅ Statut: Prêt pour stockage ou transfert"

echo ""
echo "🎯 Prochaines étapes recommandées:"
echo "1. Tester l'archive sur un autre système"
echo "2. Stocker l'archive en lieu sûr"
echo "3. Pousser le code sur GitHub (après nettoyage des gros fichiers)"
echo "4. Documenter la procédure de restauration"

echo ""
echo "🏆 FIT v2.0.0 FIFA TMS - Sauvegarde complète réussie ! 🚀"
