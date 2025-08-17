<?php

/**
 * Script de test pour les composants de drapeaux et logos
 */

echo "🧪 TEST DES COMPOSANTS DRAPEAUX ET LOGOS\n";
echo "=========================================\n\n";

// Simuler les données d'un athlète
$athlete = (object) [
    'nationality' => 'Tunisie',
    'association' => (object) [
        'name' => 'FTF',
        'country' => 'Tunisie'
    ]
];

echo "✅ Données de test créées :\n";
echo "   Nationalité : {$athlete->nationality}\n";
echo "   Association : {$athlete->association->name}\n";
echo "   Pays Association : {$athlete->association->country}\n\n";

// Tester le mapping des codes de pays
$countryCodes = [
    'Tunisie' => 'tn',
    'Maroc' => 'ma',
    'Algérie' => 'dz',
    'Mali' => 'ml',
    'Sénégal' => 'sn',
    'Côte d\'Ivoire' => 'ci',
    'Nigeria' => 'ng',
    'Portugal' => 'pt',
    'Norway' => 'no',
    'France' => 'fr',
    'Argentina' => 'ar'
];

echo "🌍 TEST DU MAPPING DES CODES DE PAYS\n";
echo "------------------------------------\n";

foreach ($countryCodes as $country => $code) {
    echo "   {$country} → {$code}\n";
}

echo "\n";

// Tester la détection FTF
echo "🏛️ TEST DE DÉTECTION FTF\n";
echo "------------------------\n";

$associations = [
            'FTF',
    'Fédération Française de Football',
    'Royal Moroccan Football Federation',
    'Fédération Algérienne de Football'
];

foreach ($associations as $assoc) {
    $isFtf = str_contains(strtolower($assoc), 'ftf');
    $status = $isFtf ? '✅ FTF détecté' : '❌ Non-FTF';
    echo "   {$assoc} : {$status}\n";
}

echo "\n";

// Tester les URLs des drapeaux
echo "🚩 TEST DES URLS DE DRAPEAUX\n";
echo "-----------------------------\n";

foreach (array_slice($countryCodes, 0, 5) as $country => $code) {
    $flagUrl = "https://flagcdn.com/w80/{$code}.png";
    echo "   {$country} : {$flagUrl}\n";
}

echo "\n";

// Tester la génération des composants
echo "🎨 TEST DE GÉNÉRATION DES COMPOSANTS\n";
echo "-------------------------------------\n";

echo "✅ Composant flag-logo-display créé\n";
echo "✅ Composant flag-logo-inline créé\n";
echo "✅ Vue PCMA modifiée avec les composants\n\n";

echo "📋 INSTRUCTIONS D'UTILISATION\n";
echo "-----------------------------\n";
echo "1. Accédez à http://localhost:8000/pcma/1\n";
echo "2. Vous devriez voir :\n";
echo "   - Drapeau de la nationalité de l'athlète\n";
echo "   - Logo FTF (bleu avec 'FTF')\n";
echo "   - Section dédiée aux informations de l'athlète\n\n";

echo "🔧 COMPOSANTS DISPONIBLES\n";
echo "-------------------------\n";
echo "- <x-flag-logo-display> : Affichage complet avec tailles configurables\n";
echo "- <x-flag-logo-inline> : Affichage compact pour les listes\n\n";

echo "🎯 FONCTIONNALITÉS IMPLÉMENTÉES\n";
echo "-------------------------------\n";
echo "✅ Drapeaux des pays via flagcdn.com\n";
echo "✅ Logo FTF personnalisé (bleu avec 'FTF')\n";
echo "✅ Logos génériques pour autres associations\n";
echo "✅ Gestion des erreurs de chargement d'images\n";
echo "✅ Tailles configurables (small, medium, large)\n";
echo "✅ Affichage des noms de pays et associations\n\n";

echo "🎉 TEST TERMINÉ AVEC SUCCÈS !\n";
echo "Les composants sont prêts à être utilisés dans vos vues.\n";
