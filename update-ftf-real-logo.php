<?php
/**
 * Script pour mettre à jour le logo FTF avec le vrai logo
 */

echo "🏆 MISE À JOUR DU LOGO FTF AVEC LE VRAI LOGO\n";
echo "==============================================\n\n";

// Connexion à la base de données
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données établie\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    exit(1);
}

// Nouveau logo FTF fourni par l'utilisateur
$realFtfLogoUrl = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmVBw2j8ptZ7bVM08T5pnMCF7I9kHbO_9ARg&s';
$realFtfLogoPath = '/storage/associations/logos/ftf-real-logo.png';

echo "🏆 MISE À JOUR DU LOGO FTF\n";
echo "==========================\n";

// Vérifier si l'association FTF existe
$stmt = $db->prepare("SELECT id, name, association_logo_url FROM associations WHERE name LIKE '%FTF%'");
$stmt->execute();
$ftfAssociation = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ftfAssociation) {
    echo "✅ Association FTF trouvée : {$ftfAssociation['name']} (ID: {$ftfAssociation['id']})\n";
    echo "   Ancien logo : {$ftfAssociation['association_logo_url']}\n";
    
    // Mettre à jour avec le nouveau logo
    try {
        $updateStmt = $db->prepare("UPDATE associations SET association_logo_url = ?, logo_image = ? WHERE id = ?");
        $result = $updateStmt->execute([$realFtfLogoUrl, $realFtfLogoPath, $ftfAssociation['id']]);
        
        if ($result) {
            echo "✅ Logo FTF mis à jour avec succès !\n";
            echo "   Nouveau logo URL : {$realFtfLogoUrl}\n";
            echo "   Nouveau logo Path : {$realFtfLogoPath}\n";
        } else {
            echo "❌ Échec de la mise à jour du logo FTF\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de la mise à jour : " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Association FTF non trouvée\n";
}

echo "\n🧪 TEST DU NOUVEAU LOGO FTF\n";
echo "============================\n";

// Vérifier que le nouveau logo est accessible
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $realFtfLogoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Nouveau logo FTF accessible (HTTP {$httpCode})\n";
    echo "   Type de contenu : {$contentType}\n";
} else {
    echo "❌ Nouveau logo FTF non accessible (HTTP {$httpCode})\n";
    echo "   🔄 Utilisation du logo de fallback UI Avatars\n";
    
    // Fallback vers UI Avatars si le vrai logo n'est pas accessible
    $fallbackLogoUrl = 'https://ui-avatars.com/api/?name=FTF&background=1e40af&color=ffffff&size=128&font-size=0.5&bold=true';
    $fallbackLogoPath = '/storage/associations/logos/ftf-fallback.png';
    
    try {
        $updateStmt = $db->prepare("UPDATE associations SET association_logo_url = ?, logo_image = ? WHERE id = ?");
        $result = $updateStmt->execute([$fallbackLogoUrl, $fallbackLogoPath, $ftfAssociation['id']]);
        
        if ($result) {
            echo "✅ Logo de fallback appliqué\n";
        } else {
            echo "❌ Échec de l'application du fallback\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'application du fallback : " . $e->getMessage() . "\n";
    }
}

echo "\n📊 VÉRIFICATION FINALE\n";
echo "======================\n";

// Vérifier l'état final
$stmt = $db->prepare("SELECT name, association_logo_url, logo_image FROM associations WHERE name LIKE '%FTF%'");
$stmt->execute();
$ftfFinal = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ftfFinal) {
    echo "🏆 {$ftfFinal['name']} - État final :\n";
    echo "   Logo URL : {$ftfFinal['association_logo_url']}\n";
    echo "   Logo Image : {$ftfFinal['logo_image']}\n";
} else {
    echo "❌ Association FTF non trouvée dans la vérification finale\n";
}

echo "\n🎉 MISE À JOUR DU LOGO FTF TERMINÉE !\n";
echo "Le logo FTF a été mis à jour avec le vrai logo fourni.\n";
?>







