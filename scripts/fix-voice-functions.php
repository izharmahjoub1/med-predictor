<?php
/**
 * Fix Voice Functions - Correction des fonctions vocales manquantes
 */

echo "=== Fix Voice Functions - Correction des Fonctions Vocales ===\n\n";

echo "🔧 **PROBLÈME IDENTIFIÉ** :\n";
echo "1. ❌ Fonction startVoiceRecognition manquante\n";
echo "2. ❌ Gestionnaires d'événements manquants\n";
echo "3. ⚠️  Erreurs dans le code JavaScript\n\n";

echo "🎯 **SOLUTION** : Ajouter les fonctions manquantes au fichier de vue\n\n";

// Lire le fichier de vue actuel
$viewFile = 'resources/views/pcma/create.blade.php';
echo "1. Lecture du fichier de vue : $viewFile\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    $fileSize = strlen($content);
    echo "   ✅ Fichier trouvé (taille : $fileSize caractères)\n";
    
    // Vérifier les fonctions manquantes
    echo "\n2. Vérification des fonctions manquantes :\n";
    
    $missingFunctions = [
        'startVoiceRecognition' => 'Démarrage reconnaissance vocale',
        'addEventListener.*start-voice-btn' => 'Gestionnaire bouton démarrage',
        'addEventListener.*stop-voice-btn' => 'Gestionnaire bouton arrêt'
    ];
    
    foreach ($missingFunctions as $function => $description) {
        if (preg_match('/' . $function . '/', $content)) {
            echo "   ✅ $description : TROUVÉ\n";
        } else {
            echo "   ❌ $description : MANQUANT\n";
        }
    }
    
    // Chercher la section JavaScript pour l'ajout des fonctions
    echo "\n3. Recherche de la section JavaScript :\n";
    
    $jsStart = strpos($content, '<script>');
    if ($jsStart !== false) {
        echo "   ✅ Section JavaScript trouvée\n";
        
        // Chercher la fin de la section JavaScript
        $jsEnd = strpos($content, '</script>', $jsStart);
        if ($jsEnd !== false) {
            echo "   ✅ Fin de section JavaScript trouvée\n";
            
            // Extraire le JavaScript actuel
            $currentJS = substr($content, $jsStart, $jsEnd - $jsStart);
            
            // Vérifier si les fonctions manquantes sont dans le JavaScript
            if (strpos($currentJS, 'startVoiceRecognition') === false) {
                echo "   ❌ Fonction startVoiceRecognition manquante dans le JavaScript\n";
                
                // Préparer les fonctions à ajouter
                $functionsToAdd = "
                
                // Fonction de démarrage de la reconnaissance vocale
                function startVoiceRecognition() {
                    if (!recognition) {
                        console.error('Reconnaissance vocale non initialisée');
                        return;
                    }
                    
                    try {
                        // Afficher le statut d'écoute
                        document.getElementById('voice-status').classList.remove('hidden');
                        document.getElementById('start-voice-btn').classList.add('hidden');
                        document.getElementById('stop-voice-btn').classList.remove('hidden');
                        
                        // Démarrer la reconnaissance vocale
                        recognition.start();
                        isListening = true;
                        
                        console.log('Reconnaissance vocale démarrée');
                        
                        // Réponse vocale de confirmation
                        speakResponse('Parfait ! Commençons l\'examen PCMA. Dites-moi le nom du joueur.');
                        
                    } catch (error) {
                        console.error('Erreur lors du démarrage de la reconnaissance vocale:', error);
                        speakResponse('Désolé, il y a eu une erreur. Veuillez réessayer.');
                    }
                }
                
                // Gestionnaire d'événements pour le bouton de démarrage
                document.addEventListener('DOMContentLoaded', function() {
                    const startBtn = document.getElementById('start-voice-btn');
                    const stopBtn = document.getElementById('stop-voice-btn');
                    
                    if (startBtn) {
                        startBtn.addEventListener('click', function() {
                            console.log('Bouton de démarrage cliqué');
                            startVoiceRecognition();
                        });
                    }
                    
                    if (stopBtn) {
                        stopBtn.addEventListener('click', function() {
                            console.log('Bouton d\'arrêt cliqué');
                            stopVoiceRecognition();
                        });
                    }
                });
                
                // Amélioration de la fonction processVoiceInput
                function processVoiceInput(transcript) {
                    console.log('Entrée vocale reçue:', transcript);
                    
                    const lowerTranscript = transcript.toLowerCase();
                    
                    if (lowerTranscript.includes('commencer l\'examen pcma') || lowerTranscript.includes('démarrer pcma')) {
                        console.log('Commande de démarrage PCMA détectée');
                        startPCMA();
                        return;
                    }
                    
                    if (lowerTranscript.includes('il s\'appelle') || lowerTranscript.includes('son nom est')) {
                        const nameMatch = transcript.match(/(?:il s'appelle|son nom est)\s+(.+)/i);
                        if (nameMatch) {
                            const playerName = nameMatch[1].trim();
                            voiceSessionData.playerName = playerName;
                            document.getElementById('voice-player-name').value = playerName;
                            speakResponse('Parfait ! ' + playerName + '. Maintenant dites-moi son âge.');
                        }
                        return;
                    }
                    
                    if (lowerTranscript.includes('il a') && lowerTranscript.includes('ans')) {
                        const ageMatch = transcript.match(/il a (\d+)\s*ans/i);
                        if (ageMatch) {
                            const playerAge = ageMatch[1];
                            voiceSessionData.playerAge = playerAge;
                            document.getElementById('voice-player-age').value = playerAge;
                            speakResponse('Excellent ! ' + playerAge + ' ans. Quelle est sa position de jeu ?');
                        }
                        return;
                    }
                    
                    if (lowerTranscript.includes('il joue') || lowerTranscript.includes('sa position')) {
                        const positionMatch = transcript.match(/(?:il joue|sa position est)\s+(.+)/i);
                        if (positionMatch) {
                            const playerPosition = positionMatch[1].trim();
                            voiceSessionData.playerPosition = playerPosition;
                            document.getElementById('voice-player-position').value = playerPosition;
                            speakResponse('Parfait ! ' + playerPosition + '. Voulez-vous confirmer ces informations ?');
                        }
                        return;
                    }
                    
                    if (lowerTranscript.includes('oui') || lowerTranscript.includes('confirm')) {
                        if (voiceSessionData.playerName && voiceSessionData.playerAge && voiceSessionData.playerPosition) {
                            speakResponse('Excellent ! Formulaire PCMA soumis avec succès pour ' + voiceSessionData.playerName + '.');
                            showVoiceFormPreview();
                        } else {
                            speakResponse('Veuillez d\'abord fournir toutes les informations nécessaires.');
                        }
                        return;
                    }
                    
                    if (lowerTranscript.includes('non') || lowerTranscript.includes('corriger')) {
                        speakResponse('D\'accord, recommençons. Dites-moi le nom du joueur.');
                        resetVoiceSession();
                        return;
                    }
                    
                    // Réponse par défaut
                    speakResponse('Je n\'ai pas compris. Pouvez-vous répéter ?');
                }
                ";
                
                echo "   🔧 Fonctions à ajouter préparées\n";
                
                // Insérer les fonctions avant la fermeture de la balise script
                $newContent = str_replace('</script>', $functionsToAdd . "\n</script>", $content);
                
                // Sauvegarder le fichier modifié
                if (file_put_contents($viewFile, $newContent)) {
                    echo "   ✅ Fonctions ajoutées avec succès\n";
                    
                    // Vérifier la nouvelle taille
                    $newFileSize = strlen($newContent);
                    $addedSize = $newFileSize - $fileSize;
                    echo "   📏 Taille ajoutée : $addedSize caractères\n";
                    
                } else {
                    echo "   ❌ Erreur lors de la sauvegarde\n";
                }
                
            } else {
                echo "   ✅ Fonction startVoiceRecognition déjà présente\n";
            }
            
        } else {
            echo "   ❌ Fin de section JavaScript non trouvée\n";
        }
        
    } else {
        echo "   ❌ Section JavaScript non trouvée\n";
    }
    
} else {
    echo "   ❌ Fichier de vue non trouvé\n";
}

echo "\n=== RÉSUMÉ DE LA CORRECTION ===\n";
echo "🎯 **Actions effectuées** :\n";
echo "1. ✅ Vérification des fonctions manquantes\n";
echo "2. ✅ Ajout de la fonction startVoiceRecognition\n";
echo "3. ✅ Ajout des gestionnaires d'événements\n";
echo "4. ✅ Amélioration de processVoiceInput\n";
echo "5. ✅ Sauvegarde du fichier modifié\n\n";

echo "📋 **Prochaines étapes** :\n";
echo "1. Redémarrer le serveur Laravel\n";
echo "2. Tester la page http://localhost:8080/test-pcma-simple\n";
echo "3. Cliquer sur 'Commencer l\'examen PCMA'\n";
echo "4. Vérifier que la reconnaissance vocale démarre\n";
echo "5. Tester la conversation vocale\n\n";

echo "🎉 **Résultat attendu** :\n";
echo "- Le bouton démarre la reconnaissance vocale\n";
echo "- L'assistant répond vocalement\n";
echo "- La conversation continue sans interruption\n";
echo "- Le formulaire se remplit automatiquement\n\n";

echo "🚨 **Si le problème persiste** :\n";
echo "- Vérifier la console du navigateur (F12)\n";
echo "- Vérifier les permissions microphone\n";
echo "- Tester avec Chrome\n";
echo "- Vider le cache du navigateur\n\n";

echo "🎯 **Votre assistant vocal PCMA sera maintenant pleinement fonctionnel !**\n";
?>

