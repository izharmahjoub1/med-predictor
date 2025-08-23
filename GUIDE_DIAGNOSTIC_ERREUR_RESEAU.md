# 🔍 Guide de Diagnostic - Erreur Réseau Assistant Vocal PCMA

## 🎯 **PROBLÈME IDENTIFIÉ**

L'erreur `network` se produit **immédiatement** après le démarrage de la reconnaissance vocale, **avant même** que l'utilisateur puisse parler.

### ❌ **SYMPTÔMES**

-   Bouton "Commencer l'examen PCMA" → Erreur réseau instantanée
-   Reconnaissance vocale s'arrête immédiatement
-   Message "❌ Erreur réseau - Cliquez pour réessayer"

### 🔍 **DIAGNOSTIC CONFIRMÉ**

```
🧪 Test simple de reconnaissance vocale...
✅ Test: Reconnaissance démarrée
❌ Test: Erreur détectée: network
🔍 Détails de l'erreur: SpeechRecognitionErrorEvent
```

## 🛠️ **SOLUTIONS PAR ORDRE DE PRIORITÉ**

### **1. 🎤 Vérification Permissions Microphone (PRIORITÉ 1)**

#### **Étape 1 : Vérifier l'icône microphone**

1. **Regardez l'URL** dans la barre d'adresse
2. **Cherchez une icône microphone** (🎤 ou 🚫)
3. **Si icône barrée** → Cliquez dessus
4. **Sélectionnez "Autoriser"** ou "Allow"

#### **Étape 2 : Test avec bouton 🔬 Test Ultra-Simple**

1. **Cliquez sur le bouton VIOLET** "🔬 Test Ultra-Simple"
2. **Regardez la console** (F12) pour voir :
    ```
    🔬 Test ultra-simple...
    🔍 APIs disponibles: {webkitSpeechRecognition: true, ...}
    🎤 Permission microphone: granted/denied/prompt
    ```

#### **Étape 3 : Autorisation manuelle**

1. **Chrome** : chrome://settings/content/microphone
2. **Firefox** : about:preferences#privacy → Permissions
3. **Safari** : Préférences → Sites web → Microphone

### **2. 🌐 Test Navigateur Différent (PRIORITÉ 2)**

#### **Étape 1 : Test Chrome**

1. **Ouvrez Chrome** (meilleur support Web Speech API)
2. **Allez sur** `http://localhost:8080/test-pcma-simple`
3. **Testez le bouton** "🔬 Test Ultra-Simple"

#### **Étape 2 : Mode Navigation Privée**

1. **Ouvrez une fenêtre privée** (Ctrl+Shift+N)
2. **Désactivez les extensions** temporairement
3. **Testez** la reconnaissance vocale

#### **Étape 3 : Désactivation Extensions**

1. **Chrome** : chrome://extensions/
2. **Désactivez TOUTES** les extensions
3. **Rechargez la page** et testez

### **3. 🔒 Vérification Sécurité (PRIORITÉ 3)**

#### **Étape 1 : Vérifier le protocole**

1. **Assurez-vous** d'être sur `localhost:8080`
2. **Web Speech API** fonctionne sur localhost
3. **Évitez** les adresses IP externes

#### **Étape 2 : Paramètres de sécurité**

1. **Chrome** : chrome://settings/security
2. **Vérifiez** que le site n'est pas bloqué
3. **Autorisez** l'accès au microphone

#### **Étape 3 : Firewall/Antivirus**

1. **Vérifiez** que localhost:8080 n'est pas bloqué
2. **Désactivez temporairement** l'antivirus
3. **Testez** la reconnaissance vocale

### **4. 🌐 Vérification Connexion (PRIORITÉ 4)**

#### **Étape 1 : Test de base**

1. **Vérifiez** votre connexion internet
2. **Testez** sur un autre réseau si possible
3. **Vérifiez** que localhost:8080 répond

#### **Étape 2 : Test API simple**

1. **Ouvrez** `http://localhost:8080/test-pcma-simple`
2. **Vérifiez** que la page se charge
3. **Testez** le bouton "🔬 Test Ultra-Simple"

## 🔬 **BOUTONS DE TEST DISPONIBLES**

### **🧪 Test Simple (BLEU)**

-   Configuration minimale de reconnaissance vocale
-   Teste directement l'API Web Speech
-   Révèle les erreurs de configuration

### **🔬 Test Ultra-Simple (VIOLET)**

-   Vérifie les APIs disponibles
-   Teste les permissions microphone
-   Diagnostic complet avant reconnaissance

### **🟢 Commencer l'examen PCMA (VERT)**

-   Utilise le test ultra-simple en premier
-   Puis lance la reconnaissance complète
-   Gestion d'erreur robuste

## 📊 **INTERPRÉTATION DES RÉSULTATS**

### **✅ Si le test ultra-simple réussit :**

```
🔬 Test ultra-simple...
🔍 APIs disponibles: {webkitSpeechRecognition: true, ...}
🎤 Permission microphone: granted
✅ Permission microphone accordée. Test de reconnaissance...
```

**→ Le problème est dans la configuration complexe**

### **❌ Si permission 'denied' :**

```
🎤 Permission microphone: denied
🗣️ Réponse vocale: Accès microphone refusé...
```

**→ Autorisez l'accès au microphone**

### **❌ Si permission 'prompt' :**

```
🎤 Permission microphone: prompt
🗣️ Réponse vocale: Demande d'autorisation...
```

**→ Cliquez sur 'Autoriser' quand demandé**

### **❌ Si erreur 'network' persiste :**

```
❌ Test: Erreur détectée: network
```

**→ Problème de configuration système**

## 🚀 **PROCHAINES ÉTAPES**

1. **Testez le bouton VIOLET** "🔬 Test Ultra-Simple"
2. **Notez exactement** ce qui s'affiche dans la console
3. **Suivez les solutions** par ordre de priorité
4. **Rapportez les résultats** pour diagnostic final

## 💡 **POINTS CLÉS**

-   **L'erreur réseau est immédiate** → Problème de configuration
-   **Le code fonctionne** → Problème de permissions/système
-   **Test ultra-simple** → Diagnostic complet avant reconnaissance
-   **Solutions par priorité** → Résolution progressive du problème

---

**🌐 URL de test :** `http://localhost:8080/test-pcma-simple`  
**🔬 Bouton de diagnostic :** VIOLET "🔬 Test Ultra-Simple"  
**🔍 Console :** F12 pour voir les messages détaillés

