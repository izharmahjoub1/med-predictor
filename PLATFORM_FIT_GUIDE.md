# 🌐 Guide de Navigation - Plateforme FIT

## 🚀 **Accès à la Plateforme**

### Serveur Local

-   **URL de base** : `http://localhost:8080`
-   **Port** : 8080
-   **Statut** : ✅ Démarré et accessible

## 📱 **Pages Disponibles**

### 1. **Page d'Accueil FIT**

-   **URL** : `http://localhost:8080/`
-   **Description** : Page principale de la plateforme
-   **Contenu** : Informations générales et navigation

### 2. **Interface PCMA - Fallback Vocal**

-   **URL** : `http://localhost:8080/pcma/voice-fallback`
-   **Description** : Interface complète des formulaires PCMA
-   **Fonctionnalités** :
    -   ✅ Formulaire PCMA complet
    -   ✅ Validation des données
    -   ✅ Interface responsive
    -   ✅ Synchronisation vocale

### 3. **API Webhook PCMA**

-   **URL** : `http://localhost:8080/api/google-assistant/webhook`
-   **Description** : Endpoint pour Dialogflow
-   **Méthode** : POST
-   **Format** : JSON

## 🧪 **Test des Fonctionnalités PCMA**

### **Test 1 : Interface Web PCMA**

1. Ouvrir : `http://localhost:8080/pcma/voice-fallback`
2. Remplir le formulaire PCMA :
    - Nom du joueur
    - Âge
    - Position (attaquant, défenseur, milieu, gardien)
3. Valider et soumettre
4. Vérifier la confirmation

### **Test 2 : Validation des Données**

-   Tester avec des données valides
-   Tester avec des données invalides
-   Vérifier les messages d'erreur
-   Confirmer la soumission

### **Test 3 : Responsive Design**

-   Tester sur mobile (réduire la fenêtre)
-   Tester sur desktop
-   Vérifier l'adaptation des formulaires

## 🔍 **Vérification des Fonctionnalités**

### **Interface PCMA**

-   [ ] Formulaire accessible
-   [ ] Champs de saisie fonctionnels
-   [ ] Validation en temps réel
-   [ ] Messages d'erreur clairs
-   [ ] Bouton de soumission actif
-   [ ] Confirmation après soumission

### **Responsive Design**

-   [ ] Adaptation mobile
-   [ ] Adaptation desktop
-   [ ] Navigation intuitive
-   [ ] Boutons accessibles

### **Intégration Backend**

-   [ ] Sauvegarde des données
-   [ ] Validation côté serveur
-   [ ] Messages de confirmation
-   [ ] Gestion des erreurs

## 📊 **Données de Test PCMA**

### **Joueur 1 - Test Complet**

-   **Nom** : Ahmed
-   **Âge** : 24
-   **Position** : Défenseur

### **Joueur 2 - Test Validation**

-   **Nom** : Mohamed
-   **Âge** : 25
-   **Position** : Attaquant

### **Joueur 3 - Test Erreurs**

-   **Nom** : Test
-   **Âge** : -5 (invalide)
-   **Position** : Position invalide

## 🎯 **Scénarios de Test**

### **Scénario 1 : Formulaire Complet**

1. Accéder à `/pcma/voice-fallback`
2. Remplir tous les champs
3. Valider le formulaire
4. Confirmer la soumission
5. Vérifier la confirmation

### **Scénario 2 : Validation des Erreurs**

1. Remplir partiellement le formulaire
2. Tenter la soumission
3. Vérifier les messages d'erreur
4. Corriger les erreurs
5. Soumettre à nouveau

### **Scénario 3 : Test Responsive**

1. Tester sur différentes tailles d'écran
2. Vérifier la navigation mobile
3. Tester les formulaires sur mobile
4. Valider l'expérience utilisateur

## 🔧 **Dépannage**

### **Problème : Page non accessible**

-   Vérifier que le serveur Laravel est démarré
-   Vérifier le port 8080
-   Vérifier les logs Laravel

### **Problème : Formulaire non fonctionnel**

-   Vérifier la console JavaScript
-   Vérifier les logs Laravel
-   Vérifier la base de données

### **Problème : Validation non active**

-   Vérifier les composants Vue.js
-   Vérifier les règles de validation
-   Vérifier les messages d'erreur

## 📱 **Navigation Mobile**

### **Interface Mobile**

-   **Navigation** : Menu hamburger
-   **Formulaires** : Adaptation automatique
-   **Validation** : Messages d'erreur clairs
-   **Soumission** : Boutons accessibles

### **Test Mobile**

1. Réduire la fenêtre du navigateur
2. Tester la navigation
3. Tester les formulaires
4. Vérifier l'ergonomie

## 🎉 **Validation Finale**

### **Checklist de Test**

-   [ ] Page d'accueil accessible
-   [ ] Interface PCMA fonctionnelle
-   [ ] Formulaires validés
-   [ ] Responsive design
-   [ ] Intégration backend
-   [ ] Gestion des erreurs
-   [ ] Confirmation des soumissions

---

## 🚀 **Prêt à Tester !**

Votre plateforme FIT est maintenant accessible sur `http://localhost:8080`

**Commencez par tester l'interface PCMA :**
`http://localhost:8080/pcma/voice-fallback`

**Bonne exploration de la plateforme ! 🎯**

