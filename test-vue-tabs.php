<?php

echo "=== Test Vue.js et Onglets ===\n";

// Test 1: Vérifier que le fichier existe
if (file_exists('resources/views/health-records/create.blade.php')) {
    echo "✅ Fichier create.blade.php existe\n";
    
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    
    // Test 2: Vérifier que Vue.js est inclus
    if (strpos($content, 'vue.global.js') !== false) {
        echo "✅ Vue.js est inclus\n";
    } else {
        echo "❌ Vue.js n'est pas inclus\n";
    }
    
    // Test 3: Vérifier que le script attend le DOM
    if (strpos($content, 'DOMContentLoaded') !== false) {
        echo "✅ Script attend le chargement du DOM\n";
    } else {
        echo "❌ Script n'attend pas le chargement du DOM\n";
    }
    
    // Test 4: Vérifier que Vue est vérifié
    if (strpos($content, 'typeof Vue') !== false) {
        echo "✅ Vérification de Vue.js présente\n";
    } else {
        echo "❌ Vérification de Vue.js manquante\n";
    }
    
    // Test 5: Vérifier la gestion d'erreur
    if (strpos($content, 'try {') !== false && strpos($content, 'catch (error)') !== false) {
        echo "✅ Gestion d'erreur présente\n";
    } else {
        echo "❌ Gestion d'erreur manquante\n";
    }
    
    // Test 6: Vérifier que les onglets sont bien définis
    $tabCount = substr_count($content, "activeTab === '");
    echo "✅ Nombre d'onglets trouvés: $tabCount\n";
    
    // Test 7: Vérifier que la navigation des onglets existe
    if (strpos($content, 'tabs-nav') !== false) {
        echo "✅ Navigation des onglets présente\n";
    } else {
        echo "❌ Navigation des onglets manquante\n";
    }
    
    // Test 8: Vérifier le CSS des onglets
    if (strpos($content, '.tab-button') !== false && strpos($content, '.tab-button.active') !== false) {
        echo "✅ CSS des onglets présent\n";
    } else {
        echo "❌ CSS des onglets manquant\n";
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Instructions de Test ===\n";
echo "1. Connectez-vous avec test@example.com / password\n";
echo "2. Allez sur http://localhost:8000/health-records/create\n";
echo "3. Ouvrez la console du navigateur (F12)\n";
echo "4. Vous devriez voir: 'Vue.js application montée avec succès'\n";
echo "5. Les onglets devraient être cliquables et navigables\n";
echo "6. Seul l'onglet actif devrait être visible\n";

echo "\n=== Dépannage ===\n";
echo "Si les onglets ne fonctionnent pas:\n";
echo "- Vérifiez la console pour les erreurs JavaScript\n";
echo "- Assurez-vous que Vue.js se charge (pas d'erreur 404)\n";
echo "- Vérifiez que le CSS est appliqué (onglets visibles)\n"; 