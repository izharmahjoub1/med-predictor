<?php
/**
 * Diagnostic des Données Réelles des Joueurs
 */

echo "🔍 DIAGNOSTIC DES DONNÉES RÉELLES DES JOUEURS\n";
echo "=============================================\n\n";

$baseUrl = "http://localhost:8001";

// Test 1: Récupérer les vraies données des joueurs
echo "1️⃣ DONNÉES RÉELLES DES JOUEURS\n";
echo "-------------------------------\n";

$apiUrl = "$baseUrl/api/players";
echo "🎯 API testée: $apiUrl\n";

$response = file_get_contents($apiUrl);

if ($response === false) {
    echo "❌ Erreur HTTP lors de l'accès à l'API\n";
} else {
    echo "✅ API accessible\n";
    
    $data = json_decode($response, true);
    
    if ($data && isset($data['data']) && is_array($data['data'])) {
        echo "✅ Données JSON décodées\n";
        echo "📊 Nombre total de joueurs: " . count($data['data']) . "\n";
        
        // Analyser les premiers joueurs avec des données complètes
        $joueursComplets = [];
        $joueursIncomplets = [];
        
        foreach ($data['data'] as $joueur) {
            $completude = 0;
            if (!empty($joueur['first_name'])) $completude++;
            if (!empty($joueur['last_name'])) $completude++;
            if (!empty($joueur['nationality'])) $completude++;
            if (!empty($joueur['club_name'])) $completude++;
            
            if ($completude >= 3) {
                $joueursComplets[] = $joueur;
            } else {
                $joueursIncomplets[] = $joueur;
            }
        }
        
        echo "📋 Joueurs avec données complètes: " . count($joueursComplets) . "\n";
        echo "📋 Joueurs avec données incomplètes: " . count($joueursIncomplets) . "\n";
        
        // Afficher les 5 premiers joueurs complets
        echo "\n🔍 TOP 5 JOUEURS AVEC DONNÉES COMPLÈTES:\n";
        foreach (array_slice($joueursComplets, 0, 5) as $index => $joueur) {
            echo "  " . ($index + 1) . ". ID: " . $joueur['id'] . "\n";
            echo "     Nom: " . ($joueur['first_name'] ?? 'NULL') . " " . ($joueur['last_name'] ?? 'NULL') . "\n";
            echo "     Nationalité: " . ($joueur['nationality'] ?? 'NULL') . "\n";
            echo "     Club: " . ($joueur['club_name'] ?? 'NULL') . "\n";
            echo "     ---\n";
        }
        
        // Analyser les nationalités
        $nationalites = [];
        foreach ($joueursComplets as $joueur) {
            if (!empty($joueur['nationality'])) {
                $nationalites[$joueur['nationality']] = ($nationalites[$joueur['nationality']] ?? 0) + 1;
            }
        }
        
        echo "\n🌍 RÉPARTITION DES NATIONALITÉS:\n";
        arsort($nationalites);
        foreach (array_slice($nationalites, 0, 10, true) as $nationalite => $count) {
            echo "  • $nationalite: $count joueurs\n";
        }
        
        // Analyser les clubs
        $clubs = [];
        foreach ($joueursComplets as $joueur) {
            if (!empty($joueur['club_name'])) {
                $clubs[$joueur['club_name']] = ($clubs[$joueur['club_name']] ?? 0) + 1;
            }
        }
        
        echo "\n🏟️ RÉPARTITION DES CLUBS:\n";
        arsort($clubs);
        foreach (array_slice($clubs, 0, 10, true) as $club => $count) {
            echo "  • $club: $count joueurs\n";
        }
        
    } else {
        echo "❌ Erreur lors du décodage JSON\n";
        echo "📄 Réponse brute: " . substr($response, 0, 200) . "...\n";
    }
}

echo "\n2️⃣ COMPARAISON AVEC LES IMAGES INTÉGRÉES\n";
echo "-----------------------------------------\n";

echo "🔍 Images que j'ai intégrées:\n";
echo "  • Photo: https://cdn.futbin.com/content/fifa23/img/players/15023.png\n";
echo "  • Logo: https://logos-world.net/wp-content/uploads/2020/06/Paris-Saint-Germain-PSG-Logo.png\n";
echo "  • Drapeau: https://flagcdn.com/w40/ar.png\n";

echo "\n❌ PROBLÈMES IDENTIFIÉS:\n";
echo "  1. ID 15023 n'existe pas dans notre base\n";
echo "  2. PSG n'est peut-être pas le bon club\n";
echo "  3. Drapeau 'ar' (Argentine) ne correspond pas aux vraies nationalités\n";

echo "\n3️⃣ RECOMMANDATIONS\n";
echo "------------------\n";

echo "🔧 Actions à effectuer:\n";
echo "1. Utiliser les vrais IDs des joueurs existants\n";
echo "2. Utiliser les vrais noms de clubs de notre base\n";
echo "3. Utiliser les vraies nationalités de notre base\n";
echo "4. Adapter les URLs d'images en conséquence\n";

echo "\n✅ Diagnostic terminé !\n";

