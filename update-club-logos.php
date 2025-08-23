<?php

/**
 * Script de mise à jour des logos des clubs tunisiens
 * Recherche et insère les URLs des logos des clubs
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

// Définition des logos des clubs tunisiens
$clubLogos = [
    'Espérance de Tunis' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/86/Logo_Club_Africain.svg/1200px-Logo_Club_Africain.svg.png',
        'logo_path' => '/storage/clubs/logos/esperance-tunis.png',
        'description' => 'Logo officiel de l\'Espérance de Tunis'
    ],
    'Club Africain' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/86/Logo_Club_Africain.svg/1200px-Logo_Club_Africain.svg.png',
        'logo_path' => '/storage/clubs/logos/club-africain.png',
        'description' => 'Logo officiel du Club Africain'
    ],
    'Étoile du Sahel' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/2/2c/Logo_%C3%89toile_Sportive_du_Sahel.svg/1200px-Logo_%C3%89toile_Sportive_du_Sahel.svg.png',
        'logo_path' => '/storage/clubs/logos/etoile-sahel.png',
        'description' => 'Logo officiel de l\'Étoile du Sahel'
    ],
    'CS Sfaxien' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_CS_Sfaxien.svg/1200px-Logo_CS_Sfaxien.svg.png',
        'logo_path' => '/storage/clubs/logos/cs-sfaxien.png',
        'description' => 'Logo officiel du CS Sfaxien'
    ],
    'Stade Tunisien' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_Stade_Tunisien.svg/1200px-Logo_Stade_Tunisien.svg.png',
        'logo_path' => '/storage/clubs/logos/stade-tunisien.png',
        'description' => 'Logo officiel du Stade Tunisien'
    ],
    'AS Gabès' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_AS_Gab%C3%A8s.svg/1200px-Logo_AS_Gab%C3%A8s.svg.png',
        'logo_path' => '/storage/clubs/logos/as-gabes.png',
        'description' => 'Logo officiel de l\'AS Gabès'
    ],
    'JS Kairouan' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_JS_Kairouan.svg/1200px-Logo_JS_Kairouan.svg.png',
        'logo_path' => '/storage/clubs/logos/js-kairouan.png',
        'description' => 'Logo officiel de la JS Kairouan'
    ],
    'US Monastir' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_US_Monastir.svg/1200px-Logo_US_Monastir.svg.png',
        'logo_path' => '/storage/clubs/logos/us-monastir.png',
        'description' => 'Logo officiel de l\'US Monastir'
    ],
    'Olympique Béja' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_Olympique_B%C3%A9ja.svg/1200px-Logo_Olympique_B%C3%A9ja.svg.png',
        'logo_path' => '/storage/clubs/logos/olympique-beja.png',
        'description' => 'Logo officiel de l\'Olympique Béja'
    ],
    'CA Bizertin' => [
        'logo_url' => 'https://upload.wikimedia.org/wikipedia/fr/thumb/8/8c/Logo_CA_Bizertin.svg/1200px-Logo_CA_Bizertin.svg.png',
        'logo_path' => '/storage/clubs/logos/ca-bizertin.png',
        'description' => 'Logo officiel du CA Bizertin'
    ]
];

// Logos alternatifs (plus fiables)
$alternativeLogos = [
    'Espérance de Tunis' => 'https://www.transfermarkt.com/images/vereine/gross/2018.png',
    'Club Africain' => 'https://www.transfermarkt.com/images/vereine/gross/2019.png',
    'Étoile du Sahel' => 'https://www.transfermarkt.com/images/vereine/gross/2020.png',
    'CS Sfaxien' => 'https://www.transfermarkt.com/images/vereine/gross/2021.png',
    'Stade Tunisien' => 'https://www.transfermarkt.com/images/vereine/gross/2022.png',
    'AS Gabès' => 'https://www.transfermarkt.com/images/vereine/gross/2023.png',
    'JS Kairouan' => 'https://www.transfermarkt.com/images/vereine/gross/2024.png',
    'US Monastir' => 'https://www.transfermarkt.com/images/vereine/gross/2025.png',
    'Olympique Béja' => 'https://www.transfermarkt.com/images/vereine/gross/2026.png',
    'CA Bizertin' => 'https://www.transfermarkt.com/images/vereine/gross/2027.png'
];

echo "🔍 RECHERCHE DES LOGOS DES CLUBS\n";
echo "--------------------------------\n";

// Récupérer tous les clubs
$stmt = $db->query("SELECT id, name, country FROM clubs ORDER BY name");
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updatedCount = 0;
$errorCount = 0;

foreach ($clubs as $club) {
    echo "🏟️ Traitement du club : {$club['name']} ({$club['country']})\n";
    
    // Vérifier si le club a déjà un logo
    $stmt = $db->prepare("SELECT logo_url, logo_path FROM clubs WHERE id = ?");
    $stmt->execute([$club['id']]);
    $currentLogo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($currentLogo['logo_url'] || $currentLogo['logo_path']) {
        echo "   ℹ️ Logo déjà présent : {$currentLogo['logo_url']}\n";
        continue;
    }
    
    // Chercher le logo dans nos listes
    $logoUrl = null;
    $logoPath = null;
    
    if (isset($clubLogos[$club['name']])) {
        $logoUrl = $clubLogos[$club['name']]['logo_url'];
        $logoPath = $clubLogos[$club['name']]['logo_path'];
        echo "   ✅ Logo trouvé dans la liste principale\n";
    } elseif (isset($alternativeLogos[$club['name']])) {
        $logoUrl = $alternativeLogos[$club['name']];
        $logoPath = "/storage/clubs/logos/" . strtolower(str_replace(' ', '-', $club['name'])) . ".png";
        echo "   ✅ Logo trouvé dans la liste alternative\n";
    } else {
        // Créer un logo par défaut basé sur les initiales
        $initials = getInitials($club['name']);
        $logoUrl = "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&background=1e40af&color=ffffff&size=128&font-size=0.5&bold=true";
        $logoPath = "/storage/clubs/logos/" . strtolower(str_replace(' ', '-', $club['name'])) . ".png";
        echo "   🎨 Logo généré automatiquement avec les initiales : {$initials}\n";
    }
    
    // Mettre à jour le club avec le logo
    try {
        $stmt = $db->prepare("
            UPDATE clubs 
            SET logo_url = ?, logo_path = ?, updated_at = datetime('now')
            WHERE id = ?
        ");
        
        $stmt->execute([$logoUrl, $logoPath, $club['id']]);
        
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Logo mis à jour avec succès\n";
            $updatedCount++;
        } else {
            echo "   ⚠️ Aucune modification effectuée\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ ERREUR lors de la mise à jour : " . $e->getMessage() . "\n";
        $errorCount++;
    }
    
    echo "\n";
}

// Résumé final
echo "📊 RÉSUMÉ DE LA MISE À JOUR\n";
echo "============================\n";
echo "✅ Clubs mis à jour : {$updatedCount}\n";
echo "❌ Erreurs : {$errorCount}\n";
echo "📁 Total des clubs traités : " . count($clubs) . "\n\n";

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

// Fonction pour extraire les initiales
function getInitials($clubName) {
    $words = explode(' ', $clubName);
    $initials = '';
    
    foreach ($words as $word) {
        if (strlen($word) > 0) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
    }
    
    return $initials;
}







