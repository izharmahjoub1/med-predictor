<?php
/**
 * 🧪 Test rapide - Vérification du logo JSK
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Blade;

echo "🧪 TEST DU LOGO JSK POUR JS KAIROUAN\n";
echo "=====================================\n\n";

// Simuler un club JS Kairouan
$club = (object) ['name' => 'JS Kairouan'];

// Rendre le composant
$html = Blade::render('<x-club-logo-working :club="$club" class="w-20 h-20" />', ['club' => $club]);

echo "🏟️ Club : {$club->name}\n";
echo "🔍 HTML généré :\n";
echo $html . "\n\n";

// Vérifier que le fichier JSK.webp existe
$jskPath = public_path('clubs/JSK.webp');
if (file_exists($jskPath)) {
    $size = round(filesize($jskPath) / 1024, 1);
    echo "✅ Logo JSK.webp trouvé ({$size} KB)\n";
} else {
    echo "❌ Logo JSK.webp manquant\n";
}

// Vérifier que le fichier JSO.webp existe aussi
$jsoPath = public_path('clubs/JSO.webp');
if (file_exists($jsoPath)) {
    $size = round(filesize($jsoPath) / 1024, 1);
    echo "✅ Logo JSO.webp trouvé ({$size} KB)\n";
} else {
    echo "❌ Logo JSO.webp manquant\n";
}

echo "\n🎯 RÉSULTAT :\n";
echo "JS Kairouan doit maintenant afficher JSK.webp au lieu de JSO.webp\n";
?>

