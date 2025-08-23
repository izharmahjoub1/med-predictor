# 🚀 Configuration du Projet Collaboratif - Med Predictor

## 📋 **Prérequis pour tous les membres**

### **Développeurs :**
- PHP 8.1+
- Composer 2.0+
- Node.js 16+
- MySQL 8.0+
- Git
- IDE (VS Code, PHPStorm, etc.)

### **Testeurs :**
- Navigateur moderne (Chrome, Firefox, Safari)
- Git (basique)
- Accès à l'environnement de test

## 🔧 **Installation initiale**

### **1. Cloner le projet :**
```bash
git clone https://github.com/username/med-predictor.git
cd med-predictor
```

### **2. Installer les dépendances PHP :**
```bash
composer install
```

### **3. Installer les dépendances Node.js :**
```bash
npm install
```

### **4. Configuration de l'environnement :**
```bash
cp .env.example .env
php artisan key:generate
```

### **5. Configuration de la base de données :**
```bash
# Créer la base de données
mysql -u root -p -e "CREATE DATABASE med_predictor;"

# Migrations et seeders
php artisan migrate
php artisan db:seed
```

### **6. Configuration Google Cloud :**
```bash
# Ajouter votre clé API dans .env
GOOGLE_SPEECH_API_KEY=your_google_cloud_api_key
```

### **7. Démarrer le serveur :**
```bash
php artisan serve
```

## 🌿 **Configuration des branches Git**

### **Structure des branches :**
```bash
# Branche principale de développement
git checkout develop-v3

# Branche pour les fonctionnalités
git checkout -b feature/nouvelle-fonctionnalite

# Branche pour les tests QA
git checkout testing/qa-testers

# Branche pour les tests UAT
git checkout testing/user-acceptance
```

### **Workflow de développement :**
```bash
# 1. Créer une branche feature
git checkout develop-v3
git pull origin develop-v3
git checkout -b feature/nouvelle-fonctionnalite

# 2. Développement...
git add .
git commit -m "feat: description de la fonctionnalité"

# 3. Push et Pull Request
git push origin feature/nouvelle-fonctionnalite
# Créer PR vers develop-v3
```

## 🧪 **Configuration des tests**

### **Tests unitaires :**
```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage-html=coverage/html

# Tests spécifiques
php artisan test --filter=ConsoleVocaleTest
```

### **Tests de navigateur :**
```bash
# Tests Laravel Dusk
php artisan dusk

# Tests en mode headless
php artisan dusk --headless
```

### **Tests de qualité :**
```bash
# Qualité PHP
composer run quality:php

# Qualité JavaScript
npm run quality:js

# Qualité CSS
npm run quality:css

# Tous les tests de qualité
npm run quality:all
```

## 📊 **Outils de qualité configurés**

### **PHP :**
- **PHP CS Fixer** : Formatage du code
- **PHPStan** : Analyse statique
- **PHPMD** : Détection de code complexe
- **PHPUnit** : Tests unitaires

### **JavaScript/CSS :**
- **ESLint** : Qualité du code JavaScript
- **Prettier** : Formatage du code
- **Stylelint** : Qualité du CSS

### **CI/CD :**
- **GitHub Actions** : Intégration continue
- **Tests automatisés** : Qualité garantie
- **Déploiement automatique** : Sur develop-v3

## 🔍 **Configuration de l'IDE**

### **VS Code (recommandé) :**
```json
{
  "php.validate.enable": true,
  "php.suggest.basic": false,
  "phpcs.standard": "PSR12",
  "phpstan.enabled": true,
  "eslint.enable": true,
  "prettier.enable": true,
  "stylelint.enable": true
}
```

### **Extensions recommandées :**
- PHP Intelephense
- Laravel Snippets
- PHP CS Fixer
- ESLint
- Prettier
- Stylelint
- GitLens

## 🚨 **Points d'attention**

### **Sécurité :**
- Ne jamais commiter de clés API
- Utiliser les variables d'environnement
- Valider toutes les entrées utilisateur

### **Qualité :**
- Respecter les conventions de code
- Écrire des tests pour chaque fonctionnalité
- Documenter le code complexe

### **Collaboration :**
- Communiquer via GitHub Issues
- Faire des Pull Requests descriptives
- Réviser le code des autres

## 📚 **Documentation disponible**

- `COLLABORATION_GUIDE.md` : Guide de collaboration
- `DEVELOPER_GUIDE.md` : Guide du développeur
- `TESTING_GUIDE.md` : Guide des tests
- `README.md` : Vue d'ensemble du projet

## 🆘 **Support et assistance**

### **Problèmes techniques :**
- Créer une issue GitHub avec le label `bug`
- Inclure logs, étapes de reproduction, environnement

### **Nouvelles fonctionnalités :**
- Créer une issue GitHub avec le label `enhancement`
- Décrire le besoin métier et les critères d'acceptation

### **Questions générales :**
- Utiliser le canal Slack/Discord `#general`
- Ou créer une issue avec le label `question`

## 🎯 **Objectifs de qualité**

- **Couverture de test :** > 80%
- **Temps de résolution des bugs critiques :** < 24h
- **Temps de résolution des bugs haute priorité :** < 72h
- **Taux de régression :** < 5%

## 🚀 **Commandes rapides**

```bash
# Démarrage rapide
npm run dev          # Développement
npm run build        # Production
npm run test         # Tests
npm run quality:all  # Qualité
npm run ci           # Intégration continue

# Composer
composer test        # Tests PHP
composer quality:all # Qualité PHP
composer ci          # CI PHP
```

---

**Dernière mise à jour :** 23 Août 2025  
**Version :** 1.0.0  
**Maintenu par :** Équipe Med Predictor
