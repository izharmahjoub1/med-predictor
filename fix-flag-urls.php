<?php

/**
 * Script pour corriger les URLs des drapeaux avec les codes pays ISO
 */

echo "🏳️ CORRECTION DES URLS DES DRAPEAUX\n";
echo "====================================\n\n";

// Mappage des noms de pays vers les codes ISO
$countryToISO = [
    'Tunisie' => 'tn',
    'Algérie' => 'dz', 
    'Maroc' => 'ma',
    'Portugal' => 'pt',
    'France' => 'fr',
    'Norway' => 'no',
    'Côte d\'Ivoire' => 'ci',
    'Mali' => 'ml',
    'Sénégal' => 'sn',
    'Cameroun' => 'cm',
    'Nigeria' => 'ng',
    'Égypte' => 'eg',
    'Ghana' => 'gh',
    'Kenya' => 'ke',
    'Afrique du Sud' => 'za',
    'Brasil' => 'br',
    'Argentina' => 'ar',
    'Uruguay' => 'uy',
    'Chile' => 'cl',
    'Colombia' => 'co',
    'Mexico' => 'mx',
    'USA' => 'us',
    'Canada' => 'ca',
    'England' => 'gb-eng',
    'Spain' => 'es',
    'Germany' => 'de',
    'Italy' => 'it',
    'Netherlands' => 'nl',
    'Belgium' => 'be',
    'Switzerland' => 'ch',
    'Austria' => 'at',
    'Poland' => 'pl',
    'Czech Republic' => 'cz',
    'Hungary' => 'hu',
    'Romania' => 'ro',
    'Bulgaria' => 'bg',
    'Greece' => 'gr',
    'Turkey' => 'tr',
    'Russia' => 'ru',
    'Ukraine' => 'ua',
    'Belarus' => 'by',
    'Lithuania' => 'lt',
    'Latvia' => 'lv',
    'Estonia' => 'ee',
    'Finland' => 'fi',
    'Sweden' => 'se',
    'Denmark' => 'dk',
    'Norway' => 'no',
    'Iceland' => 'is',
    'Ireland' => 'ie',
    'Scotland' => 'gb-sct',
    'Wales' => 'gb-wls',
    'Northern Ireland' => 'gb-nir'
];

echo "🌍 MAPPAGE DES PAYS VERS CODES ISO\n";
echo "----------------------------------\n";

foreach ($countryToISO as $country => $iso) {
    echo "🏳️ {$country} → {$iso}\n";
}

echo "\n";

// Test des URLs corrigées
echo "🌐 TEST DES URLS CORRIGÉES\n";
echo "---------------------------\n";

$testCountries = ['Tunisie', 'Algérie', 'Maroc', 'Portugal', 'France'];

foreach ($testCountries as $country) {
    if (isset($countryToISO[$country])) {
        $iso = $countryToISO[$country];
        $flagUrl = "https://flagcdn.com/w80/{$iso}.png";
        
        $ch = curl_init($flagUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $status = $httpCode == 200 ? '✅' : '❌';
        echo "{$status} {$country} ({$iso}) : HTTP {$httpCode} - {$flagUrl}\n";
    } else {
        echo "⚠️ {$country} : Code ISO non trouvé\n";
    }
}

echo "\n";

// Créer un composant helper pour les drapeaux
echo "🔧 CRÉATION D'UN COMPOSANT HELPER POUR LES DRAPEAUX\n";
echo "--------------------------------------------------\n";

$flagHelperContent = '<?php

/**
 * Helper pour convertir les noms de pays en codes ISO pour les drapeaux
 */

function getCountryFlagCode($countryName) {
    $countryToISO = [
        "Tunisie" => "tn",
        "Algérie" => "dz", 
        "Maroc" => "ma",
        "Portugal" => "pt",
        "France" => "fr",
        "Norway" => "no",
        "Côte d\'Ivoire" => "ci",
        "Mali" => "ml",
        "Sénégal" => "sn",
        "Cameroun" => "cm",
        "Nigeria" => "ng",
        "Égypte" => "eg",
        "Ghana" => "gh",
        "Kenya" => "ke",
        "Afrique du Sud" => "za",
        "Brasil" => "br",
        "Argentina" => "ar",
        "Uruguay" => "uy",
        "Chile" => "cl",
        "Colombia" => "co",
        "Mexico" => "mx",
        "USA" => "us",
        "Canada" => "ca",
        "England" => "gb-eng",
        "Spain" => "es",
        "Germany" => "de",
        "Italy" => "it",
        "Netherlands" => "nl",
        "Belgium" => "be",
        "Switzerland" => "ch",
        "Austria" => "at",
        "Poland" => "pl",
        "Czech Republic" => "cz",
        "Hungary" => "hu",
        "Romania" => "ro",
        "Bulgaria" => "bg",
        "Greece" => "gr",
        "Turkey" => "tr",
        "Russia" => "ru",
        "Ukraine" => "ua",
        "Belarus" => "by",
        "Lithuania" => "lt",
        "Latvia" => "lv",
        "Estonia" => "ee",
        "Finland" => "fi",
        "Sweden" => "se",
        "Denmark" => "dk",
        "Iceland" => "is",
        "Ireland" => "ie",
        "Scotland" => "gb-sct",
        "Wales" => "gb-wls",
        "Northern Ireland" => "gb-nir"
    ];
    
    return $countryToISO[$countryName] ?? "un";
}

// Exemple d\'utilisation
// $flagCode = getCountryFlagCode("Tunisie"); // Retourne "tn"
// $flagUrl = "https://flagcdn.com/w80/{$flagCode}.png";
';

// Créer le fichier helper
$helperFile = 'app/Helpers/FlagHelper.php';
$helperDir = dirname($helperFile);

if (!is_dir($helperDir)) {
    mkdir($helperDir, 0755, true);
}

if (file_put_contents($helperFile, $flagHelperContent)) {
    echo "✅ Helper créé : {$helperFile}\n";
} else {
    echo "❌ Erreur lors de la création du helper\n";
}

echo "\n";

// Instructions pour utiliser le helper
echo "📋 INSTRUCTIONS D'UTILISATION\n";
echo "-----------------------------\n";

echo "1. Le helper est créé dans : {$helperFile}\n";
echo "2. Dans vos vues Blade, utilisez :\n";
echo "   @php\n";
echo "       function getCountryFlagCode(\$countryName) {\n";
echo "           // Copier le contenu de la fonction depuis le helper\n";
echo "       }\n";
echo "   @endphp\n";
echo "\n";
echo "3. Puis dans le HTML :\n";
echo "   <img src=\"https://flagcdn.com/w80/{{ getCountryFlagCode(\$player->nationality) }}.png\"\n";
echo "        alt=\"Drapeau {{ \$player->nationality }}\"\n";
echo "        class=\"w-20 h-16 object-cover rounded shadow-sm mb-2\"\n";
echo "        onerror=\"this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';\">\n";

echo "\n🎉 CORRECTION DES URLS DES DRAPEAUX TERMINÉE !\n";
echo "Utilisez maintenant les codes ISO pour des URLs de drapeaux fonctionnelles.\n";







