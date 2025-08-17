<?php
/**
 * Script pour créer un logo FTF
 */

echo "🏆 CRÉATION DU LOGO FTF\n";
echo "======================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Créer un logo FTF avec UI Avatars (bleu avec FTF)
$ftfLogoUrl = 'https://ui-avatars.com/api/?name=FTF&background=1e40af&color=ffffff&size=128&font-size=0.5&bold=true';
$ftfLogoPath = '/storage/associations/logos/ftf-logo.png';

echo "🏆 CRÉATION DU LOGO FTF\n";
echo "======================\n";

// Vérifier si l'association FTF existe
$stmt = $db->prepare("SELECT id, name FROM associations WHERE name LIKE '%FTF%'");
$stmt->execute();
$ftfAssociation = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ftfAssociation) {
    echo "✅ Association FTF trouvée : {$ftfAssociation['name']} (ID: {$ftfAssociation['id']})\n";
    
    // Mettre à jour avec le logo
    try {
        $updateStmt = $db->prepare("UPDATE associations SET association_logo_url = ?, logo_image = ? WHERE id = ?");
        $result = $updateStmt->execute([$ftfLogoUrl, $ftfLogoPath, $ftfAssociation['id']]);
        
        if ($result) {
            echo "✅ Logo FTF ajouté avec succès !\n";
            echo "   Logo URL : {$ftfLogoUrl}\n";
            echo "   Logo Path : {$ftfLogoPath}\n";
        } else {
            echo "❌ Échec de l'ajout du logo FTF\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'ajout du logo : " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Association FTF non trouvée\n";
    
    // Créer l'association FTF si elle n'existe pas
    try {
        $createStmt = $db->prepare("INSERT INTO associations (name, country, association_logo_url, logo_image, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
        $result = $createStmt->execute(['FTF', 'Tunisie', $ftfLogoUrl, $ftfLogoPath]);
        
        if ($result) {
            $ftfId = $db->lastInsertId();
            echo "✅ Association FTF créée avec succès (ID: {$ftfId})\n";
            echo "   Logo URL : {$ftfLogoUrl}\n";
            echo "   Logo Path : {$ftfLogoPath}\n";
        } else {
            echo "❌ Échec de la création de l'association FTF\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création : " . $e->getMessage() . "\n";
    }
}

echo "\n🧪 TEST DU LOGO FTF\n";
echo "===================\n";

// Vérifier que le logo est accessible
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ftfLogoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Logo FTF accessible (HTTP {$httpCode})\n";
} else {
    echo "❌ Logo FTF non accessible (HTTP {$httpCode})\n";
}

echo "\n🎉 CRÉATION DU LOGO FTF TERMINÉE !\n";
?>
