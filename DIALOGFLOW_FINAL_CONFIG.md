# Configuration Finale Dialogflow - Assistant Vocal PCMA

## 🎯 Objectif Final

Configurer Dialogflow pour utiliser l'API webhook PCMA et tester l'assistant vocal complet.

## ✅ État Actuel

-   **API Webhook** : ✅ Fonctionnelle et testée
-   **Serveur Local** : ✅ Laravel sur port 8080
-   **Cloud Run** : ✅ Déployé et accessible
-   **Tests** : ✅ Tous les intents PCMA validés

## 🚀 Configuration Dialogflow - Étapes Détaillées

### Étape 1 : Accéder à Dialogflow Console

1. Ouvrir [https://console.dialogflow.com/](https://console.dialogflow.com/)
2. Se connecter avec `izhar@tbhc.uk`
3. Sélectionner le projet `fit-medical-voice`

### Étape 2 : Configurer le Webhook Principal

1. Menu gauche → **"Fulfillment"**
2. Section **"Webhook"** :
    - ✅ **"Enable webhook"**
    - 📝 **URL** : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook`
    - 🔐 **Basic Auth** : Laisser vide
    - 💾 **"Save"**

### Étape 3 : Configurer les Intents PCMA

#### 3.1 Intent `start_pcma`

1. **"Intents"** → Cliquer sur `start_pcma`
2. **"Training phrases"** (ajouter si nécessaire) :
    - `commencer l'examen PCMA`
    - `démarrer PCMA`
    - `PCMA`
3. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
4. **"Save"**

#### 3.2 Intent `answer_field`

1. **"Intents"** → Cliquer sur `answer_field`
2. **"Training phrases"** (ajouter si nécessaire) :
    - `Il s'appelle [player_name]`
    - `Il a [age1] ans`
    - `Il joue [position]`
3. **"Parameters"** (vérifier) :
    - `player_name` (required: true)
    - `age1` (required: false)
    - `position` (required: false)
4. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
5. **"Save"**

#### 3.3 Intent `yes_intent`

1. **"Intents"** → Cliquer sur `yes_intent`
2. **"Training phrases"** (ajouter si nécessaire) :
    - `oui`
    - `d'accord`
    - `confirmer`
3. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
4. **"Save"**

#### 3.4 Intent `no_intent`

1. **"Intents"** → Cliquer sur `no_intent`
2. **"Training phrases"** (ajouter si nécessaire) :
    - `non`
    - `annuler`
    - `corriger`
3. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
4. **"Save"**

#### 3.5 Intent `correct_field`

1. **"Intents"** → Cliquer sur `correct_field` (créer si nécessaire)
2. **"Training phrases"** :
    - `corriger le nom`
    - `changer l'âge`
    - `modifier la position`
3. **"Parameters"** :
    - `field_to_correct` (required: true)
4. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
5. **"Save"**

#### 3.6 Intent `restart_pcma`

1. **"Intents"** → Cliquer sur `restart_pcma` (créer si nécessaire)
2. **"Training phrases"** :
    - `recommencer`
    - `tout reprendre`
    - `nouveau formulaire`
3. **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
4. **"Save"**

## 🧪 Tests et Validation

### Test 1 : Test Console Dialogflow

1. Cliquer sur l'icône **"Test"** (microphone) à droite
2. Tester la séquence complète :

    ```
    Utilisateur: commencer l'examen PCMA
    Agent: [Réponse du webhook]

    Utilisateur: Il s'appelle Ahmed
    Agent: [Réponse du webhook]

    Utilisateur: Il a 24 ans
    Agent: [Réponse du webhook]

    Utilisateur: Il joue défenseur
    Agent: [Réponse du webhook]

    Utilisateur: oui
    Agent: [Confirmation et soumission]
    ```

### Test 2 : Vérifier les Logs

1. **"Training"** → **"Logs"**
2. Vérifier que chaque test appelle le webhook
3. Vérifier les réponses reçues

### Test 3 : Test Local (Alternative)

Si Cloud Run n'est pas accessible :

```bash
# Démarrer ngrok
ngrok http 8080

# Utiliser l'URL ngrok dans Dialogflow
# Ex: https://abc123.ngrok-free.app/api/google-assistant/webhook
```

## 🔧 Résolution des Problèmes

### Problème : Webhook non appelé

**Vérifications** :

1. Intent a-t-il le webhook activé ?
2. URL webhook correcte ?
3. Service accessible ?

### Problème : Réponses incorrectes

**Vérifications** :

1. Base de données accessible
2. Migrations exécutées
3. Logs Laravel

### Problème : Erreur 403 sur Cloud Run

**Solutions** :

1. **Temporaire** : Utiliser ngrok
2. **Permanent** : Contacter l'admin Google Workspace

## 📱 Intégration Google Assistant

### Option 1 : Google Actions Console (Recommandé)

1. Aller sur [Actions Console](https://console.actions.google.com/)
2. Créer un nouveau projet
3. Configurer l'intégration Dialogflow
4. Tester sur Google Home

### Option 2 : Test Direct

1. Utiliser la console Dialogflow pour les tests
2. Simuler les interactions vocales
3. Valider le flux PCMA complet

## ✅ Checklist de Validation Finale

-   [ ] Webhook configuré dans Dialogflow
-   [ ] Tous les intents PCMA ont le webhook activé
-   [ ] Test `start_pcma` fonctionne
-   [ ] Test `answer_field` fonctionne (nom, âge, position)
-   [ ] Test `yes_intent` fonctionne
-   [ ] Test `no_intent` fonctionne
-   [ ] Test `correct_field` fonctionne
-   [ ] Test `restart_pcma` fonctionne
-   [ ] Flux PCMA complet testé
-   [ ] Logs webhook visibles
-   [ ] Réponses PCMA correctes

## 🎉 Succès !

Une fois tous les tests passés, votre assistant vocal PCMA sera entièrement fonctionnel !

**URLs de configuration :**

-   **Cloud Run** : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook`
-   **Local** : `http://localhost:8080/api/google-assistant/webhook`
-   **Ngrok** : `https://[ID].ngrok-free.app/api/google-assistant/webhook`

**Prochaine étape :** Tester l'intégration Google Assistant et déployer en production !

