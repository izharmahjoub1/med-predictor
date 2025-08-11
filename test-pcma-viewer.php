<?php
echo "=== Test Viewer PCMA ===\n";

// Test 1: Vérifier que le serveur Laravel fonctionne
echo "1. Test du serveur Laravel...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Serveur Laravel accessible (HTTP $httpCode)\n";
} else {
    echo "❌ Serveur Laravel non accessible (HTTP $httpCode)\n";
    echo "   - Démarrez le serveur: php artisan serve\n";
    return;
}

// Test 2: Vérifier l'accessibilité de la page PCMA
echo "\n2. Test de la page PCMA...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/pcma/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Page PCMA accessible (HTTP $httpCode)\n";
    
    // Vérifier les éléments clés dans la réponse
    $keyElements = [
        'dicom-canvas' => 'Canvas du viewer',
        'dicom-viewer-container' => 'Container du viewer',
        'DicomViewer' => 'Classe JavaScript DicomViewer',
        'showViewer()' => 'Fonction showViewer',
        'loadImage(' => 'Fonction loadImage'
    ];
    
    echo "\n3. Éléments dans la page:\n";
    foreach ($keyElements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "✅ $element trouvé - $description\n";
        } else {
            echo "❌ $element manquant - $description\n";
        }
    }
    
} else {
    echo "❌ Page PCMA non accessible (HTTP $httpCode)\n";
    echo "   - Vérifiez que la route pcma.create existe\n";
    echo "   - Vérifiez que le contrôleur PCMAController existe\n";
    return;
}

// Test 3: Vérifier les routes PCMA
echo "\n4. Test des routes PCMA...\n";
$routes = [
    'http://localhost:8000/pcma' => 'Index PCMA',
    'http://localhost:8000/pcma/create' => 'Create PCMA',
    'http://localhost:8000/pcma/dashboard' => 'Dashboard PCMA'
];

foreach ($routes as $url => $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "✅ $description accessible (HTTP $httpCode)\n";
    } else {
        echo "❌ $description non accessible (HTTP $httpCode)\n";
    }
}

echo "\n=== Instructions de Debug ===\n";
echo "1. Ouvrez votre navigateur et allez sur: http://localhost:8000/pcma/create\n";
echo "2. Ouvrez la console du navigateur (F12)\n";
echo "3. Vérifiez les logs de debug:\n";
echo "   - 🔍 Initializing DICOM viewer...\n";
echo "   - ✅ Canvas trouvé, création du viewer...\n";
echo "   - ✅ DicomViewer créé avec succès\n";
echo "4. Testez le chargement d'une image:\n";
echo "   - Sélectionnez un fichier image (JPG, PNG, etc.)\n";
echo "   - Choisissez le type dans le select 'Sélectionner un fichier à visualiser'\n";
echo "   - Vérifiez les logs:\n";
echo "     * 🔍 Loading image: [nom du fichier]\n";
echo "     * ✅ File read successfully\n";
echo "     * ✅ Image loaded successfully: [dimensions]\n";
echo "     * 🔍 Showing viewer...\n";
echo "     * ✅ Canvas hidden class removed\n";
echo "     * 🔄 Re-rendering after show...\n";
echo "5. Si l'image ne s'affiche pas, vérifiez:\n";
echo "   - Que le canvas n'a plus la classe 'hidden'\n";
echo "   - Que les dimensions du canvas sont correctes\n";
echo "   - Que la fonction render() est appelée\n";
echo "   - Qu'il n'y a pas d'erreurs JavaScript\n";

echo "\n=== Problèmes Courants ===\n";
echo "1. Canvas invisible: Vérifiez que showViewer() est appelée\n";
echo "2. Image non chargée: Vérifiez que loadImage() fonctionne\n";
echo "3. Erreur de rendu: Vérifiez les dimensions du canvas\n";
echo "4. JavaScript non chargé: Vérifiez la console pour les erreurs\n";
echo "5. Fichier non trouvé: Vérifiez que le fichier est bien uploadé\n";

echo "\n=== Commandes de Test ===\n";
echo "Pour tester complètement:\n";
echo "1. php artisan serve\n";
echo "2. Ouvrir http://localhost:8000/pcma/create\n";
echo "3. Uploader une image test\n";
echo "4. Sélectionner le type dans le viewer\n";
echo "5. Vérifier que l'image s'affiche\n";
echo "6. Tester les contrôles (zoom, pan, etc.)\n";
?> 