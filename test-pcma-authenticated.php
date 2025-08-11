<?php
echo "=== Test Viewer PCMA (Authentifié) ===\n";

// Test avec authentification simulée
echo "1. Test de la page PCMA avec authentification...\n";

// Créer une session de test
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$response = curl_exec($ch);
curl_close($ch);

// Se connecter avec un utilisateur existant
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => 'test@example.com',
    'password' => 'password',
    '_token' => 'test-token'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code de réponse login: $httpCode\n";

// Tester l'accès à la page PCMA avec authentification
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/pcma/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code de réponse PCMA: $httpCode\n";

if ($httpCode == 200) {
    echo "✅ Page PCMA accessible avec authentification\n";
    
    // Vérifier les éléments du viewer dans la réponse
    $viewerElements = [
        'dicom-canvas' => 'Canvas du viewer',
        'dicom-viewer-container' => 'Container du viewer',
        'DicomViewer' => 'Classe JavaScript DicomViewer',
        'showViewer()' => 'Fonction showViewer',
        'loadImage(' => 'Fonction loadImage',
        '🔍 Initializing DICOM viewer' => 'Logs de debug'
    ];
    
    echo "\n2. Éléments du viewer dans la page:\n";
    foreach ($viewerElements as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "✅ $element trouvé - $description\n";
        } else {
            echo "❌ $element manquant - $description\n";
        }
    }
    
    // Vérifier les inputs de fichiers
    $fileInputs = [
        'ecg_file' => 'Input ECG',
        'mri_file' => 'Input IRM',
        'xray_file' => 'Input Radiographie',
        'ct_scan_file' => 'Input Scanner',
        'ultrasound_file' => 'Input Échographie'
    ];
    
    echo "\n3. Inputs de fichiers:\n";
    foreach ($fileInputs as $input => $description) {
        if (strpos($response, $input) !== false) {
            echo "✅ $input trouvé - $description\n";
        } else {
            echo "❌ $input manquant - $description\n";
        }
    }
    
    // Vérifier les boutons de contrôle
    $controlButtons = [
        'viewer-zoom-in' => 'Zoom +',
        'viewer-zoom-out' => 'Zoom -',
        'viewer-reset' => 'Reset',
        'viewer-fullscreen' => 'Plein écran',
        'viewer-measure' => 'Mesure',
        'viewer-annotate' => 'Annotation',
        'viewer-screenshot' => 'Screenshot'
    ];
    
    echo "\n4. Boutons de contrôle:\n";
    foreach ($controlButtons as $button => $description) {
        if (strpos($response, $button) !== false) {
            echo "✅ $button trouvé - $description\n";
        } else {
            echo "❌ $button manquant - $description\n";
        }
    }
    
} else {
    echo "❌ Page PCMA non accessible (HTTP $httpCode)\n";
    echo "   - Vérifiez l'authentification\n";
    echo "   - Vérifiez les permissions\n";
}

// Nettoyer les cookies
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "\n=== Instructions de Test Manuel ===\n";
echo "1. Ouvrez votre navigateur\n";
echo "2. Allez sur http://localhost:8000/login\n";
echo "3. Connectez-vous avec:\n";
echo "   - Email: test@example.com\n";
echo "   - Mot de passe: password\n";
echo "4. Allez sur http://localhost:8000/pcma/create\n";
echo "5. Ouvrez la console (F12)\n";
echo "6. Vérifiez les logs de debug:\n";
echo "   - 🔍 Initializing DICOM viewer...\n";
echo "   - ✅ Canvas trouvé, création du viewer...\n";
echo "   - ✅ DicomViewer créé avec succès\n";
echo "7. Testez le viewer:\n";
echo "   - Sélectionnez un fichier image\n";
echo "   - Choisissez le type dans le select\n";
echo "   - Vérifiez que l'image s'affiche\n";
echo "8. Si l'image ne s'affiche pas, vérifiez:\n";
echo "   - Les logs dans la console\n";
echo "   - Que le canvas n'a plus la classe 'hidden'\n";
echo "   - Que la fonction render() est appelée\n";

echo "\n=== Diagnostic Rapide ===\n";
echo "Si le viewer ne fonctionne pas:\n";
echo "1. Vérifiez la console pour les erreurs JavaScript\n";
echo "2. Vérifiez que tous les éléments sont présents\n";
echo "3. Testez avec une image simple (JPG, PNG)\n";
echo "4. Vérifiez que le canvas devient visible\n";
echo "5. Vérifiez que l'image se charge correctement\n";
?> 