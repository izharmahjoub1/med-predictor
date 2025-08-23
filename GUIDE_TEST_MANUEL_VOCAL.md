# 🧪 **Guide de Test Manuel - Assistant Vocal PCMA**

## 🎯 **Objectif**

Vérifier manuellement que la carte d'assistant vocal PCMA fonctionne correctement.

## 🌐 **Accès à la Page**

1. **Ouvrir le navigateur** (Chrome recommandé)
2. **Aller sur** : `http://localhost:8080/pcma/create`
3. **Vérifier** que la page se charge correctement

## 🔍 **Étape 1 : Vérification de la Structure**

### **Vérifier les Onglets**

En haut de la page, vous devriez voir **4 onglets** :

-   ✅ **Saisie manuelle** (actif par défaut)
-   🎤 **Assistant Vocal** ← **CLIQUEZ ICI !**
-   📥 **Téléchargement FHIR**
-   🖼️ **Scan d'image**

### **Vérifier l'Onglet Vocal**

1. **Cliquer sur l'onglet "🎤 Assistant Vocal"**
2. **Vérifier** que la section vocale s'affiche
3. **Vérifier** que le formulaire manuel se cache

## 🎤 **Étape 2 : Vérification de la Carte Vocale**

### **Carte Google Assistant (Verte)**

Après avoir cliqué sur l'onglet vocal, vous devriez voir :

```
┌─────────────────────────────────────────────────────────────┐
│ 🟢 Google Assistant PCMA                                  │
│ Assistant vocal intelligent pour remplir le formulaire    │
│                                                           │
│ [🟢 Commencer l'examen PCMA]                             │
│                                                           │
│ 📋 Instructions vocales :                                 │
│ • "commencer l'examen PCMA" - Démarrer                   │
│ • "Il s'appelle [nom]" - Nom du joueur                   │
│ • "Il a [âge] ans" - Âge du joueur                       │
│ • "Il joue [position]" - Position                        │
└─────────────────────────────────────────────────────────────┘
```

### **Carte Whisper (Grise)**

En dessous, vous devriez voir :

```
┌─────────────────────────────────────────────────────────────┐
│ ⚫ Whisper - Transcription Vocale                         │
│ Enregistrement et transcription automatique               │
│                                                           │
│ [🔒 Fonctionnalité à venir]                              │
│ Whisper sera activé dans une prochaine version            │
└─────────────────────────────────────────────────────────────┘
```

## 🧪 **Étape 3 : Test de la Fonctionnalité Vocale**

### **Test du Bouton de Démarrage**

1. **Cliquer sur "Commencer l'examen PCMA"**
2. **Autoriser l'accès au microphone** si demandé
3. **Vérifier** que le bouton devient "Arrêter" (rouge)
4. **Vérifier** que le statut "Écoute en cours..." apparaît

### **Test des Commandes Vocales**

1. **Dire clairement** : "commencer l'examen PCMA"
2. **Vérifier** que l'assistant répond vocalement
3. **Dire** : "Il s'appelle Ahmed"
4. **Vérifier** que le nom est enregistré
5. **Continuer** avec l'âge et la position

## 🔧 **Dépannage - Si la Carte n'Apparaît Pas**

### **Problème 1 : Onglet vocal non visible**

-   ✅ Vérifier que vous êtes sur la bonne page
-   ✅ Vérifier que le JavaScript est chargé
-   ✅ Vérifier la console du navigateur pour les erreurs

### **Problème 2 : Section vocale cachée**

-   ✅ Cliquer sur l'onglet "🎤 Assistant Vocal"
-   ✅ Vérifier que la classe "hidden" est supprimée
-   ✅ Vérifier que le JavaScript des onglets fonctionne

### **Problème 3 : Bouton non fonctionnel**

-   ✅ Vérifier que le bouton a l'ID "start-voice-btn"
-   ✅ Vérifier que le JavaScript vocal est chargé
-   ✅ Vérifier la console pour les erreurs JavaScript

## 🎯 **Vérifications Techniques**

### **Console du Navigateur**

1. **Ouvrir les outils de développement** (F12)
2. **Aller dans l'onglet Console**
3. **Vérifier** qu'il n'y a pas d'erreurs JavaScript
4. **Vérifier** que les messages de log vocal apparaissent

### **Éléments HTML**

1. **Inspecter l'élément** de l'onglet vocal
2. **Vérifier** qu'il a l'ID "voice-tab"
3. **Vérifier** qu'il a la classe "input-method-tab"
4. **Vérifier** qu'il n'a pas la classe "active"

### **Section Vocale**

1. **Inspecter l'élément** de la section vocale
2. **Vérifier** qu'il a l'ID "voice-section"
3. **Vérifier** qu'il a la classe "input-section hidden"
4. **Vérifier** que la classe "hidden" est supprimée au clic

## 📋 **Checklist de Test**

-   [ ] Page `/pcma/create` accessible
-   [ ] 4 onglets visibles en haut
-   [ ] Onglet "🎤 Assistant Vocal" cliquable
-   [ ] Section vocale s'affiche au clic
-   [ ] Carte Google Assistant (verte) visible
-   [ ] Carte Whisper (grise) visible
-   [ ] Bouton "Commencer l'examen PCMA" cliquable
-   [ ] Microphone autorisé
-   [ ] Reconnaissance vocale fonctionne
-   [ ] Commandes vocales reconnues

## 🚨 **Si Rien ne Fonctionne**

### **Solution 1 : Vider le Cache**

1. **Ctrl+F5** (Windows) ou **Cmd+Shift+R** (Mac)
2. **Vider le cache du navigateur**
3. **Recharger la page**

### **Solution 2 : Vérifier le Serveur**

1. **Vérifier** que le serveur Laravel fonctionne
2. **Vérifier** que le port 8080 est accessible
3. **Redémarrer** le serveur si nécessaire

### **Solution 3 : Vérifier le Code**

1. **Vérifier** que le fichier `create.blade.php` est modifié
2. **Vérifier** que la section vocale est présente
3. **Vérifier** que le JavaScript est correct

## 🎉 **Résultat Attendu**

Après avoir suivi ce guide, vous devriez avoir :

-   **🎤 Une carte d'assistant vocal PCMA fonctionnelle**
-   **🟢 Google Assistant actif et opérationnel**
-   **⚫ Whisper désactivé mais visible**
-   **✅ Une interface vocale complètement fonctionnelle**

**🎯 Votre plateforme FIT aura un assistant vocal PCMA professionnel !**

