<?php
echo "🔍 **Test de la syntaxe Blade**\n";
echo "🎯 Vérification du fichier create.blade.php\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

echo "📏 Taille du fichier: " . number_format(strlen($content)) . " caractères\n";

// Vérifier les balises Blade
$bladeChecks = [
    '{{' => 'Balises d\'affichage',
    '}}' => 'Balises d\'affichage fermantes',
    '@if' => 'Directives conditionnelles',
    '@endif' => 'Fermeture des directives',
    '@foreach' => 'Boucles foreach',
    '@endforeach' => 'Fermeture des boucles',
    '@extends' => 'Héritage de layout',
    '@section' => 'Sections de contenu',
    '@endsection' => 'Fermeture des sections'
];

echo "\n🔍 Vérification des balises Blade:\n";
foreach ($bladeChecks as $tag => $description) {
    $count = substr_count($content, $tag);
    echo "   $tag: $count occurrences - $description\n";
}

// Vérifier les balises HTML
$htmlChecks = [
    '<div' => 'Balises div',
    '</div>' => 'Balises div fermantes',
    '<script' => 'Balises script',
    '</script>' => 'Balises script fermantes',
    '<form' => 'Balises form',
    '</form>' => 'Balises form fermantes'
];

echo "\n🔍 Vérification des balises HTML:\n";
foreach ($htmlChecks as $tag => $description) {
    $count = substr_count($content, $tag);
    echo "   $tag: $count occurrences - $description\n";
}

// Vérifier l'équilibre des balises
$divOpen = substr_count($content, '<div');
$divClose = substr_count($content, '</div>');
$scriptOpen = substr_count($content, '<script');
$scriptClose = substr_count($content, '</script>');

echo "\n🔍 Équilibre des balises:\n";
echo "   Div: $divOpen ouvertes, $divClose fermées - " . ($divOpen === $divClose ? "✅ Équilibré" : "❌ Déséquilibré") . "\n";
echo "   Script: $scriptOpen ouvertes, $scriptClose fermées - " . ($scriptOpen === $scriptClose ? "✅ Équilibré" : "❌ Déséquilibré") . "\n";

// Vérifier la présence de la section vocale
echo "\n🔍 Section vocale:\n";
$voiceSection = strpos($content, 'id="voice-section"');
if ($voiceSection !== false) {
    echo "   ✅ Section vocale trouvée à la ligne " . (substr_count(substr($content, 0, $voiceSection), "\n") + 1) . "\n";
} else {
    echo "   ❌ Section vocale non trouvée\n";
}

// Vérifier les fonctions JavaScript
echo "\n🔍 Fonctions JavaScript:\n";
$jsFunctions = [
    'function initSpeechRecognition()' => 'Initialisation',
    'function startVoiceRecognition()' => 'Démarrage',
    'function stopVoiceRecognition()' => 'Arrêt',
    'function processVoiceInput(' => 'Traitement',
    'function speakResponse(' => 'Réponse vocale'
];

foreach ($jsFunctions as $func => $description) {
    if (strpos($content, $func) !== false) {
        echo "   ✅ $description\n";
    } else {
        echo "   ❌ $description - NON TROUVÉ\n";
    }
}

echo "\n🎯 **Résumé de la vérification**\n";
if ($divOpen === $divClose && $scriptOpen === $scriptClose) {
    echo "✅ Structure HTML équilibrée\n";
} else {
    echo "❌ Structure HTML déséquilibrée\n";
}
?>

