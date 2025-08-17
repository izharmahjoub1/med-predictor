<?php
echo "🔍 TEST RAPIDE DES LOGOS FTF\n";
echo "============================\n\n";

$clubs = ['EST', 'ESS', 'USM', 'CA', 'CSS', 'CAB', 'ST', 'USBG', 'OB', 'ASG', 'ESM', 'ESZ', 'JSO', 'EGSG', 'ASS', 'UST'];

foreach ($clubs as $code) {
    $webpPath = "public/clubs/{$code}.webp";
    $webpExists = file_exists($webpPath);
    $webpSize = $webpExists ? filesize($webpPath) : 0;
    
    $url = "http://localhost:8000/clubs/{$code}.webp";
    $headers = get_headers($url);
    $httpCode = $headers ? explode(' ', $headers[0])[1] : 'ERROR';
    
    $status = $httpCode == '200' ? '✅' : '❌';
    
    echo "{$status} {$code}: ";
    echo "Fichier: " . ($webpExists ? "OUI ({$webpSize} bytes)" : "NON");
    echo " | Web: HTTP {$httpCode}\n";
}

echo "\n🎯 Résumé: Seuls EST, ESS, USM fonctionnent\n";
echo "🔧 Problème probable: URLs incorrectes ou serveur web\n";
?>

