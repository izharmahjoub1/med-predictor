# 🚀 **Démarrage Rapide - Google Home FIT Med Assistant**

## ⚡ **Configuration en 5 Minutes**

### **Étape 1 : Installer ngrok**

```bash
# Rendre le script exécutable
chmod +x scripts/install-ngrok.sh

# Installer ngrok automatiquement
./scripts/install-ngrok.sh
```

### **Étape 2 : Configurer ngrok**

1. Allez sur [https://ngrok.com/](https://ngrok.com/)
2. Créez un compte gratuit
3. Récupérez votre authtoken
4. Exécutez : `ngrok authtoken YOUR_TOKEN`

### **Étape 3 : Démarrer le serveur**

```bash
# Démarrer Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Dans un autre terminal, configurer ngrok
./scripts/setup-ngrok.sh
```

### **Étape 4 : Configurer Google Actions**

1. Allez sur [Actions on Google Console](https://console.actions.google.com/)
2. Créez un nouveau projet : `fit-med-assistant`
3. Dans Fulfillment, configurez le webhook avec l'URL ngrok

### **Étape 5 : Configurer Google Home**

1. Ouvrez l'app Google Home
2. Paramètres > Assistant > Actions sur Google
3. Activez le mode développeur
4. Liez "FIT Med Assistant"

## 🎯 **Test Immédiat**

Dites à votre Google Home :

```
"OK Google, parle à FIT Med Assistant"
```

Puis :

```
"Commence un PCMA pour Test Player"
```

## 📱 **Commandes Vocales**

-   **Démarrage** : "OK Google, parle à FIT Med Assistant"
-   **PCMA** : "Commence un PCMA pour [Nom]"
-   **Réponse** : "Il est [poste]" ou "Il a [âge] ans"
-   **Fin** : "Termine le PCMA"

## 🔧 **Dépannage Rapide**

-   **ngrok non accessible** : Vérifiez l'authtoken
-   **Google Home ne répond pas** : Vérifiez le mode développeur
-   **Erreur serveur** : Vérifiez que Laravel fonctionne sur le port 8000

## 📚 **Documentation Complète**

-   [Configuration Google Actions](./docs/GOOGLE-ACTIONS-SETUP.md)
-   [Configuration Google Home](./docs/GOOGLE-HOME-SETUP.md)
-   [API FIT V3](./docs/FIT-V3-API-DOCUMENTATION.md)

---

**FIT Med Assistant - Révolutionner la médecine sportive par la voix** 🎤⚽🏥
