<?php
/**
 * 🧪 Test simple des logos des clubs FTF
 * 📅 Créé le 15 Août 2025
 */

echo "🧪 TEST SIMPLE DES LOGOS DES CLUBS FTF\n";
echo "=====================================\n\n";

$clubsDir = __DIR__ . '/public/clubs';
$clubs = [
    'EST' => 'Esperance Sportive de Tunis',
    'ESS' => 'Etoile Sportive du Sahel',
    'CA' => 'Club Africain',
    'CSS' => 'CS Sfaxien',
    'CAB' => 'CA Bizertin',
    'ST' => 'Stade Tunisien',
    'USM' => 'US Monastirienne',
    'USBG' => 'US Ben Guerdane',
    'OB' => 'Olympique de Béja',
    'ASG' => 'Avenir Sportif de Gabès',
    'ESM' => 'ES de Métlaoui',
    'ESZ' => 'ES de Zarzis',
    'JSO' => 'JS de el Omrane',
    'EGSG' => 'El Gawafel de Gafsa',
    'ASS' => 'AS Soliman',
    'UST' => 'US Tataouine'
];

$results = [
    'total' => count($clubs),
    'webp' => 0,
    'png' => 0,
    'missing' => 0,
    'details' => []
];

foreach ($clubs as $code => $name) {
    echo "🏟️ Test du logo $code - $name...\n";
    
    $webpPath = $clubsDir . "/$code.webp";
    $pngPath = $clubsDir . "/$code.png";
    
    if (file_exists($webpPath)) {
        $size = filesize($webpPath);
        $sizeKB = round($size / 1024, 1);
        echo "   ✅ Logo WebP trouvé ($sizeKB KB)\n";
        $results['webp']++;
        $results['details'][$code] = ['type' => 'webp', 'size' => $sizeKB, 'status' => 'OK'];
    } elseif (file_exists($pngPath)) {
        $size = filesize($pngPath);
        $sizeKB = round($size / 1024, 1);
        echo "   📁 Logo PNG trouvé ($sizeKB KB)\n";
        $results['png']++;
        $results['details'][$code] = ['type' => 'png', 'size' => $sizeKB, 'status' => 'OK'];
    } else {
        echo "   ❌ Aucun logo trouvé\n";
        $results['missing']++;
        $results['details'][$code] = ['type' => 'none', 'status' => 'MISSING'];
    }
    
    echo "\n";
}

// Résumé
echo "📊 RÉSUMÉ DES TESTS\n";
echo "====================\n";
echo "Total des clubs FTF : {$results['total']}\n";
echo "✅ Logos WebP : {$results['webp']}\n";
echo "📁 Logos PNG : {$results['png']}\n";
echo "❌ Logos manquants : {$results['missing']}\n\n";

echo "🏆 DÉTAILS PAR CLUB :\n";
foreach ($results['details'] as $code => $info) {
    $status = $info['status'] === 'OK' ? '✅' : '❌';
    $logoInfo = $info['type'] !== 'none' ? strtoupper($info['type']) . " ({$info['size']} KB)" : 'Manquant';
    echo "  $status $code - $logoInfo\n";
}

echo "\n📁 Dossier des logos : $clubsDir\n";
echo "🎯 Prochaine étape : Tester l'affichage dans les vues Blade\n";

if ($results['missing'] === 0) {
    echo "🎉 Tous les logos sont présents ! Le problème vient probablement du serveur web.\n";
} else {
    echo "⚠️  Certains logos sont manquants. Vérifiez le téléchargement.\n";
}
?>

