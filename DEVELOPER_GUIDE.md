# 👨‍💻 Guide du Développeur - Med Predictor

## 🚀 **Bienvenue dans l'équipe !**

Ce guide vous accompagne dans le développement du projet Med Predictor, en particulier la **console vocale Google Cloud Speech-to-Text**.

## 🏗️ **Architecture du projet**

### **Structure des dossiers :**
```
med-predictor/
├── app/
│   ├── Http/Controllers/     # Contrôleurs Laravel
│   ├── Services/             # Services métier
│   └── Models/               # Modèles Eloquent
├── resources/views/
│   ├── pcma/                 # Vues PCMA
│   └── layouts/              # Layouts principaux
├── public/js/                # JavaScript frontend
├── routes/                   # Définition des routes
└── database/                 # Migrations et seeders
```

### **Technologies utilisées :**
- **Backend :** Laravel 10, PHP 8.1+
- **Frontend :** Blade, Tailwind CSS, JavaScript ES6+
- **Base de données :** MySQL 8.0+
- **API externe :** Google Cloud Speech-to-Text
- **Tests :** PHPUnit, Laravel Dusk

## 🎯 **Fonctionnalités principales**

### **1. Console Vocale Google Cloud**
- Reconnaissance vocale en temps réel
- Extraction automatique des données (nom, âge, position, club)
- Intégration avec le formulaire PCMA
- Gestion des erreurs et fallbacks

### **2. Mode Switching**
- Basculement entre modes Manuel, Vocal, OCR, FHIR
- Persistance des données entre modes
- Interface utilisateur responsive

### **3. Formulaire PCMA**
- Gestion des évaluations médicales
- Validation des données
- Sauvegarde en base de données

## 🔧 **Configuration de l'environnement**

### **Prérequis :**
- PHP 8.1+
- Composer 2.0+
- Node.js 16+
- MySQL 8.0+
- Git

### **Installation :**
```bash
# Cloner le projet
git clone https://github.com/username/med-predictor.git
cd med-predictor

# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Compiler les assets
npm run build

# Démarrer le serveur
php artisan serve
```

### **Variables d'environnement :**
```env
APP_NAME="Med Predictor"
APP_ENV=local
APP_DEBUG=true

# Google Cloud Speech-to-Text
GOOGLE_SPEECH_API_KEY=your_google_cloud_api_key

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=med_predictor
DB_USERNAME=root
DB_PASSWORD=

# Cache et sessions
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 📝 **Conventions de code**

### **PHP (Laravel) :**
```php
<?php

namespace App\Http\Controllers;

use App\Models\PCMA;
use App\Services\GoogleSpeechService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PCMAController extends Controller
{
    /**
     * Créer un nouveau PCMA avec reconnaissance vocale
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'player_name' => 'required|string|max:255',
                'age' => 'required|integer|min:16|max:50',
                'position' => 'required|string|max:100',
                'club' => 'required|string|max:255',
            ]);

            // Création du PCMA
            $pcma = PCMA::create($validated);

            return response()->json([
                'success' => true,
                'pcma_id' => $pcma->id,
                'message' => 'PCMA créé avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du PCMA',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### **JavaScript :**
```javascript
/**
 * Service de reconnaissance vocale Google Cloud
 */
class SpeechRecognitionService {
    constructor() {
        this.isListening = false;
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.onResult = null;
        this.onError = null;
    }

    /**
     * Démarrer l'écoute vocale
     */
    async startListening() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream);
            
            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = async () => {
                await this.processAudioData();
            };

            this.mediaRecorder.start();
            this.isListening = true;

        } catch (error) {
            if (this.onError) this.onError(error);
        }
    }

    /**
     * Arrêter l'écoute vocale
     */
    stopListening() {
        if (this.mediaRecorder && this.isListening) {
            this.mediaRecorder.stop();
            this.isListening = false;
        }
    }
}
```

### **Blade (Vue) :**
```blade
@extends('layouts.app')

@section('title', 'Nouveau PCMA - Med Predictor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Mode Selector -->
    <div class="flex space-x-4 mb-8">
        <button type="button" 
                id="mode-manuel" 
                class="mode-selector bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition-colors">
            <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <div class="font-semibold">Mode Manuel</div>
                <div class="text-sm opacity-75">Saisie manuelle des données</div>
            </div>
        </button>

        <button type="button" 
                id="mode-vocal" 
                class="mode-selector bg-gray-100 text-gray-700 p-4 rounded-lg hover:bg-gray-200 transition-colors">
            <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
                <div class="font-semibold">Mode Vocal</div>
                <div class="text-sm opacity-75">Reconnaissance vocale intelligente</div>
            </div>
        </button>
    </div>

    <!-- Content Sections -->
    @include('pcma.partials.manual-mode')
    @include('pcma.partials.vocal-mode')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/SpeechRecognitionService-laravel.js') }}"></script>
<script>
    // Initialisation des modes
    document.addEventListener('DOMContentLoaded', function() {
        initModeManager();
    });
</script>
@endpush
```

## 🧪 **Tests et qualité**

### **Tests unitaires :**
```bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=PCMAControllerTest
php artisan test --filter=SpeechRecognitionTest

# Tests avec couverture
php artisan test --coverage
```

### **Tests de navigateur :**
```bash
# Tests Laravel Dusk
php artisan dusk

# Tests spécifiques
php artisan dusk --filter=testConsoleVocale
php artisan dusk --filter=testModeSwitching
```

### **Qualité du code :**
```bash
# PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# PHPStan (analyse statique)
./vendor/bin/phpstan analyse

# PHPUnit avec couverture
./vendor/bin/phpunit --coverage-html coverage
```

## 🔄 **Workflow de développement**

### **1. Nouvelle fonctionnalité :**
```bash
# Créer une branche feature
git checkout develop-v3
git pull origin develop-v3
git checkout -b feature/nouvelle-fonctionnalite

# Développement...
git add .
git commit -m "feat: description de la fonctionnalité"

# Push et Pull Request
git push origin feature/nouvelle-fonctionnalite
# Créer PR vers develop-v3
```

### **2. Correction de bug :**
```bash
# Créer une branche hotfix
git checkout develop-v3
git checkout -b hotfix/correction-bug

# Correction...
git add .
git commit -m "fix: description de la correction"

# Push et Pull Request
git push origin hotfix/correction-bug
```

### **3. Code Review :**
- Vérifier la logique métier
- Valider la qualité du code
- S'assurer que les tests passent
- Vérifier la documentation

## 📚 **Documentation à maintenir**

### **Fichiers à mettre à jour :**
- `README.md` : Vue d'ensemble du projet
- `API_DOCS.md` : Documentation des APIs
- `CHANGELOG.md` : Historique des versions
- `DEPLOYMENT.md` : Guide de déploiement

### **Commentaires de code :**
- Documenter les fonctions complexes
- Expliquer la logique métier
- Ajouter des exemples d'utilisation
- Maintenir la cohérence

## 🚨 **Points d'attention**

### **Sécurité :**
- Valider toutes les entrées utilisateur
- Utiliser les middlewares d'authentification
- Protéger les routes sensibles
- Ne jamais exposer les clés API

### **Performance :**
- Optimiser les requêtes de base de données
- Mettre en cache les données statiques
- Lazy loading des composants
- Compression des assets

### **Maintenabilité :**
- Code lisible et bien structuré
- Nommage explicite des variables
- Séparation des responsabilités
- Tests automatisés

## 🆘 **Support et ressources**

### **Documentation officielle :**
- [Laravel Documentation](https://laravel.com/docs)
- [Google Cloud Speech-to-Text](https://cloud.google.com/speech-to-text/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)

### **Équipe :**
- **Lead Developer :** Izhar Mahjoub
- **Email :** `izhar@med-predictor.com`
- **Slack :** `#developers`

### **Ressources internes :**
- `COLLABORATION_GUIDE.md` : Guide de collaboration
- `TESTING_GUIDE.md` : Guide des tests
- Issues GitHub : Suivi des tâches

---

**Dernière mise à jour :** 23 Août 2025  
**Version :** 1.0.0  
**Maintenu par :** Équipe de développement Med Predictor
