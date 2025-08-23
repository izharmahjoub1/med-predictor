# 🚀 Guide de Collaboration - Med Predictor

## 👥 **Équipe de développement**

### **Développeurs :**
- **Izhar Mahjoub** (Lead Developer) - `izhar@med-predictor.com`
- **Développeur 2** - `dev2@med-predictor.com`

### **Testeurs :**
- **Tester QA** - `qa@med-predictor.com`
- **Tester User Acceptance** - `uat@med-predictor.com`

## 🌿 **Structure des branches Git**

```
main (production)
├── develop-v3 (développement principal)
├── feature/console-vocale (fonctionnalités vocales)
├── testing/qa-testers (tests QA)
└── testing/user-acceptance (tests UAT)
```

## 📝 **Conventions de commit**

### **Format :**
```
<type>(<scope>): <description>

<body>

<footer>
```

### **Types :**
- `feat:` - Nouvelle fonctionnalité
- `fix:` - Correction de bug
- `docs:` - Documentation
- `style:` - Formatage du code
- `refactor:` - Refactoring
- `test:` - Tests
- `chore:` - Maintenance

### **Exemples :**
```bash
git commit -m "feat(console-vocale): Ajout reconnaissance Google Cloud Speech-to-Text

- Intégration API Google Cloud
- Interface utilisateur pour tests
- Gestion des erreurs et fallbacks

Closes #123"
```

## 🔄 **Workflow de développement**

### **1. Développement de fonctionnalités :**
```bash
git checkout develop-v3
git pull origin develop-v3
git checkout -b feature/nouvelle-fonctionnalite
# Développement...
git commit -m "feat: description"
git push origin feature/nouvelle-fonctionnalite
# Créer Pull Request vers develop-v3
```

### **2. Tests QA :**
```bash
git checkout testing/qa-testers
git merge develop-v3
# Tests automatisés et manuels
git commit -m "test(qa): validation fonctionnalités console vocale"
git push origin testing/qa-testers
```

### **3. Tests UAT :**
```bash
git checkout testing/user-acceptance
git merge testing/qa-testers
# Tests utilisateur finaux
git commit -m "test(uat): validation utilisateur console vocale"
git push origin testing/user-acceptance
```

### **4. Déploiement production :**
```bash
git checkout main
git merge testing/user-acceptance
git tag -a v1.2.0 -m "Release: Console vocale Google Cloud"
git push origin main --tags
```

## 🧪 **Processus de test**

### **Tests QA (Tester 1) :**
- ✅ Tests unitaires
- ✅ Tests d'intégration
- ✅ Tests de régression
- ✅ Tests de performance
- ✅ Validation des fonctionnalités

### **Tests UAT (Tester 2) :**
- ✅ Tests utilisateur finaux
- ✅ Validation des parcours utilisateur
- ✅ Tests d'acceptation
- ✅ Validation des exigences métier

## 📊 **Outils de collaboration**

### **Communication :**
- **Slack/Discord** : Communication en temps réel
- **Email** : Communications formelles
- **GitHub Issues** : Suivi des tâches

### **Gestion de projet :**
- **GitHub Projects** : Kanban board
- **GitHub Issues** : Suivi des bugs et fonctionnalités
- **GitHub Actions** : CI/CD automatisé

### **Documentation :**
- **README.md** : Vue d'ensemble du projet
- **API_DOCS.md** : Documentation des APIs
- **TESTING_GUIDE.md** : Guide des tests
- **DEPLOYMENT.md** : Guide de déploiement

## 🚀 **Démarrage rapide**

### **Pour les développeurs :**
```bash
git clone https://github.com/username/med-predictor.git
cd med-predictor
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### **Pour les testeurs :**
```bash
git clone https://github.com/username/med-predictor.git
cd med-predictor
git checkout testing/qa-testers  # ou testing/user-acceptance
# Suivre les instructions de test dans TESTING_GUIDE.md
```

## 📋 **Checklist de qualité**

### **Avant commit :**
- [ ] Code formaté (PHP CS Fixer, Prettier)
- [ ] Tests passent
- [ ] Documentation mise à jour
- [ ] Pas de secrets dans le code

### **Avant merge :**
- [ ] Code review approuvé
- [ ] Tests CI/CD passent
- [ ] Documentation mise à jour
- [ ] Changelog mis à jour

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

---

**Dernière mise à jour :** 23 Août 2025  
**Version :** 1.0.0  
**Maintenu par :** Équipe Med Predictor
