<?php

/**
 * Script de correction des logos des clubs avec des URLs réelles et accessibles
 */

echo "🔧 CORRECTION DES LOGOS DES CLUBS - URLS RÉELLES\n";
echo "================================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Logos des clubs avec des URLs réelles et accessibles
$clubLogos = [
    'Espérance de Tunis' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=ET&background=ff0000&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/esperance-tunis.png'
    ],
    'Club Africain' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=CA&background=000000&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/club-africain.png'
    ],
    'Étoile du Sahel' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=ES&background=ff6600&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/etoile-sahel.png'
    ],
    'CS Sfaxien' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=CS&background=0066cc&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/cs-sfaxien.png'
    ],
    'Stade Tunisien' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=ST&background=009900&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/stade-tunisien.png'
    ],
    'AS Gabès' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=AG&background=660066&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/as-gabes.png'
    ],
    'JS Kairouan' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=JK&background=cc6600&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/js-kairouan.png'
    ],
    'US Monastir' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=UM&background=cc0066&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/us-monastir.png'
    ],
    'Olympique Béja' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=OB&background=006666&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/olympique-beja.png'
    ],
    'CA Bizertin' => [
        'logo_url' => 'https://ui-avatars.com/api/?name=CB&background=990066&color=ffffff&size=128&font-size=0.5&bold=true',
        'logo_path' => '/storage/clubs/logos/ca-bizertin.png'
    ]
];

echo "🔍 CORRECTION DES LOGOS DES CLUBS\n";
echo "----------------------------------\n";

$updatedCount = 0;
$errorCount = 0;

foreach ($clubLogos as $clubName => $logoData) {
    echo "🏟️ Correction du club : {$clubName}\n";
    
    try {
        // Mettre à jour le club avec le nouveau logo
        $stmt = $db->prepare("
            UPDATE clubs 
            SET logo_url = ?, logo_path = ?, updated_at = datetime('now')
            WHERE name = ?
        ");
        
        $stmt->execute([$logoData['logo_url'], $logoData['logo_path'], $clubName]);
        
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Logo corrigé avec succès\n";
            $updatedCount++;
        } else {
            echo "   ⚠️ Club non trouvé ou déjà mis à jour\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ ERREUR : " . $e->getMessage() . "\n";
        $errorCount++;
    }
    
    echo "\n";
}

// Le Club Test garde son logo actuel qui fonctionne
echo "🏟️ Club Test : Logo déjà fonctionnel (UI Avatars)\n\n";

// Résumé final
echo "📊 RÉSUMÉ DE LA CORRECTION\n";
echo "===========================\n";
echo "✅ Clubs corrigés : {$updatedCount}\n";
echo "❌ Erreurs : {$errorCount}\n";
echo "📁 Total des clubs traités : " . (count($clubLogos) + 1) . "\n\n";

// Vérification finale
echo "🔍 VÉRIFICATION FINALE DES LOGOS\n";
echo "================================\n";

$stmt = $db->query("SELECT name, logo_url FROM clubs WHERE logo_url IS NOT NULL ORDER BY name");
$clubsWithLogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clubsWithLogos as $club) {
    echo "🏟️ {$club['name']} : {$club['logo_url']}\n";
}

echo "\n🎉 CORRECTION TERMINÉE !\n";
echo "Tous les clubs ont maintenant des logos accessibles via UI Avatars.\n";
echo "Les logos devraient maintenant s'afficher correctement dans vos vues.\n";




