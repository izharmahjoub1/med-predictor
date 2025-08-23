# 🎯 **Guide Visuel - Où Trouver la Carte d'Assistant Vocal PCMA**

## 🌐 **Accès à la Fonctionnalité**

### **1. Page de Création PCMA**

-   **URL** : `http://localhost:8080/pcma/create`

### **2. Localisation Exacte de la Carte**

```
┌─────────────────────────────────────────────────────────────────┐
│                    📋 Nouveau PCMA                              │
├─────────────────────────────────────────────────────────────────┤
│                                                               │
│  🎯 **Méthode de saisie**                                    │
│                                                               │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ │
│  │ ✏️ Saisie  │ │ 🎤 Assistant│ │ 📥 FHIR     │ │ 🖼️ Scan     │ │
│  │ manuelle    │ │ Vocal       │ │ Download    │ │ d'image     │ │
│  │             │ │             │ │             │ │             │ │
│  │ ✅ ACTIF   │ │ ⚪ INACTIF   │ │ ⚪ INACTIF   │ │ ⚪ INACTIF   │ │
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ │
│                                                               │
│  📝 **Formulaire manuel affiché par défaut**                  │
│                                                               │
└─────────────────────────────────────────────────────────────────┘
```

## 🎤 **Comment Afficher la Carte d'Assistant Vocal**

### **Étape 1 : Cliquer sur l'Onglet Vocal**

```
┌─────────────────────────────────────────────────────────────────┐
│                    📋 Nouveau PCMA                              │
├─────────────────────────────────────────────────────────────────┤
│                                                               │
│  🎯 **Méthode de saisie**                                    │
│                                                               │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ │
│  │ ✏️ Saisie  │ │ 🎤 Assistant│ │ 📥 FHIR     │ │ 🖼️ Scan     │ │
│  │ manuelle    │ │ Vocal       │ │ Download    │ │ d'image     │ │
│  │             │ │             │ │             │ │             │ │
│  │ ⚪ INACTIF  │ │ ✅ ACTIF    │ │ ⚪ INACTIF   │ │ ⚪ INACTIF   │ │
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ │
│                                                               │
│  🎤 **Assistant Vocal PCMA**                                  │
│                                                               │
└─────────────────────────────────────────────────────────────────┘
```

### **Étape 2 : La Carte d'Assistant Vocal Apparaît**

```
┌─────────────────────────────────────────────────────────────────┐
│                    📋 Nouveau PCMA                              │
├─────────────────────────────────────────────────────────────────┤
│                                                               │
│  🎤 **Assistant Vocal PCMA**                                  │
│  Choisissez votre méthode d'assistance vocale                  │
│                                                               │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ 🟢 **Google Assistant PCMA**                              │ │
│  │ Assistant vocal intelligent pour remplir le formulaire    │ │
│  │                                                           │ │
│  │ [🟢 Commencer l'examen PCMA]                             │ │
│  │                                                           │ │
│  │ 📋 Instructions vocales :                                 │ │
│  │ • "commencer l'examen PCMA" - Démarrer                   │ │
│  │ • "Il s'appelle [nom]" - Nom du joueur                   │ │
│  │ • "Il a [âge] ans" - Âge du joueur                       │ │
│  │ • "Il joue [position]" - Position                        │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ ⚫ **Whisper - Transcription Vocale**                     │ │
│  │ Enregistrement et transcription automatique               │ │
│  │                                                           │ │
│  │ [🔒 Fonctionnalité à venir]                              │ │
│  │ Whisper sera activé dans une prochaine version            │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                               │
└─────────────────────────────────────────────────────────────────┘
```

## 🔍 **Détail des Cartes**

### **🟢 Carte Google Assistant (Active)**

-   **Couleur** : Vert (green-50 à emerald-50)
-   **Bouton** : "Commencer l'examen PCMA" (vert)
-   **Fonctionnalité** : Reconnaissance vocale en temps réel
-   **Instructions** : Commandes vocales en français

### **⚫ Carte Whisper (Désactivée)**

-   **Couleur** : Gris (gray-50 à slate-50)
-   **Bouton** : "🔒 Fonctionnalité à venir" (gris, désactivé)
-   **Fonctionnalité** : Transcription vocale (à venir)
-   **Statut** : Désactivé pour l'instant

## 🎯 **Instructions d'Utilisation**

### **1. Accéder à la Carte**

1. Aller sur `http://localhost:8080/pcma/create`
2. **Cliquer sur l'onglet "🎤 Assistant Vocal"**
3. La carte d'assistant vocal PCMA apparaît

### **2. Utiliser Google Assistant**

1. **Cliquer sur "Commencer l'examen PCMA"**
2. **Autoriser le microphone** si demandé
3. **Utiliser les commandes vocales** :
    - "commencer l'examen PCMA"
    - "Il s'appelle [nom]"
    - "Il a [âge] ans"
    - "Il joue [position]"

### **3. Whisper (Futur)**

-   **Actuellement désactivé**
-   **Sera activé** dans une prochaine version
-   **Permettra** l'enregistrement et la transcription

## 🧪 **Test de la Fonctionnalité**

### **Test Complet**

1. **Onglet vocal** → Cliquer sur "🎤 Assistant Vocal"
2. **Carte Google Assistant** → Cliquer sur "Commencer l'examen PCMA"
3. **Microphone** → Autoriser l'accès
4. **Commandes vocales** → Tester les instructions
5. **Validation** → Vérifier le remplissage du formulaire

## 🔧 **Dépannage**

### **Problème : Carte non visible**

-   ✅ Vérifier que vous êtes sur `/pcma/create`
-   ✅ Cliquer sur l'onglet "🎤 Assistant Vocal"
-   ✅ Vérifier que le JavaScript est chargé

### **Problème : Microphone non détecté**

-   ✅ Autoriser l'accès au microphone
-   ✅ Vérifier les permissions du navigateur
-   ✅ Utiliser Chrome ou Edge

### **Problème : Reconnaissance vocale non fonctionnelle**

-   ✅ Vérifier la compatibilité du navigateur
-   ✅ Parler clairement en français
-   ✅ Utiliser les commandes exactes

---

## 🎉 **Résultat Final**

Votre plateforme FIT dispose maintenant de **deux fonctionnalités vocales** :

-   **🟢 Google Assistant PCMA** : **ACTIF** - Assistant vocal intelligent
-   **⚫ Whisper** : **DÉSACTIVÉ** - Transcription vocale (à venir)

**🎯 La carte d'assistant vocal PCMA est accessible via l'onglet "🎤 Assistant Vocal" !**

