# Configuration Dialogflow - Guide Étape par Étape

## 🎯 Objectif

Configurer Dialogflow pour utiliser l'API webhook PCMA déployée sur Cloud Run.

## 📋 Prérequis

-   ✅ Projet Dialogflow `fit-medical-voice` créé
-   ✅ Service Cloud Run déployé : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app`
-   ✅ Serveur Laravel local fonctionnel sur `http://localhost:8080`

## 🚀 Étape 1 : Accéder à Dialogflow Console

1. Ouvrir le navigateur
2. Aller sur : [https://console.dialogflow.com/](https://console.dialogflow.com/)
3. Se connecter avec le compte `izhar@tbhc.uk`
4. Sélectionner le projet `fit-medical-voice`

**Interface attendue :**

```
┌─────────────────────────────────────────────────────────────┐
│ Dialogflow Console                                          │
├─────────────────────────────────────────────────────────────┤
│ [fit-medical-voice] ▼                                      │
│                                                             │
│ Intents  │ Entities │ Training │ Fulfillment │ Integrations │
│          │          │          │             │              │
└─────────────────────────────────────────────────────────────┘
```

## 🔧 Étape 2 : Configurer le Webhook

1. Dans le menu de gauche, cliquer sur **"Fulfillment"**
2. Dans la section **"Webhook"** :
    - ✅ Activer **"Enable webhook"**
    - 📝 URL : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook`
    - 🔐 Basic Auth : Laisser vide
    - 💾 Cliquer sur **"Save"**

**Configuration attendue :**

```
┌─────────────────────────────────────────────────────────────┐
│ Fulfillment                                                 │
├─────────────────────────────────────────────────────────────┤
│ Webhook                                                     │
│ ☑ Enable webhook                                           │
│                                                             │
│ URL: [https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/  │
│      api/google-assistant/webhook]                         │
│                                                             │
│ Basic Auth: [Username: _____] [Password: _____]            │
│                                                             │
│ [Save]                                                      │
└─────────────────────────────────────────────────────────────┘
```

## 🎭 Étape 3 : Configurer les Intents

### 3.1 Intent `start_pcma`

1. Cliquer sur **"Intents"** dans le menu gauche
2. Cliquer sur **"start_pcma"** (ou créer s'il n'existe pas)
3. Dans la section **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
4. Cliquer sur **"Save"**

**Configuration attendue :**

```
┌─────────────────────────────────────────────────────────────┐
│ Intent: start_pcma                                         │
├─────────────────────────────────────────────────────────────┤
│ Training phrases:                                          │
│ • commencer l'examen PCMA                                  │
│ • démarrer PCMA                                            │
│ • PCMA                                                     │
│                                                             │
│ Fulfillment:                                               │
│ ☑ Enable webhook call for this intent                      │
│ ☑ Use webhook                                              │
│                                                             │
│ [Save]                                                      │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Intent `answer_field`

1. Cliquer sur **"answer_field"** (ou créer)
2. Dans **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
3. Cliquer sur **"Save"**

### 3.3 Intent `yes_intent`

1. Cliquer sur **"yes_intent"** (ou créer)
2. Dans **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
3. Cliquer sur **"Save"**

### 3.4 Intent `no_intent`

1. Cliquer sur **"no_intent"** (ou créer)
2. Dans **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
3. Cliquer sur **"Save"**

## 🧪 Étape 4 : Tester la Configuration

### 4.1 Test dans la Console

1. Cliquer sur l'icône **"Test"** (microphone) à droite
2. Taper : `commencer l'examen PCMA`
3. Appuyer sur **Entrée**
4. Vérifier la réponse et les logs

**Test attendu :**

```
┌─────────────────────────────────────────────────────────────┐
│ Test Console                                               │
├─────────────────────────────────────────────────────────────┤
│ [commencer l'examen PCMA] [Send]                          │
│                                                             │
│ Agent: Bonjour ! Je vais vous aider à remplir le          │
│        formulaire PCMA. Comment s'appelle le joueur ?      │
│                                                             │
│ [Logs: Webhook called successfully]                       │
└─────────────────────────────────────────────────────────────┘
```

### 4.2 Test avec Script Local

```bash
# Dans un autre terminal
php scripts/test-webhook-cloud-run.php
```

## 🔍 Étape 5 : Vérifier les Logs

### 5.1 Logs Dialogflow

1. Dans la console, aller dans **"Training"**
2. Cliquer sur **"Logs"**
3. Vérifier que les webhooks sont appelés

### 5.2 Logs Laravel

```bash
# Vérifier les logs en temps réel
tail -f storage/logs/laravel.log
```

## ❌ Résolution des Problèmes

### Problème : Webhook non appelé

**Vérifications :**

1. Intent a-t-il le webhook activé ?
2. URL webhook correcte ?
3. Service Cloud Run accessible ?

### Problème : Erreur 403 sur Cloud Run

**Solution temporaire :**

```bash
# Utiliser ngrok pour les tests
ngrok http 8080
# Utiliser l'URL ngrok dans Dialogflow
```

### Problème : Réponses incorrectes

**Vérifications :**

1. Base de données accessible
2. Migrations exécutées
3. Logs Laravel

## ✅ Validation Finale

### Checklist de validation :

-   [ ] Webhook configuré dans Dialogflow
-   [ ] Tous les intents PCMA ont le webhook activé
-   [ ] Test `start_pcma` fonctionne
-   [ ] Test `answer_field` fonctionne
-   [ ] Test `yes_intent` fonctionne
-   [ ] Test `no_intent` fonctionne
-   [ ] Logs webhook visibles
-   [ ] Réponses PCMA correctes

## 🎉 Succès !

Une fois tous les tests passés, votre assistant vocal PCMA sera entièrement fonctionnel !

**Prochaine étape :** Tester l'intégration Google Assistant (si configuré)

