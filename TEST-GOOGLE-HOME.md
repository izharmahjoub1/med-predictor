# 🧪 **Test Rapide Google Home - FIT Med Assistant**

## ⚡ **Test en 3 Étapes**

### **Étape 1 : Configuration ngrok**

```bash
# Configurer ngrok avec votre token
./scripts/configure-ngrok.sh

# Suivez les instructions pour récupérer votre token sur https://ngrok.com/
```

### **Étape 2 : Démarrage des services**

```bash
# Démarrer automatiquement Laravel + ngrok
./scripts/start-google-home-test.sh
```

### **Étape 3 : Test Google Home**

1. **App Google Home** → Paramètres → Assistant → Actions sur Google
2. **Activer le mode développeur**
3. **Lier "FIT Med Assistant"**
4. **Tester** : "OK Google, parle à FIT Med Assistant"

## 🎯 **Tests Vocaux**

### **Test 1 : Connexion**

```
Vous: "OK Google, parle à FIT Med Assistant"
Google: "Bienvenue dans FIT Med Assistant..."
```

### **Test 2 : Démarrage PCMA**

```
Vous: "Commence un PCMA pour Test Player"
Google: "Parfait ! Commençons le PCMA pour Test Player..."
```

### **Test 3 : Réponse au champ**

```
Google: "Quel est son poste ?"
Vous: "Il est attaquant"
Google: "Merci ! J'ai enregistré attaquant..."
```

## 🔧 **Dépannage**

### **Problème : "Je ne comprends pas"**

-   Vérifiez que l'action est liée
-   Redémarrez Google Home
-   Vérifiez la configuration des intents

### **Problème : "Service non disponible"**

-   Vérifiez que ngrok fonctionne
-   Vérifiez l'URL du webhook
-   Consultez les logs du serveur

### **Problème : Erreur d'authentification**

-   Vérifiez le token FIT
-   Vérifiez les headers d'authentification
-   Consultez les logs d'erreur

## 📱 **Commandes Vocales**

-   **Démarrage** : "OK Google, parle à FIT Med Assistant"
-   **PCMA** : "Commence un PCMA pour [Nom]"
-   **Réponse** : "Il est [poste]" ou "Il a [âge] ans"
-   **Fin** : "Termine le PCMA"

## 🚀 **Démarrage Rapide**

```bash
# Configuration complète en une commande
./scripts/start-google-home-test.sh

# Arrêt des services
./scripts/stop-google-home-test.sh

# Test de l'API
php scripts/test-google-actions.php
```

## 📊 **Monitoring**

-   **Interface ngrok** : http://localhost:4040
-   **Serveur Laravel** : http://localhost:8000
-   **API Google Assistant** : [URL ngrok]/api/google-assistant/health

## 🎉 **Succès !**

Si vous entendez "Bienvenue dans FIT Med Assistant", la configuration est réussie !

---

**FIT Med Assistant - Révolutionner la médecine sportive par la voix** 🎤⚽🏥
