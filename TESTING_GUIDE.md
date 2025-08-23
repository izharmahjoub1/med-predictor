# 🧪 Guide de Test - Med Predictor

## 👥 **Équipe de test**

### **Tester QA (Tester 1) :**
- **Responsable :** Tests techniques et automatisés
- **Email :** `qa@med-predictor.com`
- **Branche :** `testing/qa-testers`

### **Tester User Acceptance (Tester 2) :**
- **Responsable :** Tests utilisateur finaux
- **Email :** `uat@med-predictor.com`
- **Branche :** `testing/user-acceptance`

## 🎯 **Scénarios de test prioritaires**

### **1. Console Vocale - Reconnaissance Google Cloud**

#### **Test 1.1 : Initialisation du service**
- **Prérequis :** Page `/pcma/create` accessible
- **Étapes :**
  1. Cliquer sur "Mode Vocal"
  2. Vérifier que la console vocale apparaît
  3. Cliquer sur "Tester le Service"
  4. Vérifier le statut "Service initialisé avec succès"
- **Résultat attendu :** Service Google Cloud Speech-to-Text initialisé
- **Critère de succès :** ✅ Statut vert "Service initialisé"

#### **Test 1.2 : Reconnaissance vocale basique**
- **Prérequis :** Service initialisé
- **Étapes :**
  1. Cliquer sur "Démarrer Reconnaissance"
  2. Dire clairement : "Le joueur s'appelle Mohamed Salah, il a 33 ans, il joue à l'Espérance Sportive de Tunis, il est gardien de but"
  3. Attendre la transcription
  4. Vérifier les données extraites
- **Résultat attendu :** Données correctement extraites et affichées
- **Critère de succès :** ✅ Nom, âge, position et club correctement reconnus

#### **Test 1.3 : Gestion des erreurs**
- **Prérequis :** Service initialisé
- **Étapes :**
  1. Démarrer la reconnaissance
  2. Parler très bas ou faire du bruit
  3. Vérifier la gestion d'erreur
- **Résultat attendu :** Message d'erreur approprié affiché
- **Critère de succès :** ✅ Erreur gérée gracieusement

### **2. Mode Switching**

#### **Test 2.1 : Changement de mode**
- **Prérequis :** Page `/pcma/create` accessible
- **Étapes :**
  1. Vérifier que le mode Manuel est actif par défaut
  2. Cliquer sur "Mode Vocal"
  3. Vérifier que la console vocale apparaît
  4. Cliquer sur "Mode Manuel"
  5. Vérifier que la console vocale disparaît
- **Résultat attendu :** Basculement fluide entre les modes
- **Critère de succès :** ✅ Console vocale visible uniquement en mode vocal

#### **Test 2.2 : Persistance des données**
- **Prérequis :** Données vocales extraites
- **Étapes :**
  1. Extraire des données vocales
  2. Changer de mode
  3. Revenir au mode vocal
  4. Vérifier que les données sont conservées
- **Résultat attendu :** Données préservées lors du changement de mode
- **Critère de succès :** ✅ Données vocales conservées

### **3. Intégration avec le formulaire PCMA**

#### **Test 3.1 : Transfert automatique des données**
- **Prérequis :** Données vocales extraites
- **Étapes :**
  1. Extraire des données vocales
  2. Cliquer sur "Transférer vers le formulaire PCMA"
  3. Vérifier que les champs sont remplis automatiquement
- **Résultat attendu :** Formulaire PCMA rempli avec les données vocales
- **Critère de succès :** ✅ Champs nom, âge, position et club remplis

#### **Test 3.2 : Validation des données**
- **Prérequis :** Données transférées au formulaire
- **Étapes :**
  1. Vérifier la cohérence des données
  2. Soumettre le formulaire
  3. Vérifier la sauvegarde en base
- **Résultat attendu :** Données sauvegardées correctement
- **Critère de succès :** ✅ PCMA créé avec succès

### **4. Tests de la plateforme FIT complète**

#### **Test 4.1 : Authentification et gestion des utilisateurs**
- **Prérequis :** Accès à la page de connexion
- **Étapes :**
  1. Tester la connexion avec des identifiants valides
  2. Tester la connexion avec des identifiants invalides
  3. Tester la déconnexion
  4. Tester la récupération de mot de passe
- **Résultat attendu :** Gestion correcte de l'authentification
- **Critère de succès :** ✅ Connexion/déconnexion fonctionnelles

#### **Test 4.2 : Dashboard principal**
- **Prérequis :** Utilisateur connecté
- **Étapes :**
  1. Vérifier l'affichage des statistiques
  2. Tester la navigation entre les modules
  3. Vérifier les permissions utilisateur
  4. Tester la recherche globale
- **Résultat attendu :** Dashboard fonctionnel et responsive
- **Critère de succès :** ✅ Toutes les fonctionnalités accessibles

#### **Test 4.3 : Module PCMA (Protocole de Consultation Médicale Athlète)**
- **Prérequis :** Accès au module PCMA
- **Étapes :**
  1. Créer un nouveau PCMA
  2. Modifier un PCMA existant
  3. Consulter l'historique des PCMA
  4. Tester les différents modes (Manuel, Vocal, OCR, FHIR)
  5. Vérifier la validation des données
- **Résultat attendu :** Gestion complète des PCMA
- **Critère de succès :** ✅ CRUD PCMA fonctionnel

#### **Test 4.4 : Module de gestion des joueurs**
- **Prérequis :** Accès au module joueurs
- **Étapes :**
  1. Créer un nouveau joueur
  2. Modifier les informations d'un joueur
  3. Consulter le profil complet d'un joueur
  4. Tester la recherche et le filtrage
  5. Vérifier l'affichage des logos et drapeaux
- **Résultat attendu :** Gestion complète des joueurs
- **Critère de succès :** ✅ CRUD joueurs fonctionnel

#### **Test 4.5 : Module médical**
- **Prérequis :** Accès au module médical
- **Étapes :**
  1. Consulter les dossiers médicaux
  2. Tester la création de rapports
  3. Vérifier l'historique médical
  4. Tester les alertes et notifications
- **Résultat attendu :** Module médical fonctionnel
- **Critère de succès :** ✅ Toutes les fonctionnalités médicales accessibles

#### **Test 4.6 : Module de licences**
- **Prérequis :** Accès au module licences
- **Étapes :**
  1. Consulter les licences actives
  2. Tester la création de nouvelles licences
  3. Vérifier la validation des licences
  4. Tester l'historique des licences
- **Résultat attendu :** Gestion des licences fonctionnelle
- **Critère de succès :** ✅ CRUD licences fonctionnel

#### **Test 4.7 : Module d'administration**
- **Prérequis :** Droits d'administration
- **Étapes :**
  1. Gérer les utilisateurs
  2. Configurer les paramètres système
  3. Consulter les logs système
  4. Gérer les permissions
- **Résultat attendu :** Administration fonctionnelle
- **Critère de succès :** ✅ Toutes les fonctions d'administration accessibles

#### **Test 4.8 : Responsive design et compatibilité**
- **Prérequis :** Accès à la plateforme
- **Étapes :**
  1. Tester sur desktop (Chrome, Firefox, Safari)
  2. Tester sur tablette (iPad, Android)
  3. Tester sur mobile (iPhone, Android)
  4. Vérifier l'adaptation des écrans
- **Résultat attendu :** Interface responsive sur tous les appareils
- **Critère de succès :** ✅ Adaptation correcte sur tous les écrans

#### **Test 4.9 : Performance et temps de réponse**
- **Prérequis :** Accès à la plateforme
- **Étapes :**
  1. Mesurer le temps de chargement des pages
  2. Tester la réactivité des formulaires
  3. Vérifier la performance des recherches
  4. Tester avec de gros volumes de données
- **Résultat attendu :** Performance acceptable
- **Critère de succès :** ✅ Temps de réponse < 3 secondes

#### **Test 4.10 : Sécurité et permissions**
- **Prérequis :** Différents types d'utilisateurs
- **Étapes :**
  1. Tester l'accès aux modules selon les permissions
  2. Vérifier la protection des routes sensibles
  3. Tester la validation des entrées utilisateur
  4. Vérifier la protection CSRF
- **Résultat attendu :** Sécurité respectée
- **Critère de succès :** ✅ Accès contrôlé selon les permissions

## 🔧 **Configuration de l'environnement de test**

### **Environnement local :**
```bash
# Cloner le projet
git clone https://github.com/username/med-predictor.git
cd med-predictor

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
# Remplir GOOGLE_SPEECH_API_KEY dans .env

# Base de données
php artisan migrate
php artisan db:seed

# Démarrer le serveur
php artisan serve
```

### **Variables d'environnement requises :**
```env
GOOGLE_SPEECH_API_KEY=your_google_cloud_api_key
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=med_predictor_test
DB_USERNAME=root
DB_PASSWORD=
```

## 📊 **Matrice de test complète**

| Module/Fonctionnalité | Tester QA | Tester UAT | Statut | Priorité |
|----------------------|-----------|------------|---------|----------|
| **Console Vocale** | | | | |
| Initialisation service | ✅ | ⏳ | En cours | Haute |
| Reconnaissance vocale | ✅ | ⏳ | En cours | Haute |
| Mode switching | ✅ | ⏳ | En cours | Haute |
| Transfert données | ⏳ | ⏳ | En attente | Haute |
| Gestion erreurs | ⏳ | ⏳ | En attente | Haute |
| **Authentification** | | | | |
| Connexion/Déconnexion | ⏳ | ⏳ | En attente | Critique |
| Gestion des utilisateurs | ⏳ | ⏳ | En attente | Haute |
| Récupération mot de passe | ⏳ | ⏳ | En attente | Moyenne |
| **Dashboard** | | | | | |
| Affichage statistiques | ⏳ | ⏳ | En attente | Haute |
| Navigation modules | ⏳ | ⏳ | En attente | Haute |
| Recherche globale | ⏳ | ⏳ | En attente | Moyenne |
| **Module PCMA** | | | | |
| CRUD PCMA | ⏳ | ⏳ | En attente | Haute |
| Modes (Manuel, Vocal, OCR, FHIR) | ⏳ | ⏳ | En attente | Haute |
| Validation données | ⏳ | ⏳ | En attente | Haute |
| **Module Joueurs** | | | | |
| CRUD joueurs | ⏳ | ⏳ | En attente | Haute |
| Profils complets | ⏳ | ⏳ | En attente | Haute |
| Logos et drapeaux | ⏳ | ⏳ | En attente | Moyenne |
| **Module Médical** | | | | |
| Dossiers médicaux | ⏳ | ⏳ | En attente | Haute |
| Rapports médicaux | ⏳ | ⏳ | En attente | Haute |
| Historique médical | ⏳ | ⏳ | En attente | Moyenne |
| **Module Licences** | | | | |
| Gestion licences | ⏳ | ⏳ | En attente | Haute |
| Validation licences | ⏳ | ⏳ | En attente | Haute |
| **Administration** | | | | |
| Gestion utilisateurs | ⏳ | ⏳ | En attente | Moyenne |
| Configuration système | ⏳ | ⏳ | En attente | Moyenne |
| **Interface** | | | | |
| Responsive design | ⏳ | ⏳ | En attente | Haute |
| Compatibilité navigateurs | ⏳ | ⏳ | En attente | Haute |
| **Performance** | | | | |
| Temps de réponse | ⏳ | ⏳ | En attente | Moyenne |
| Charge volumétrique | ⏳ | ⏳ | En attente | Moyenne |
| **Sécurité** | | | | |
| Permissions utilisateurs | ⏳ | ⏳ | En attente | Critique |
| Protection CSRF | ⏳ | ⏳ | En attente | Critique |
| Validation entrées | ⏳ | ⏳ | En attente | Haute |

**Légende :**
- ✅ : Testé et validé
- ⏳ : En cours de test
- ❌ : Testé avec erreurs
- 🔄 : Test en cours de correction

## 🐛 **Gestion des bugs**

### **Template de rapport de bug :**
```markdown
## 🐛 Rapport de bug

**Titre :** [Description concise du problème]

**Sévérité :** [Critical/High/Medium/Low]

**Environnement :**
- OS : [macOS/Windows/Linux]
- Navigateur : [Chrome/Firefox/Safari]
- Version : [X.X.X]

**Étapes de reproduction :**
1. [Étape 1]
2. [Étape 2]
3. [Étape 3]

**Résultat actuel :** [Ce qui se passe]

**Résultat attendu :** [Ce qui devrait se passer]

**Logs/Erreurs :** [Copier-coller des erreurs]

**Captures d'écran :** [Si applicable]

**Informations supplémentaires :** [Contexte, etc.]
```

### **Labels GitHub :**
- `bug` : Problème à corriger
- `critical` : Bloqueur critique
- `high` : Priorité haute
- `medium` : Priorité moyenne
- `low` : Priorité basse

## 📈 **Métriques de qualité**

### **Objectifs :**
- **Couverture de test :** > 80%
- **Temps de résolution des bugs critiques :** < 24h
- **Temps de résolution des bugs haute priorité :** < 72h
- **Taux de régression :** < 5%

### **KPI à suivre :**
- Nombre de bugs détectés par jour
- Temps moyen de résolution
- Taux de régression
- Satisfaction utilisateur

## 🚀 **Tests automatisés**

### **Tests PHPUnit :**
```bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=ConsoleVocaleTest
php artisan test --filter=SpeechRecognitionTest
```

### **Tests de navigateur :**
```bash
# Tests Laravel Dusk
php artisan dusk

# Tests spécifiques
php artisan dusk --filter=testConsoleVocale
```

## 📝 **Documentation des tests**

### **Rapport quotidien :**
- Tests exécutés
- Bugs détectés
- Problèmes rencontrés
- Suggestions d'amélioration

### **Rapport hebdomadaire :**
- Résumé des tests
- Métriques de qualité
- Planification des tests
- Ressources nécessaires

## 📅 **Planification des tests par module**

### **Semaine 1 : Tests critiques et authentification**
- **Jour 1-2 :** Authentification et gestion des utilisateurs
- **Jour 3-4 :** Dashboard principal et navigation
- **Jour 5 :** Tests de sécurité et permissions

### **Semaine 2 : Module PCMA et console vocale**
- **Jour 1-2 :** Console vocale Google Cloud
- **Jour 3-4 :** CRUD PCMA et modes de saisie
- **Jour 5 :** Validation et intégration des données

### **Semaine 3 : Modules métier**
- **Jour 1-2 :** Module de gestion des joueurs
- **Jour 3-4 :** Module médical et dossiers
- **Jour 5 :** Module de licences

### **Semaine 4 : Interface et performance**
- **Jour 1-2 :** Tests responsive et compatibilité
- **Jour 3-4 :** Tests de performance et charge
- **Jour 5 :** Tests d'administration et finalisation

## 🎯 **Objectifs de test par module**

### **Module Authentification (Priorité : Critique)**
- **Objectif :** 100% de couverture des fonctionnalités
- **Critères :** Toutes les routes protégées, gestion des sessions
- **Tests :** Connexion, déconnexion, permissions, récupération mot de passe

### **Module PCMA (Priorité : Haute)**
- **Objectif :** 95% de couverture des fonctionnalités
- **Critères :** CRUD complet, modes de saisie, validation
- **Tests :** Console vocale, modes manuel/OCR/FHIR, transfert de données

### **Module Joueurs (Priorité : Haute)**
- **Objectif :** 90% de couverture des fonctionnalités
- **Critères :** Gestion des profils, recherche, filtrage
- **Tests :** CRUD joueurs, affichage logos/drapeaux, recherche avancée

### **Module Médical (Priorité : Haute)**
- **Objectif :** 90% de couverture des fonctionnalités
- **Critères :** Dossiers médicaux, rapports, historique
- **Tests :** Consultation dossiers, création rapports, alertes

### **Module Licences (Priorité : Haute)**
- **Objectif :** 85% de couverture des fonctionnalités
- **Critères :** Gestion des licences, validation
- **Tests :** CRUD licences, validation, historique

### **Interface et Performance (Priorité : Moyenne)**
- **Objectif :** 80% de couverture des fonctionnalités
- **Critères :** Responsive design, compatibilité navigateurs
- **Tests :** Adaptation écrans, temps de réponse, charge volumétrique

---

**Dernière mise à jour :** 23 Août 2025  
**Version :** 1.0.0  
**Maintenu par :** Équipe de test Med Predictor
