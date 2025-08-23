# Configuration Dialogflow avec Cloud Run

## URL Webhook Cloud Run

```
https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook
```

## Étapes de configuration

### 1. Accéder à Dialogflow Console

-   Aller sur [Dialogflow Console](https://console.dialogflow.com/)
-   Sélectionner le projet `fit-medical-voice`

### 2. Configurer le Webhook

1. Dans le menu de gauche, cliquer sur **"Fulfillment"**
2. Dans la section **"Webhook"**, activer l'option
3. Entrer l'URL : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook`
4. Cliquer sur **"Save"**

### 3. Configurer les Intents

Pour chaque intent qui doit appeler le webhook :

1. Aller dans **"Intents"**
2. Sélectionner l'intent (ex: `start_pcma`, `answer_field`, `yes_intent`)
3. Dans la section **"Fulfillment"** :
    - Activer **"Enable webhook call for this intent"**
    - Sélectionner **"Use webhook"**
4. Cliquer sur **"Save"**

### 4. Intents à configurer

-   `start_pcma` - Démarrer l'examen PCMA
-   `answer_field` - Répondre aux questions (nom, âge, position)
-   `yes_intent` - Confirmer l'envoi
-   `no_intent` - Annuler l'envoi
-   `correct_field` - Corriger un champ
-   `restart_pcma` - Recommencer l'examen

### 5. Tester la configuration

1. Dans la console Dialogflow, cliquer sur l'icône **"Test"** (microphone)
2. Tester avec : "commencer l'examen PCMA"
3. Vérifier que le webhook est appelé

## Problèmes potentiels

### Erreur 403 sur Cloud Run

-   Le service est déployé mais l'accès public est restreint
-   Solution temporaire : utiliser ngrok pour les tests locaux
-   Solution permanente : résoudre les restrictions d'organisation Google

### Webhook non appelé

-   Vérifier que l'intent a le webhook activé
-   Vérifier l'URL du webhook
-   Vérifier les logs Dialogflow

## Alternative temporaire avec ngrok

Si Cloud Run n'est pas accessible :

```bash
# Démarrer ngrok
ngrok http 8080

# Utiliser l'URL ngrok dans Dialogflow
# Ex: https://abc123.ngrok-free.app/api/google-assistant/webhook
```

## Prochaines étapes

1. Configurer Dialogflow avec l'URL Cloud Run
2. Tester les intents PCMA
3. Vérifier l'intégration webhook
4. Résoudre les problèmes d'accès public si nécessaire

