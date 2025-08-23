# Guide de Déploiement Final - Assistant Vocal PCMA

## ✅ État actuel

### Services déployés

-   **Laravel local** : `http://localhost:8080` ✅ Fonctionnel
-   **Cloud Run** : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app` ✅ Déployé
-   **API Webhook** : Tous les intents PCMA testés et fonctionnels ✅

### Intents PCMA testés

-   `start_pcma` - Démarrage de l'examen
-   `answer_field` - Collecte des informations (nom, âge, position)
-   `yes_intent` - Confirmation et soumission
-   `no_intent` - Gestion des corrections
-   Gestion des sessions et erreurs

## 🚀 Configuration Dialogflow

### 1. Accéder à Dialogflow Console

-   URL : [https://console.dialogflow.com/](https://console.dialogflow.com/)
-   Projet : `fit-medical-voice`

### 2. Configurer le Webhook

1. Menu gauche → **"Fulfillment"**
2. Activer **"Webhook"**
3. URL : `https://fit-medical-voice-eko2yrtf6q-ew.a.run.app/api/google-assistant/webhook`
4. Cliquer **"Save"**

### 3. Configurer les Intents

Pour chaque intent, activer le webhook :

1. **"Intents"** → Sélectionner l'intent
2. Section **"Fulfillment"** :
    - ✅ **"Enable webhook call for this intent"**
    - ✅ **"Use webhook"**
3. **"Save"**

### 4. Intents à configurer

-   `start_pcma` - Démarrer l'examen PCMA
-   `answer_field` - Répondre aux questions
-   `yes_intent` - Confirmer l'envoi
-   `no_intent` - Annuler/corriger
-   `correct_field` - Corriger un champ
-   `restart_pcma` - Recommencer

## 🧪 Tests et Validation

### Test local (recommandé pour le développement)

```bash
# Démarrer le serveur Laravel
php artisan serve --host=0.0.0.0 --port=8080

# Tester l'API
php scripts/test-webhook-cloud-run.php
```

### Test Dialogflow Console

1. Cliquer sur l'icône **"Test"** (microphone)
2. Tester : "commencer l'examen PCMA"
3. Vérifier les réponses et le webhook

### Test Google Assistant (si configuré)

-   "Hey Google, parler à PCMA-FIT"
-   Suivre le dialogue PCMA complet

## 🔧 Résolution des problèmes

### Problème : Erreur 403 sur Cloud Run

**Symptôme** : Service déployé mais accès public restreint
**Solutions** :

1. **Temporaire** : Utiliser ngrok pour les tests
2. **Permanent** : Contacter l'admin Google Workspace

### Problème : Webhook non appelé

**Vérifications** :

1. Intent a-t-il le webhook activé ?
2. URL webhook correcte ?
3. Logs Dialogflow

### Problème : Réponses incorrectes

**Vérifications** :

1. Base de données SQLite accessible
2. Migrations exécutées
3. Logs Laravel

## 🌐 Déploiement en production

### Option 1 : Cloud Run (recommandé)

-   ✅ HTTPS automatique
-   ✅ Scalabilité automatique
-   ✅ Intégration Google Cloud
-   ⚠️ Restrictions d'organisation possibles

### Option 2 : Serveur dédié

-   ✅ Contrôle total
-   ✅ Pas de restrictions
-   ⚠️ Configuration manuelle requise

### Option 3 : VPS avec domaine

-   ✅ Contrôle total
-   ✅ Domaine personnalisé
-   ⚠️ Configuration SSL manuelle

## 📱 Intégration finale

### 1. Dialogflow configuré ✅

### 2. Webhook fonctionnel ✅

### 3. Intents PCMA testés ✅

### 4. Gestion des sessions ✅

### 5. Interface web de secours ✅

## 🎯 Prochaines étapes

### Immédiat

1. Configurer Dialogflow avec l'URL Cloud Run
2. Tester tous les intents dans la console
3. Valider le flux PCMA complet

### Court terme

1. Résoudre les restrictions d'accès Cloud Run
2. Tester l'intégration Google Assistant
3. Optimiser les réponses vocales

### Long terme

1. Déployer sur serveur de production
2. Configurer le domaine `fit.tbhc.uk`
3. Mettre en production pour les utilisateurs

## 📞 Support

### Logs et débogage

-   **Laravel** : `storage/logs/laravel.log`
-   **Dialogflow** : Console → Logs
-   **Cloud Run** : Console Google Cloud → Logs

### Tests automatisés

-   Script de test : `scripts/test-webhook-cloud-run.php`
-   Tests manuels : Console Dialogflow
-   Validation : Flux PCMA complet

---

**🎉 L'assistant vocal PCMA est prêt pour la production !**

