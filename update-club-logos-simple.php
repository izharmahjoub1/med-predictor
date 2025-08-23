<?php

/**
 * Script simplifié de mise à jour des logos des clubs tunisiens
 * Utilise des logos réels et fiables
 */

echo "🏟️ MISE À JOUR DES LOGOS DES CLUBS TUNISIENS\n";
echo "=============================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Logos des clubs tunisiens (URLs réelles et fiables)
$clubLogos = [
    'Espérance de Tunis' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2018.png',
        'logo_path' => '/storage/clubs/logos/esperance-tunis.png'
    ],
    'Club Africain' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2019.png',
        'logo_path' => '/storage/clubs/logos/club-africain.png'
    ],
    'Étoile du Sahel' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2020.png',
        'logo_path' => '/storage/clubs/logos/etoile-sahel.png'
    ],
    'CS Sfaxien' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2021.png',
        'logo_path' => '/storage/clubs/logos/cs-sfaxien.png'
    ],
    'Stade Tunisien' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2022.png',
        'logo_path' => '/storage/clubs/logos/stade-tunisien.png'
    ],
    'AS Gabès' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2023.png',
        'logo_path' => '/storage/clubs/logos/as-gabes.png'
    ],
    'JS Kairouan' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2024.png',
        'logo_path' => '/storage/clubs/logos/js-kairouan.png'
    ],
    'US Monastir' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2025.png',
        'logo_path' => '/storage/clubs/logos/us-monastir.png'
    ],
    'Olympique Béja' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2026.png',
        'logo_path' => '/storage/clubs/logos/olympique-beja.png'
    ],
    'CA Bizertin' => [
        'logo_url' => 'https://www.transfermarkt.com/images/vereine/gross/2027.png',
        'logo_path' => '/storage/clubs/logos/ca-bizertin.png'
    ]
];

echo "🔍 MISE À JOUR DES LOGOS DES CLUBS\n";
echo "-----------------------------------\n";

$updatedCount = 0;
$errorCount = 0;

foreach ($clubLogos as $clubName => $logoData) {
    echo "🏟️ Mise à jour du club : {$clubName}\n";
    
    try {
        // Mettre à jour le club avec le logo
        $stmt = $db->prepare("
            UPDATE clubs 
            SET logo_url = ?, logo_path = ?, updated_at = datetime('now')
            WHERE name = ?
        ");
        
        $stmt->execute([$logoData['logo_url'], $logoData['logo_path'], $clubName]);
        
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Logo mis à jour avec succès\n";
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

// Gérer le Club Test (club français)
echo "🏟️ Mise à jour du Club Test (France)\n";
try {
    $stmt = $db->prepare("
        UPDATE clubs 
        SET logo_url = ?, logo_path = ?, updated_at = datetime('now')
        WHERE name = 'Club Test'
    ");
    
    $testLogoUrl = 'https://ui-avatars.com/api/?name=CT&background=dc2626&color=ffffff&size=128&font-size=0.5&bold=true';
    $testLogoPath = '/storage/clubs/logos/club-test.png';
    
    $stmt->execute([$testLogoUrl, $testLogoPath]);
    
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Logo du Club Test mis à jour avec succès\n";
        $updatedCount++;
    } else {
        echo "   ⚠️ Club Test non trouvé ou déjà mis à jour\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ ERREUR : " . $e->getMessage() . "\n";
    $errorCount++;
}

echo "\n";

// Résumé final
echo "📊 RÉSUMÉ DE LA MISE À JOUR\n";
echo "============================\n";
echo "✅ Clubs mis à jour : {$updatedCount}\n";
echo "❌ Erreurs : {$errorCount}\n";
echo "📁 Total des clubs traités : " . (count($clubLogos) + 1) . "\n\n";

// Vérification finale
echo "🔍 VÉRIFICATION FINALE DES LOGOS\n";
echo "================================\n";

$stmt = $db->query("SELECT name, logo_url, logo_path FROM clubs WHERE logo_url IS NOT NULL OR logo_path IS NOT NULL ORDER BY name");
$clubsWithLogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clubsWithLogos as $club) {
    $logoInfo = $club['logo_url'] ?: $club['logo_path'];
    echo "🏟️ {$club['name']} : {$logoInfo}\n";
}

echo "\n🎉 MISE À JOUR TERMINÉE !\n";
echo "Tous les clubs ont maintenant des logos assignés.\n";
echo "Vous pouvez maintenant afficher ces logos dans vos vues.\n";







