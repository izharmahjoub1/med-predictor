# 🎯 **Configuration Google Actions - FIT Med Assistant**

## 📋 **Prérequis**

-   Compte Google Cloud Platform
-   Accès à Actions on Google Console
-   Serveur web accessible depuis Internet (HTTPS requis)
-   Token d'authentification FIT

## 🚀 **Étapes de Configuration**

### **1. Créer le Projet Google Cloud**

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet : `fit-med-assistant`
3. Activez l'API Actions on Google
4. Activez l'API Dialogflow (si utilisée)

### **2. Configurer Actions on Google**

1. Allez sur [Actions on Google Console](https://console.actions.google.com/)
2. Sélectionnez votre projet `fit-med-assistant`
3. Cliquez sur "Create Action"

### **3. Configuration de l'Action**

#### **3.1 Informations de Base**

```json
{
    "actionName": "FIT Med Assistant",
    "description": "Assistant vocal pour formulaires PCMA médicaux",
    "category": "Health & Fitness",
    "language": "French (France)"
}
```

#### **3.2 Invocation**

-   **Nom d'invocation** : `FIT Med Assistant`
-   **Phrases d'invocation** :
    -   "OK Google, parle à FIT Med Assistant"
    -   "Hey Google, ouvre FIT Med Assistant"
    -   "Google, lance FIT Med Assistant"

### **4. Configuration des Intents**

#### **4.1 Intent Principal (MAIN)**

```json
{
    "name": "actions.intent.MAIN",
    "description": "Intent principal pour démarrer l'assistant",
    "trainingPhrases": [
        "OK Google, parle à FIT Med Assistant",
        "Hey Google, ouvre FIT Med Assistant",
        "Google, lance FIT Med Assistant"
    ]
}
```

#### **4.2 Intent PCMA**

```json
{
    "name": "start_pcma",
    "description": "Démarrer un nouveau formulaire PCMA",
    "trainingPhrases": [
        "commence un PCMA pour [player_name]",
        "démarre un PCMA pour [player_name]",
        "nouveau PCMA pour [player_name]"
    ],
    "parameters": [
        {
            "name": "player_name",
            "type": "string",
            "required": true
        }
    ]
}
```

### **5. Configuration du Webhook**

#### **5.1 URL du Webhook**

```
https://votre-domaine.com/api/google-assistant/webhook
```

#### **5.2 Headers d'Authentification**

```json
{
    "Authorization": "Bearer YOUR_FIT_TOKEN",
    "Content-Type": "application/json",
    "X-API-Version": "3"
}
```

#### **5.3 Configuration Fulfillment**

```json
{
    "fulfillment": {
        "webhook": {
            "url": "https://votre-domaine.com/api/google-assistant/webhook",
            "headers": {
                "Authorization": "Bearer YOUR_FIT_TOKEN"
            }
        }
    }
}
```

### **6. Configuration des Types**

#### **6.1 Type Position**

```json
{
    "name": "position",
    "entities": [
        {
            "id": "defenseur",
            "name": "défenseur",
            "synonyms": ["défenseur", "defenseur", "défense", "defense"]
        },
        {
            "id": "milieu",
            "name": "milieu",
            "synonyms": ["milieu", "milieu de terrain", "meneur"]
        },
        {
            "id": "attaquant",
            "name": "attaquant",
            "synonyms": ["attaquant", "avant", "buteur", "pointe"]
        }
    ]
}
```

#### **6.2 Type Statut Médical**

```json
{
    "name": "medical_status",
    "entities": [
        {
            "id": "apte",
            "name": "apte",
            "synonyms": ["apte", "en forme", "bon état"]
        },
        {
            "id": "inapte_temporaire",
            "name": "inapte temporaire",
            "synonyms": ["inapte temporaire", "blessé", "en convalescence"]
        }
    ]
}
```

### **7. Configuration des Scènes**

#### **7.1 Flux de Conversation**

1. **Welcome** → Accueil et initialisation
2. **PcmaStart** → Saisie du nom du joueur
3. **PcmaPosition** → Saisie du poste
4. **PcmaAge** → Saisie de l'âge
5. **PcmaAntecedents** → Saisie des antécédents
6. **PcmaInjury** → Saisie de la dernière blessure
7. **PcmaStatus** → Saisie du statut médical
8. **PcmaReview** → Révision des données
9. **PcmaComplete** → Finalisation

### **8. Test et Validation**

#### **8.1 Test Simulator**

1. Dans Actions on Google Console, cliquez sur "Test"
2. Utilisez le simulateur vocal
3. Testez les phrases d'invocation
4. Vérifiez le flux PCMA complet

#### **8.2 Test sur Appareil**

1. Activez le mode développeur sur votre Google Home
2. Dites "OK Google, parle à FIT Med Assistant"
3. Testez le flux complet

### **9. Déploiement en Production**

#### **9.1 Validation Google**

1. Soumettez votre action pour validation
2. Répondez aux questions de Google
3. Attendez l'approbation (2-4 semaines)

#### **9.2 Publication**

1. Une fois approuvé, publiez votre action
2. Configurez les paramètres de production
3. Activez le monitoring et les logs

## 🔧 **Configuration Serveur**

### **Variables d'Environnement**

```bash
# .env
GOOGLE_ACTIONS_PROJECT_ID=fit-med-assistant
GOOGLE_ACTIONS_VERIFICATION_TOKEN=your_verification_token
FIT_API_TOKEN=your_fit_token
FIT_API_URL=https://votre-domaine.com/api
```

### **Configuration HTTPS**

```nginx
# Nginx configuration
server {
    listen 443 ssl;
    server_name votre-domaine.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location /api/google-assistant/ {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

## 📱 **Utilisation**

### **Démarrage**

```
"OK Google, parle à FIT Med Assistant"
```

### **Commande PCMA**

```
"Commence un PCMA pour Mohamed Salah"
```

### **Réponses Vocales**

```
Assistant: "Quel est son poste ?"
Utilisateur: "Il est attaquant"
Assistant: "Quel est son âge ?"
```

## 🚨 **Dépannage**

### **Erreurs Courantes**

1. **Webhook non accessible**

    - Vérifiez HTTPS
    - Vérifiez la configuration firewall
    - Testez avec curl

2. **Authentification échoue**

    - Vérifiez le token FIT
    - Vérifiez les headers
    - Consultez les logs

3. **Intents non reconnus**
    - Vérifiez les phrases d'entraînement
    - Testez avec le simulateur
    - Vérifiez la configuration des types

### **Logs et Monitoring**

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Google Assistant
tail -f storage/logs/google-assistant.log

# Logs Nginx
tail -f /var/log/nginx/access.log
```

## 🔒 **Sécurité**

### **Authentification**

-   Utilisez des tokens JWT sécurisés
-   Implémentez la rotation des tokens
-   Loggez toutes les tentatives d'accès

### **Validation des Données**

-   Validez tous les paramètres entrants
-   Sanitisez les données utilisateur
-   Implémentez des limites de taux

### **Conformité RGPD**

-   Minimisez la collecte de données
-   Implémentez le droit à l'effacement
-   Loggez le consentement utilisateur

## 📚 **Ressources**

-   [Actions on Google Documentation](https://developers.google.com/assistant/actions)
-   [Google Cloud Console](https://console.cloud.google.com/)
-   [Dialogflow Documentation](https://dialogflow.com/docs)
-   [FIT API Documentation](./FIT-V3-API-DOCUMENTATION.md)

## 🎯 **Prochaines Étapes**

1. **Test complet** avec le simulateur
2. **Validation Google** de l'action
3. **Déploiement production** avec HTTPS
4. **Monitoring** et métriques
5. **Formation utilisateurs** médecins

---

**FIT Med Assistant - Révolutionner la médecine sportive par la voix** 🎤⚽🏥

_Configuration Google Actions v1.0.0_
