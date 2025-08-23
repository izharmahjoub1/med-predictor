# 🏠 **Configuration Google Home - FIT Med Assistant**

## 📋 **Prérequis**

-   Google Home ou Google Nest
-   Smartphone avec app Google Home
-   Compte Google
-   Serveur web accessible depuis Internet (HTTPS)
-   Configuration Google Actions terminée

## 🚀 **Configuration Étape par Étape**

### **Phase 1 : Configuration du Serveur**

#### **1.1 Option A : ngrok (Développement/Test)**

```bash
# Installer ngrok
brew install ngrok

# Ou télécharger depuis https://ngrok.com/
# Décompresser dans /usr/local/bin/

# Authentifier ngrok
ngrok authtoken YOUR_TOKEN

# Démarrer le tunnel
ngrok http 8000
```

**URL publique** : `https://xxxx-xx-xx-xxx-xx.ngrok.io`

#### **1.2 Option B : Serveur de Production**

```bash
# Configuration Nginx avec SSL
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

**URL publique** : `https://votre-domaine.com`

### **Phase 2 : Configuration Google Actions**

#### **2.1 Créer le Projet**

1. Allez sur [Actions on Google Console](https://console.actions.google.com/)
2. Cliquez sur "Create Action"
3. Sélectionnez "Custom intent"
4. Nom du projet : `fit-med-assistant`

#### **2.2 Configuration des Intents**

```json
{
    "intents": [
        {
            "name": "actions.intent.MAIN",
            "description": "Intent principal",
            "trainingPhrases": [
                "OK Google, parle à FIT Med Assistant",
                "Hey Google, ouvre FIT Med Assistant"
            ]
        },
        {
            "name": "start_pcma",
            "description": "Démarrer PCMA",
            "trainingPhrases": [
                "commence un PCMA pour [player_name]",
                "démarre un PCMA pour [player_name]"
            ],
            "parameters": [
                {
                    "name": "player_name",
                    "type": "string",
                    "required": true
                }
            ]
        }
    ]
}
```

#### **2.3 Configuration du Webhook**

```json
{
    "fulfillment": {
        "webhook": {
            "url": "VOTRE_URL/api/google-assistant/webhook",
            "headers": {
                "Authorization": "Bearer YOUR_FIT_TOKEN"
            }
        }
    }
}
```

**Remplacez `VOTRE_URL` par :**

-   ngrok : `https://xxxx-xx-xx-xxx-xx.ngrok.io`
-   Production : `https://votre-domaine.com`

### **Phase 3 : Configuration Google Home**

#### **3.1 Activer le Mode Développeur**

1. **Ouvrez l'app Google Home** sur votre smartphone
2. **Sélectionnez votre Google Home**
3. **Paramètres** (⚙️) > **Assistant** > **Actions sur Google**
4. **Activez le mode développeur**

#### **3.2 Lier l'Action**

1. Dans **Actions sur Google**, cliquez sur **"+"**
2. Recherchez **"FIT Med Assistant"**
3. Cliquez sur **"Lier"**
4. Confirmez l'autorisation

#### **3.3 Test de Connexion**

Dites à votre Google Home :

```
"OK Google, parle à FIT Med Assistant"
```

**Réponse attendue :**

> "Bienvenue dans FIT Med Assistant. Je vais vous aider à remplir les formulaires PCMA. Dites 'commence un PCMA' pour démarrer."

## 🧪 **Tests et Validation**

### **Test 1 : Démarrage PCMA**

```
Vous: "Commence un PCMA pour Mohamed Salah"
Google: "Parfait ! Commençons le PCMA pour Mohamed Salah. Quel est son poste ? (défenseur, milieu, attaquant)"
```

### **Test 2 : Réponse au Champ**

```
Vous: "Il est attaquant"
Google: "Merci ! J'ai enregistré attaquant. Quel est son âge ?"
```

### **Test 3 : Flux Complet**

1. **Démarrage** : "Commence un PCMA pour [Nom]"
2. **Poste** : "Il est [poste]"
3. **Âge** : "Il a [âge] ans"
4. **Antécédents** : "Aucun" ou description
5. **Blessure** : "Aucune" ou description
6. **Statut** : "Apte" ou autre
7. **Validation** : "Termine le PCMA"

## 🔧 **Dépannage**

### **Problème 1 : "Je ne comprends pas"**

**Solutions :**

-   Vérifiez que l'action est liée
-   Redémarrez Google Home
-   Vérifiez la configuration des intents

### **Problème 2 : "Service non disponible"**

**Solutions :**

-   Vérifiez que ngrok fonctionne
-   Vérifiez l'URL du webhook
-   Consultez les logs du serveur

### **Problème 3 : Erreur d'authentification**

**Solutions :**

-   Vérifiez le token FIT
-   Vérifiez les headers d'authentification
-   Consultez les logs d'erreur

## 📱 **Utilisation Quotidienne**

### **Commandes Vocales Courantes**

```
"OK Google, parle à FIT Med Assistant"
"Commence un PCMA pour [Nom du joueur]"
"Quel est le prochain champ ?"
"Récapitule le PCMA"
"Termine le PCMA"
"Annule le PCMA"
```

### **Exemples d'Utilisation**

#### **Scénario 1 : Nouveau Joueur**

```
Vous: "OK Google, parle à FIT Med Assistant"
Google: "Bienvenue dans FIT Med Assistant..."
Vous: "Commence un PCMA pour Kylian Mbappé"
Google: "Parfait ! Commençons le PCMA pour Kylian Mbappé..."
```

#### **Scénario 2 : Consultation Rapide**

```
Vous: "OK Google, parle à FIT Med Assistant"
Google: "Bienvenue..."
Vous: "Récapitule le PCMA en cours"
Google: "Voici le récapitulatif..."
```

## 🔒 **Sécurité et Confidentialité**

### **Données Vocales**

-   **Traitement temporaire** uniquement
-   **Suppression automatique** après transcription
-   **Chiffrement** en transit et au repos

### **Authentification**

-   **Tokens JWT** sécurisés
-   **Rotation automatique** des clés
-   **Audit trail** complet

### **Conformité RGPD**

-   **Consentement explicite** requis
-   **Droit à l'effacement** implémenté
-   **Portabilité des données** disponible

## 📊 **Monitoring et Logs**

### **Logs Serveur**

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Google Assistant
tail -f storage/logs/google-assistant.log

# Logs ngrok (si utilisé)
tail -f ~/.ngrok2/ngrok.log
```

### **Métriques Clés**

-   **Taux de succès** des sessions vocales
-   **Temps moyen** de completion PCMA
-   **Nombre d'erreurs** par champ
-   **Utilisation par langue**

## 🚀 **Prochaines Étapes**

### **Développement**

1. **Tests complets** avec Google Home
2. **Optimisation** de la reconnaissance vocale
3. **Ajout de langues** (anglais, arabe)
4. **Intégration** avec d'autres formulaires FIT

### **Production**

1. **Serveur dédié** avec HTTPS
2. **Certificat SSL** Let's Encrypt
3. **Monitoring** et alertes
4. **Formation** des utilisateurs médecins

### **Validation Google**

1. **Soumission** pour approbation
2. **Tests** de conformité
3. **Publication** publique
4. **Support** utilisateur

## 📚 **Ressources**

-   [Actions on Google Documentation](https://developers.google.com/assistant/actions)
-   [Google Home Setup](https://support.google.com/googlehome/)
-   [ngrok Documentation](https://ngrok.com/docs)
-   [FIT API Documentation](./FIT-V3-API-DOCUMENTATION.md)

## 🎯 **Checklist de Configuration**

-   [ ] Serveur Laravel démarré
-   [ ] ngrok configuré (ou serveur HTTPS)
-   [ ] Google Actions configuré
-   [ ] Webhook configuré avec la bonne URL
-   [ ] Mode développeur activé sur Google Home
-   [ ] Action liée à Google Home
-   [ ] Test de connexion réussi
-   [ ] Test PCMA complet validé

---

**FIT Med Assistant - Révolutionner la médecine sportive par la voix** 🎤⚽🏥

_Configuration Google Home v1.0.0_
