# 🎤 Google Assistant FIT - Module PCMA Vocal

## 📋 Vue d'ensemble

**FIT Med Assistant** est l'intégration Google Assistant de la plateforme FIT, permettant aux médecins de compléter des formulaires PCMA (Premier Contact Médico-Administratif) via la voix.

### 🌟 Fonctionnalités du MVP

-   **🎯 5 champs PCMA essentiels** : Poste, Âge, Antécédents, Dernière blessure, Statut
-   **🗣️ Interface vocale guidée** : Questions séquentielles avec validation
-   **🇫🇷 Langue française** : Reconnaissance et traitement en français
-   **🔒 Authentification FIT** : Intégration avec le système d'authentification existant
-   **🔄 Système de fallback intelligent** : Google Assistant → Whisper → Interface Web

## 🚀 Démarrage Rapide

### 1. **Prérequis**

-   Laravel 11 installé et configuré
-   Base de données PostgreSQL/MySQL
-   Serveur web accessible depuis Internet (pour Google Actions)

### 2. **Installation**

```bash
# Exécuter la migration
php artisan migrate

# Publier la configuration
php artisan vendor:publish --tag=google-assistant-config

# Vérifier que les routes sont chargées
php artisan route:list --name=google.assistant
```

### 3. **Configuration**

```bash
# Variables d'environnement à ajouter dans .env
GOOGLE_ASSISTANT_ENABLED=true
GOOGLE_ASSISTANT_WEBHOOK_SECRET=your_secret_here
GOOGLE_ASSISTANT_DEFAULT_LANGUAGE=fr
```

## 🏗️ Architecture Technique

### **Structure des Fichiers**

```
app/
├── Http/
│   ├── Controllers/
│   │   └── GoogleAssistantController.php    # Contrôleur principal
│   └── Middleware/
│       └── GoogleAssistantAuthMiddleware.php # Authentification GA
├── Services/
│   ├── VoiceProcessingService.php           # Traitement vocal
│   └── PcmaMappingService.php              # Mapping PCMA
├── Models/
│   └── VoiceSession.php                     # Sessions vocales
└── Events/
    └── PcmaVoiceCompleted.php              # Événement completion
```

### **Flux de Données avec Fallback**

```
Google Home → Google Actions → Webhook FIT → Traitement → Base de données → API FIT
                    ↓ (en cas d'échec)
                Whisper (local/OpenAI) → Traitement → Base de données → API FIT
                    ↓ (en cas d'échec)
                Interface Web FIT → Formulaire PCMA
```

## 🎯 Workflow PCMA Vocal

### **1. Démarrage de Session**

```
Médecin: "OK Google, commence un PCMA pour Mohamed Salah"
Assistant: "Parfait ! Commençons le PCMA pour Mohamed Salah. Quel est son poste ?"
```

### **2. Questions Séquentielles**

```
Assistant: "Quel est son poste ? (défenseur, milieu, attaquant)"
Médecin: "défenseur"
Assistant: "Quel est l'âge du joueur ?"
Médecin: "25 ans"
Assistant: "A-t-il des antécédents médicaux ? (oui/non)"
Médecin: "non"
```

### **3. Validation et Confirmation**

```
Assistant: "Récapitulatif du PCMA pour Mohamed Salah :
- Poste : défenseur
- Âge : 25 ans
- Antécédents : non
- Dernière blessure : hier
- Statut : apte

Voulez-vous valider ces informations ?"
```

## 🔧 Configuration Google Actions

### **1. Créer l'Action**

-   Aller sur [Actions on Google Console](https://console.actions.google.com/)
-   Créer un nouveau projet "FIT Med Assistant"

### **2. Configurer le Webhook**

```json
{
    "fulfillment": {
        "webhook": {
            "url": "https://votre-domaine.com/google-assistant/webhook",
            "headers": {
                "Authorization": "Bearer YOUR_FIT_TOKEN"
            }
        }
    }
}
```

### **3. Définir les Intents**

-   `start_pcma` : Démarrage d'une session PCMA
-   `answer_field` : Réponse à un champ spécifique
-   `review_pcma` : Révision des données
-   `complete_pcma` : Finalisation

## 🧪 Tests et Développement

### **Script de Test Local**

```bash
# Tester les endpoints Google Assistant
php scripts/test-google-assistant.php

# Vérifier les logs
tail -f storage/logs/laravel.log
```

### **Test avec cURL**

```bash
# Test de santé
curl -X GET "http://localhost:8000/google-assistant/health"

# Test de démarrage PCMA
curl -X POST "http://localhost:8000/google-assistant/webhook" \
  -H "Content-Type: application/json" \
  -H "X-User-ID: 1" \
  -d '{
    "queryResult": {
      "intent": {"displayName": "start_pcma"},
      "parameters": {"player_name": "Test Player"},
      "queryText": "Commence un PCMA pour Test Player"
    },
    "session": "test-session-123"
  }'
```

## 📊 Monitoring et Logs

### **Logs Importants**

-   `storage/logs/laravel.log` : Logs généraux
-   `storage/logs/google-assistant.log` : Logs spécifiques GA
-   Table `voice_sessions` : Sessions vocales

### **Métriques Clés**

-   Taux de succès des sessions vocales
-   Temps moyen de completion
-   Nombre d'erreurs par champ
-   Utilisation par langue

## 🚨 Gestion d'Erreurs et Système de Fallback

### **Types d'Erreurs**

1. **Reconnaissance vocale** : "Je n'ai pas compris, pouvez-vous répéter ?"
2. **Validation de champ** : "Âge invalide, veuillez donner un âge entre 16 et 50 ans"
3. **Authentification** : Redirection vers l'interface web
4. **Technique** : Fallback automatique vers Whisper

### **Système de Fallback Intelligent**

#### **Niveau 1 : Google Assistant (Principal)**

-   Interface vocale native Google Home
-   Intégration directe avec Google Actions
-   Traitement en temps réel

#### **Niveau 2 : Whisper (Fallback 1)**

-   Reconnaissance vocale locale ou OpenAI
-   Traitement des fichiers audio
-   Continuité du service en cas d'échec GA

#### **Niveau 3 : Interface Web (Fallback 2)**

-   Formulaire PCMA complet
-   Saisie manuelle ou vocale via navigateur
-   Garantie de continuité du service

### **Scénarios de Fallback**

#### **Échec Google Assistant → Whisper**

```
"Google Assistant n'est pas disponible.
J'ai utilisé Whisper pour traiter votre demande.
Commençons le PCMA pour [Nom du joueur].
Quel est son poste ?"
```

#### **Échec Whisper → Interface Web**

```
"Voulez-vous continuer via l'interface web ?
Vous pouvez accéder au formulaire PCMA à l'adresse : [URL]"
```

## 🔒 Sécurité et RGPD

### **Protection des Données**

-   **Données vocales** : Traitement temporaire uniquement
-   **Suppression automatique** : Après transcription et validation
-   **Audit trail** : Toutes les interactions sont loggées
-   **Authentification** : JWT/OAuth FIT existant

### **Conformité RGPD**

-   Consentement explicite requis
-   Droit à l'effacement des données
-   Portabilité des données
-   Notification des violations

## 🚀 Prochaines Étapes

### **Phase 2 : Fonctionnalités Avancées**

-   [ ] Support multilingue (Anglais, Arabe tunisien)
-   [ ] Plus de champs PCMA
-   [ ] Reconnaissance d'accents régionaux
-   [ ] Intégration avec d'autres formulaires FIT

### **Phase 3 : Intelligence Artificielle**

-   [ ] Prédiction automatique de champs
-   [ ] Détection d'anomalies médicales
-   [ ] Suggestions intelligentes
-   [ ] Apprentissage des préférences utilisateur

## 📞 Support et Maintenance

### **Dépannage Courant**

1. **Webhook non accessible** : Vérifier la configuration réseau
2. **Authentification échoue** : Vérifier les tokens FIT
3. **Reconnaissance vocale faible** : Ajuster les seuils de confiance

### **Contact**

-   **Documentation technique** : Ce fichier README
-   **Logs d'erreur** : `storage/logs/laravel.log`
-   **Support FIT** : Équipe de développement FIT

## 🔄 **Système de Fallback Whisper**

### **Configuration Whisper**

```bash
# Variables d'environnement à ajouter dans .env
WHISPER_ENABLED=true
WHISPER_USE_OPENAI=false  # true pour OpenAI, false pour Whisper local
WHISPER_OPENAI_API_KEY=your_key_here  # Si OpenAI
WHISPER_LANGUAGE=fr
WHISPER_FALLBACK_ENABLED=true
```

### **Test du Système de Fallback**

```bash
# Tester le fallback Whisper
php scripts/test-whisper-fallback.php

# Tester Google Assistant complet
php scripts/test-google-assistant.php
```

### **Installation Whisper Local (Optionnel)**

```bash
# Installation via pip
pip install openai-whisper

# Vérification de l'installation
whisper --help
```

## 🎉 Conclusion

Le module **FIT Med Assistant** révolutionne la saisie des formulaires PCMA en offrant une interface vocale intuitive et sécurisée. Cette première version MVP valide le concept et ouvre la voie à des fonctionnalités plus avancées.

---

**FIT Med Assistant - Révolutionner la médecine sportive par la voix** 🎤⚽🏥

_Version MVP 1.0.0 - Langue française uniquement_
