#!/bin/bash

# ========================================
# SCRIPT DE SAUVEGARDE FIFA PORTAL COMPLET
# ========================================
# Date: $(date)
# Description: Sauvegarde complète du FIFA Portal avec toutes les modifications
# 
# FICHIERS SAUVEGARDÉS:
# - FIFA Portal intégré (vue principale)
# - Contrôleur FIFA
# - Routes web
# - Modèle Player
# - Composants Blade
# - CSS et JavaScript
# ========================================

echo "🚀 DÉBUT DE LA SAUVEGARDE FIFA PORTAL COMPLET..."
echo "📅 Date: $(date)"
echo "📁 Dossier de sauvegarde: backups/fifa-portal-complete/"
echo ""

# Créer le dossier de sauvegarde
BACKUP_DIR="backups/fifa-portal-complete"
mkdir -p "$BACKUP_DIR"

# 1. SAUVEGARDE DE LA VUE PRINCIPALE FIFA PORTAL
echo "📄 Sauvegarde de la vue principale FIFA Portal..."
cp "resources/views/fifa-portal-integrated.blade.php" "$BACKUP_DIR/fifa-portal-integrated-$(date +%Y%m%d-%H%M%S).blade.php"

# 2. SAUVEGARDE DU CONTRÔLEUR FIFA
echo "🎮 Sauvegarde du contrôleur FIFA..."
cp "app/Http/Controllers/FIFATestController.php" "$BACKUP_DIR/FIFATestController-$(date +%Y%m%d-%H%M%S).php"

# 3. SAUVEGARDE DES ROUTES WEB
echo "🛣️ Sauvegarde des routes web..."
cp "routes/web.php" "$BACKUP_DIR/web-routes-$(date +%Y%m%d-%H%M%S).php"

# 4. SAUVEGARDE DU MODÈLE PLAYER
echo "👤 Sauvegarde du modèle Player..."
cp "app/Models/Player.php" "$BACKUP_DIR/Player-model-$(date +%Y%m%d-%H%M%S).php"

# 5. SAUVEGARDE DES COMPOSANTS BLADE
echo "🧩 Sauvegarde des composants Blade..."
cp "resources/views/components/fifa-association-logo.blade.php" "$BACKUP_DIR/fifa-association-logo-$(date +%Y%m%d-%H%M%S).blade.php"

# 6. SAUVEGARDE DU FICHIER CSS PRINCIPAL
echo "🎨 Sauvegarde du CSS principal..."
cp "resources/css/app.css" "$BACKUP_DIR/app-css-$(date +%Y%m%d-%H%M%S).css"

# 7. SAUVEGARDE DU FICHIER JAVASCRIPT PRINCIPAL
echo "⚡ Sauvegarde du JavaScript principal..."
cp "resources/js/app.js" "$BACKUP_DIR/app-js-$(date +%Y%m%d-%H%M%S).js"

# 8. CRÉER UN FICHIER DE DESCRIPTION DE LA SAUVEGARDE
echo "📝 Création du fichier de description..."
cat > "$BACKUP_DIR/SAUVEGARDE-DESCRIPTION.txt" << 'EOF'
========================================
SAUVEGARDE FIFA PORTAL COMPLET
========================================
Date: $(date)
Version: FIFA Portal v2.0 - Complète
Description: Sauvegarde complète du FIFA Portal avec toutes les modifications

FICHIERS SAUVEGARDÉS:
=====================

1. VUE PRINCIPALE:
   - fifa-portal-integrated-*.blade.php
   - Vue principale du portail FIFA avec toutes les fonctionnalités

2. CONTRÔLEUR:
   - FIFATestController-*.php
   - Contrôleur principal pour le portail FIFA

3. ROUTES:
   - web-routes-*.php
   - Toutes les routes web de l'application

4. MODÈLE:
   - Player-model-*.php
   - Modèle Eloquent pour les joueurs

5. COMPOSANTS:
   - fifa-association-logo-*.blade.php
   - Composants Blade réutilisables

6. ASSETS:
   - app-css-*.css
   - app-js-*.js
   - Fichiers CSS et JavaScript principaux

MODIFICATIONS PRINCIPALES INCLUSES:
==================================

✅ PORTAL FIFA COMPLET:
   - Interface moderne avec glassmorphism
   - 4 blocs de stats (OVR, POT, FIT, FORME)
   - Navigation entre joueurs
   - Recherche dynamique
   - Onglets multiples (Performances, Médical, Devices, etc.)

✅ ALIGNEMENT ET POSITIONNEMENT:
   - Zone Licence/FIFA ID centrée sous les stats
   - Drapeau de nationalité à droite
   - Logos de club et association harmonisés
   - Photo du joueur agrandie (12rem x 12rem)

✅ SUPPRESSION DES EMOJIS:
   - Interface professionnelle sans emojis
   - Texte descriptif à la place des icônes
   - Logs console professionnels

✅ RESPONSIVE ET ADAPTATIF:
   - Largeur complète de la page
   - Grille adaptative
   - Transitions fluides

RESTAURATION:
=============
Pour restaurer cette sauvegarde:

1. Copier les fichiers depuis le dossier de sauvegarde
2. Remplacer les fichiers actuels
3. Vider le cache: php artisan view:clear
4. Vider le cache des routes: php artisan route:clear

COMMANDES DE RESTAURATION:
=========================
# Restaurer la vue principale
cp backups/fifa-portal-complete/fifa-portal-integrated-*.blade.php resources/views/fifa-portal-integrated.blade.php

# Restaurer le contrôleur
cp backups/fifa-portal-complete/FIFATestController-*.php app/Http/Controllers/FIFATestController.php

# Restaurer les routes
cp backups/fifa-portal-complete/web-routes-*.php routes/web.php

# Vider les caches
php artisan view:clear
php artisan route:clear
php artisan cache:clear

NOTES IMPORTANTES:
=================
- Cette sauvegarde inclut TOUTES les modifications
- Les fichiers sont horodatés pour éviter les conflits
- Tester la restauration dans un environnement de développement
- Vérifier la compatibilité avec la version Laravel actuelle

========================================
EOF

# 9. CRÉER UN SCRIPT DE RESTAURATION
echo "🔄 Création du script de restauration..."
cat > "$BACKUP_DIR/restore-backup.sh" << 'EOF'
#!/bin/bash

# ========================================
# SCRIPT DE RESTAURATION FIFA PORTAL
# ========================================
# Ce script restaure la sauvegarde FIFA Portal
# ========================================

echo "🔄 DÉBUT DE LA RESTAURATION FIFA PORTAL..."
echo "📁 Dossier de sauvegarde: $(pwd)"
echo ""

# Vérifier que nous sommes dans le bon dossier
if [ ! -f "SAUVEGARDE-DESCRIPTION.txt" ]; then
    echo "❌ ERREUR: Ce script doit être exécuté depuis le dossier de sauvegarde"
    exit 1
fi

# Trouver les fichiers de sauvegarde les plus récents
VUE_SAUVEGARDE=$(ls -t fifa-portal-integrated-*.blade.php | head -1)
CONTROLEUR_SAUVEGARDE=$(ls -t FIFATestController-*.php | head -1)
ROUTES_SAUVEGARDE=$(ls -t web-routes-*.php | head -1)
MODELE_SAUVEGARDE=$(ls -t Player-model-*.php | head -1)
COMPOSANT_SAUVEGARDE=$(ls -t fifa-association-logo-*.blade.php | head -1)
CSS_SAUVEGARDE=$(ls -t app-css-*.css | head -1)
JS_SAUVEGARDE=$(ls -t app-js-*.js | head -1)

echo "📄 Fichiers de sauvegarde trouvés:"
echo "   Vue: $VUE_SAUVEGARDE"
echo "   Contrôleur: $CONTROLEUR_SAUVEGARDE"
echo "   Routes: $ROUTES_SAUVEGARDE"
echo "   Modèle: $MODELE_SAUVEGARDE"
echo "   Composant: $COMPOSANT_SAUVEGARDE"
echo "   CSS: $CSS_SAUVEGARDE"
echo "   JS: $JS_SAUVEGARDE"
echo ""

# Demander confirmation
read -p "⚠️  Êtes-vous sûr de vouloir restaurer cette sauvegarde ? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Restauration annulée"
    exit 1
fi

echo "🚀 Restauration en cours..."

# Restaurer les fichiers
echo "📄 Restauration de la vue principale..."
cp "$VUE_SAUVEGARDE" "../../resources/views/fifa-portal-integrated.blade.php"

echo "🎮 Restauration du contrôleur..."
cp "$CONTROLEUR_SAUVEGARDE" "../../app/Http/Controllers/FIFATestController.php"

echo "🛣️ Restauration des routes..."
cp "$ROUTES_SAUVEGARDE" "../../routes/web.php"

echo "👤 Restauration du modèle..."
cp "$MODELE_SAUVEGARDE" "../../app/Models/Player.php"

echo "🧩 Restauration du composant..."
cp "$COMPOSANT_SAUVEGARDE" "../../resources/views/components/fifa-association-logo.blade.php"

echo "🎨 Restauration du CSS..."
cp "$CSS_SAUVEGARDE" "../../resources/css/app.css"

echo "⚡ Restauration du JavaScript..."
cp "$JS_SAUVEGARDE" "../../resources/js/app.js"

# Vider les caches
echo "🧹 Vidage des caches..."
cd ../..
php artisan view:clear
php artisan route:clear
php artisan cache:clear

echo ""
echo "✅ RESTAURATION TERMINÉE AVEC SUCCÈS !"
echo "🌐 Testez maintenant: http://localhost:8001/fifa-portal"
echo ""
echo "📝 Fichiers restaurés:"
echo "   - Vue principale FIFA Portal"
echo "   - Contrôleur FIFA"
echo "   - Routes web"
echo "   - Modèle Player"
echo "   - Composants Blade"
echo "   - CSS et JavaScript"
echo ""
echo "🎯 Le FIFA Portal devrait maintenant être exactement comme dans la sauvegarde !"
EOF

# 10. RENDRE LE SCRIPT DE RESTAURATION EXÉCUTABLE
chmod +x "$BACKUP_DIR/restore-backup.sh"

# 11. CRÉER UN FICHIER DE VÉRIFICATION
echo "🔍 Création du fichier de vérification..."
cat > "$BACKUP_DIR/VERIFICATION-SAUVEGARDE.md" << 'EOF'
# VÉRIFICATION DE LA SAUVEGARDE FIFA PORTAL

## 📋 Liste des fichiers sauvegardés

### ✅ Fichiers principaux
- [ ] `fifa-portal-integrated-*.blade.php` - Vue principale
- [ ] `FIFATestController-*.php` - Contrôleur
- [ ] `web-routes-*.php` - Routes web
- [ ] `Player-model-*.php` - Modèle Player

### ✅ Composants et assets
- [ ] `fifa-association-logo-*.blade.php` - Composant
- [ ] `app-css-*.css` - Styles CSS
- [ ] `app-js-*.js` - JavaScript

### ✅ Scripts et documentation
- [ ] `backup-script.sh` - Script de sauvegarde
- [ ] `restore-backup.sh` - Script de restauration
- [ ] `SAUVEGARDE-DESCRIPTION.txt` - Description complète
- [ ] `VERIFICATION-SAUVEGARDE.md` - Ce fichier

## 🧪 Test de la sauvegarde

1. **Vérifier les fichiers** : Tous les fichiers doivent être présents
2. **Tester la restauration** : Utiliser le script `restore-backup.sh`
3. **Vérifier le fonctionnement** : Tester le FIFA Portal après restauration

## 📁 Structure du dossier de sauvegarde

```
backups/fifa-portal-complete/
├── fifa-portal-integrated-*.blade.php
├── FIFATestController-*.php
├── web-routes-*.php
├── Player-model-*.php
├── fifa-association-logo-*.blade.php
├── app-css-*.css
├── app-js-*.js
├── backup-script.sh
├── restore-backup.sh
├── SAUVEGARDE-DESCRIPTION.txt
└── VERIFICATION-SAUVEGARDE.md
```

## 🚀 Commandes de test

```bash
# Vérifier le contenu
ls -la backups/fifa-portal-complete/

# Tester la restauration
cd backups/fifa-portal-complete/
./restore-backup.sh

# Vérifier le fonctionnement
php artisan serve
# Ouvrir http://localhost:8001/fifa-portal
```
EOF

# 12. AFFICHER LE RÉSUMÉ FINAL
echo ""
echo "✅ SAUVEGARDE TERMINÉE AVEC SUCCÈS !"
echo "========================================"
echo "📁 Dossier: $BACKUP_DIR"
echo "📅 Date: $(date)"
echo "📊 Fichiers sauvegardés: $(ls -1 "$BACKUP_DIR" | wc -l)"
echo ""
echo "📋 CONTENU DE LA SAUVEGARDE:"
echo "   🎮 FIFA Portal complet (vue + contrôleur)"
echo "   🛣️ Routes web complètes"
echo "   👤 Modèle Player"
echo "   🧩 Composants Blade"
echo "   🎨 CSS et JavaScript"
echo "   📝 Documentation complète"
echo "   🔄 Script de restauration automatique"
echo ""
echo "🚀 POUR RESTAURER:"
echo "   cd $BACKUP_DIR"
echo "   ./restore-backup.sh"
echo ""
echo "🌐 POUR TESTER:"
echo "   http://localhost:8001/fifa-portal"
echo ""
echo "🎯 SAUVEGARDE COMPLÈTE ET PROFESSIONNELLE CRÉÉE !"

