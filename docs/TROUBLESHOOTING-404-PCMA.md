# 🔧 Guide de résolution - Erreur 404 sur la route PCMA

## 🚨 Problème

```
GET http://localhost:8000/pcma/voice-fallback
404 Not Found
```

## 🔍 Diagnostic

### 1. Vérifier que le serveur Laravel fonctionne

```bash
# Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=8000

# Vérifier qu'il écoute
curl http://localhost:8000
```

### 2. Vérifier la configuration des routes

La route PCMA doit être dans `routes/web.php`, pas dans `routes/api.php`.

**✅ Correct (routes/web.php):**

```php
Route::get('/pcma/voice-fallback', function () {
    return view('pcma.voice-fallback');
})->name('pcma.voice-fallback');
```

**❌ Incorrect (routes/api.php):**

```php
Route::get('/pcma/voice-fallback', function () {
    return view('pcma.voice-fallback');
})->name('api.pcma.voice-fallback');
```

### 3. Vérifier l'existence du fichier de vue

```bash
ls -la resources/views/pcma/voice-fallback.blade.php
```

### 4. Vérifier les logs Laravel

```bash
tail -f storage/logs/laravel.log
```

## 🛠️ Solutions

### Solution 1: Vérifier la configuration des routes

```bash
# Vérifier que la route est dans web.php
grep -n "pcma/voice-fallback" routes/web.php

# Vérifier qu'elle n'est pas dans api.php
grep -n "pcma/voice-fallback" routes/api.php
```

### Solution 2: Nettoyer le cache des routes

```bash
php artisan route:clear
php artisan route:cache
php artisan config:clear
php artisan view:clear
```

### Solution 3: Vérifier la structure des dossiers

```bash
# Créer le dossier si nécessaire
mkdir -p resources/views/pcma

# Vérifier les permissions
chmod 755 resources/views/pcma
chmod 644 resources/views/pcma/voice-fallback.blade.php
```

### Solution 4: Test avec le script de diagnostic

```bash
php scripts/test-fallback-route.php
```

## 📋 Vérification complète

### 1. Structure des fichiers

```
routes/
├── web.php                    # ✅ Route PCMA ici
└── api.php                    # ❌ Pas de route PCMA ici

resources/views/
└── pcma/
    └── voice-fallback.blade.php  # ✅ Vue PCMA ici
```

### 2. Contenu de la route (routes/web.php)

```php
// Interface web de fallback pour PCMA (complètement publique)
Route::get('/pcma/voice-fallback', function () {
    return view('pcma.voice-fallback');
})->name('pcma.voice-fallback');
```

### 3. Test de la route

```bash
# Lister toutes les routes
php artisan route:list | grep pcma

# Tester la route spécifique
curl -v http://localhost:8000/pcma/voice-fallback
```

## 🚀 Démarrage rapide

### Étape 1: Démarrer le serveur

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Étape 2: Tester la route

```bash
php scripts/test-fallback-route.php
```

### Étape 3: Ouvrir dans le navigateur

```
http://localhost:8000/pcma/voice-fallback
```

## 🔗 URLs importantes

-   **Route PCMA** : `http://localhost:8000/pcma/voice-fallback`
-   **API Google Assistant** : `http://localhost:8000/api/google-assistant/webhook`
-   **Santé API** : `http://localhost:8000/api/google-assistant/health`

## 📞 Support

Si le problème persiste :

1. **Vérifiez les logs** : `tail -f storage/logs/laravel.log`
2. **Testez avec le script** : `php scripts/test-fallback-route.php`
3. **Vérifiez la configuration** : `php artisan config:show`
4. **Listez les routes** : `php artisan route:list`

---

_Guide créé le : {{ date('d/m/Y H:i') }}_  
_Version : 1.0 - Résolution erreur 404 PCMA_
