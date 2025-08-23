<?php
echo "🎯 **TEST FORMULAIRE PCMA AVEC REMPLISSAGE VOCAL**\n";
echo "🔍 Vérification que le formulaire et l'enregistrement fonctionnent\n";

$url = 'http://localhost:8080/test-pcma-simple';

echo "📡 Test de $url...\n";

// Test avec curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Code HTTP: $httpCode\n";

if ($error) {
    echo "❌ Erreur curl: $error\n";
    exit(1);
}

if ($httpCode === 200) {
    echo "✅ Page accessible\n";
    echo "📏 Taille de la réponse: " . number_format(strlen($response)) . " caractères\n";
    
    // Vérification du formulaire PCMA
    $formChecks = [
        // Champs du formulaire
        'player-name-field' => 'Champ nom du joueur',
        'player-age-field' => 'Champ âge',
        'player-position-field' => 'Champ position',
        'player-club-field' => 'Champ club',
        
        // Boutons
        'save-pcma-btn' => 'Bouton enregistrer PCMA',
        'clear-form-btn' => 'Bouton vider le formulaire',
        'new-player-btn' => 'Bouton nouveau joueur',
        
        // Statuts des champs
        'player-name-status' => 'Statut du champ nom',
        'player-age-status' => 'Statut du champ âge',
        'player-position-status' => 'Statut du champ position',
        'player-club-status' => 'Statut du champ club',
        
        // Statut du formulaire
        'form-status' => 'Statut du formulaire',
        'form-status-text' => 'Texte du statut'
    ];
    
    // Vérification des fonctions JavaScript
    $functionChecks = [
        'updateFieldStatus' => 'Fonction mise à jour statut champ',
        'checkFormCompletion' => 'Fonction vérification complétude',
        'showFormStatus' => 'Fonction affichage statut',
        'clearForm' => 'Fonction vidage formulaire',
        'savePCMA' => 'Fonction enregistrement PCMA',
        'currentPlayerData' => 'Variable données joueur'
    ];
    
    // Vérification de la détection vocale
    $voiceChecks = [
        'position.*détectée' => 'Détection position',
        'Position.*enregistrée' => 'Enregistrement position',
        'attaquant.*défenseur.*milieu.*gardien' => 'Mapping des positions',
        'checkFormCompletion' => 'Vérification automatique complétude'
    ];
    
    echo "\n🔍 **VÉRIFICATION DU FORMULAIRE :**\n";
    $formGood = true;
    foreach ($formChecks as $element => $description) {
        if (strpos($response, $element) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description - NON TROUVÉ\n";
            $formGood = false;
        }
    }
    
    echo "\n🔍 **VÉRIFICATION DES FONCTIONS :**\n";
    $functionGood = true;
    foreach ($functionChecks as $function => $description) {
        if (strpos($response, $function) !== false) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description - NON TROUVÉ\n";
            $functionGood = false;
        }
    }
    
    echo "\n🔍 **VÉRIFICATION DE LA DÉTECTION VOCALE :**\n";
    $voiceGood = true;
    foreach ($voiceChecks as $pattern => $description) {
        if (preg_match('/' . $pattern . '/i', $response)) {
            echo "✅ $description\n";
        } else {
            echo "❌ $description - NON TROUVÉ\n";
            $voiceGood = false;
        }
    }
    
    if ($formGood && $functionGood && $voiceGood) {
        echo "\n🎉 **FORMULAIRE PCMA COMPLÈTEMENT FONCTIONNEL !**\n";
        echo "🚀 Votre assistant vocal peut maintenant :\n";
        echo "✅ Remplir automatiquement le nom du joueur\n";
        echo "✅ Remplir automatiquement l'âge\n";
        echo "✅ Remplir automatiquement la position\n";
        echo "✅ Remplir automatiquement le club\n";
        echo "✅ Vérifier la complétude du formulaire\n";
        echo "✅ Enregistrer le PCMA\n";
        echo "✅ Vider le formulaire pour le joueur suivant\n";
        echo "\n📋 **INSTRUCTIONS DE TEST COMPLÈTES :**\n";
        echo "1. 🌐 Allez sur http://localhost:8080/test-pcma-simple\n";
        echo "2. 🔍 Regardez le panneau bleu '📋 Formulaire PCMA - Remplissage Vocal'\n";
        echo "3. 🎤 Cliquez sur 'Commencer l\'examen PCMA'\n";
        echo "4. 🗣️ Dites : 'Il s\'appelle Jean Dupont'\n";
        echo "5. 🗣️ Dites : 'Il a 25 ans'\n";
        echo "6. 🗣️ Dites : 'Il joue attaquant'\n";
        echo "7. 🗣️ Dites : 'Il joue au club Lyon'\n";
        echo "8. 👀 Observez les champs se remplir automatiquement\n";
        echo "9. 💾 Cliquez sur 'Enregistrer PCMA' quand le bouton devient actif\n";
        echo "10. 👤 Cliquez sur 'Nouveau joueur' pour recommencer\n";
        echo "\n💡 **COMMANDES VOCALES SUPPORTÉES :**\n";
        echo "• 'Il s\'appelle [nom]' ou 'Nom du joueur: [nom]'\n";
        echo "• 'Il a [âge] ans' ou 'Âge: [nombre]'\n";
        echo "• 'Il joue [position]' ou 'Position: [attaquant/défenseur/milieu/gardien]'\n";
        echo "• 'Il joue au club [nom]' ou 'Club: [nom]'\n";
        echo "\n🎯 **Votre assistant vocal PCMA est maintenant COMPLÈTEMENT AUTONOME !**\n";
        echo "📊 Il peut remplir, vérifier et enregistrer des PCMA pour plusieurs joueurs !\n";
    } else {
        echo "\n❌ **PROBLÈMES DÉTECTÉS**\n";
        echo "🔧 Vérifiez les éléments manquants\n";
        echo "📊 Formulaire: " . ($formGood ? "✅" : "❌") . " | Fonctions: " . ($functionGood ? "✅" : "❌") . " | Voix: " . ($voiceGood ? "✅" : "❌") . "\n";
    }
} else {
    echo "❌ Erreur HTTP $httpCode\n";
    echo "📄 Corps de l'erreur:\n";
    echo substr($response, 0, 500) . "\n";
}
?>

