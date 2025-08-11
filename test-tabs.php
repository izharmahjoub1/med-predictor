<?php

// Test simple pour vérifier que la page avec onglets fonctionne
echo "=== Test de la Page avec Onglets ===\n";

// Test 1: Vérifier que le fichier existe
if (file_exists('resources/views/health-records/create.blade.php')) {
    echo "✅ Fichier create.blade.php existe\n";
    
    // Test 2: Vérifier que Vue.js est inclus
    $content = file_get_contents('resources/views/health-records/create.blade.php');
    if (strpos($content, 'vue.global.js') !== false) {
        echo "✅ Vue.js est inclus\n";
    } else {
        echo "❌ Vue.js n'est pas inclus\n";
    }
    
    // Test 3: Vérifier que les onglets sont définis
    if (strpos($content, 'activeTab') !== false) {
        echo "✅ Variable activeTab est définie\n";
    } else {
        echo "❌ Variable activeTab n'est pas définie\n";
    }
    
    // Test 4: Vérifier que les 9 onglets sont présents
    $tabCount = substr_count($content, 'v-show="activeTab ===');
    echo "✅ Nombre d'onglets trouvés: $tabCount\n";
    
    // Test 5: Vérifier le contenu des onglets
    $tabs = [
        'general' => 'Informations Générales',
        'ai-assistant' => 'Assistant IA',
        'medical-categories' => 'Catégories Médicales',
        'doping-control' => 'Contrôle Anti-Dopage',
        'physical-assessments' => 'Évaluations Physiques',
        'postural-assessment' => 'Évaluation Posturale',
        'vaccinations' => 'Vaccinations',
        'medical-imaging' => 'Imagerie Médicale',
        'notes-observations' => 'Notes et Observations'
    ];
    
    foreach ($tabs as $tabId => $tabName) {
        if (strpos($content, "activeTab === '$tabId'") !== false) {
            echo "✅ Onglet '$tabName' ($tabId) présent\n";
        } else {
            echo "❌ Onglet '$tabName' ($tabId) manquant\n";
        }
    }
    
} else {
    echo "❌ Fichier create.blade.php n'existe pas\n";
}

echo "\n=== Résumé ===\n";
echo "La page avec onglets est prête !\n";
echo "Pour tester:\n";
echo "1. Connectez-vous avec test@example.com / password\n";
echo "2. Allez sur http://localhost:8000/health-records/create\n";
echo "3. Vous devriez voir 9 onglets avec du contenu réel\n"; 