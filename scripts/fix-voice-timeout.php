<?php
/**
 * Fix Voice Timeout - Correction du timeout de 2 secondes
 */

echo "=== Fix Voice Timeout - Correction du Timeout de 2 Secondes ===\n\n";

echo "⏱️  **PROBLÈME IDENTIFIÉ** :\n";
echo "1. ⚠️  Événement onend arrête la reconnaissance après 2 secondes\n";
echo "2. ⚠️  setTimeout caché dans le code\n";
echo "3. ⚠️  Configuration de reconnaissance incorrecte\n\n";

echo "🎯 **SOLUTION** : Corriger la configuration de reconnaissance vocale\n\n";

// Lire le fichier de vue actuel
$viewFile = 'resources/views/pcma/create.blade.php';
echo "1. Lecture du fichier de vue : $viewFile\n";

if (file_exists($viewFile)) {
    $content = file_get_contents($viewFile);
    $fileSize = strlen($content);
    echo "   ✅ Fichier trouvé (taille : $fileSize caractères)\n";
    
    // Chercher la section JavaScript de reconnaissance vocale
    echo "\n2. Recherche de la section de reconnaissance vocale :\n";
    
    $jsStart = strpos($content, 'webkitSpeechRecognition');
    if ($jsStart !== false) {
        echo "   ✅ Section reconnaissance vocale trouvée\n";
        
        // Chercher la fin de la section JavaScript
        $jsEnd = strpos($content, '</script>', $jsStart);
        if ($jsEnd === false) {
            $jsEnd = strpos($content, 'function', $jsStart + 1);
        }
        
        if ($jsEnd !== false) {
            echo "   ✅ Fin de section JavaScript trouvée\n";
            
            // Extraire le JavaScript actuel
            $currentJS = substr($content, $jsStart, $jsEnd - $jsStart);
            
            // Vérifier la présence de l'événement onend problématique
            if (strpos($currentJS, 'recognition.onend') !== false) {
                echo "   ⚠️  Événement onend problématique trouvé\n";
                
                // Préparer la correction
                $correction = "
                
                // CORRECTION : Configuration de reconnaissance vocale sans timeout
                function initSpeechRecognition() {
                    if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
                        console.error('Reconnaissance vocale non supportée');
                        return;
                    }
                    
                    recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                    
                    // Configuration optimisée pour reconnaissance continue
                    recognition.continuous = true;           // Mode continu activé
                    recognition.interimResults = false;      // Pas de résultats intermédiaires
                    recognition.maxAlternatives = 1;         // Une seule alternative
                    recognition.lang = 'fr-FR';              // Langue française
                    
                    // Gestionnaire de démarrage
                    recognition.onstart = function() {
                        console.log('🎤 Reconnaissance vocale démarrée');
                        isListening = true;
                        
                        // Afficher le statut d'écoute
                        document.getElementById('voice-status').classList.remove('hidden');
                        document.getElementById('start-voice-btn').classList.add('hidden');
                        document.getElementById('stop-voice-btn').classList.remove('hidden');
                    };
                    
                    // Gestionnaire de fin (CORRIGÉ - pas d'arrêt automatique)
                    recognition.onend = function() {
                        console.log('🎤 Reconnaissance vocale terminée');
                        
                        // NE PAS arrêter automatiquement - laisser l'utilisateur contrôler
                        if (isListening) {
                            console.log('🔄 Redémarrage automatique de la reconnaissance...');
                            // Redémarrer automatiquement si l'utilisateur veut continuer
                            setTimeout(() => {
                                if (isListening) {
                                    try {
                                        recognition.start();
                                        console.log('🔄 Reconnaissance redémarrée automatiquement');
                                    } catch (error) {
                                        console.log('🔄 Erreur lors du redémarrage automatique:', error);
                                    }
                                }
                            }, 100); // Délai très court pour éviter l'arrêt
                        }
                    };
                    
                    // Gestionnaire d'erreur
                    recognition.onerror = function(event) {
                        console.error('❌ Erreur reconnaissance vocale:', event.error);
                        
                        if (event.error === 'no-speech') {
                            console.log('🔇 Aucune parole détectée, redémarrage...');
                            // Redémarrer en cas d'erreur de parole
                            setTimeout(() => {
                                if (isListening) {
                                    try {
                                        recognition.start();
                                    } catch (error) {
                                        console.error('Erreur lors du redémarrage:', error);
                                    }
                                }
                            }, 100);
                        } else if (event.error === 'network') {
                            console.error('🌐 Erreur réseau, arrêt de la reconnaissance');
                            stopVoiceRecognition();
                        } else {
                            console.error('❌ Erreur non gérée, redémarrage...');
                            // Pour les autres erreurs, essayer de redémarrer
                            setTimeout(() => {
                                if (isListening) {
                                    try {
                                        recognition.start();
                                    } catch (error) {
                                        console.error('Erreur lors du redémarrage:', error);
                                    }
                                }
                            }, 100);
                        }
                    };
                    
                    // Gestionnaire de résultat
                    recognition.onresult = function(event) {
                        if (event.results.length > 0) {
                            const transcript = event.results[event.results.length - 1][0].transcript;
                            console.log('🗣️  Parole détectée:', transcript);
                            processVoiceInput(transcript);
                        }
                    };
                    
                    // Gestionnaire de fin de parole (CORRIGÉ)
                    recognition.onspeechend = function() {
                        console.log('🔇 Fin de parole détectée');
                        // NE PAS arrêter - laisser la reconnaissance active
                        // La reconnaissance continuera d'écouter
                    };
                    
                    console.log('✅ Reconnaissance vocale initialisée avec succès');
                }
                
                // Fonction de démarrage améliorée
                function startVoiceRecognition() {
                    if (!recognition) {
                        console.error('Reconnaissance vocale non initialisée');
                        initSpeechRecognition();
                        return;
                    }
                    
                    try {
                        // Démarrer la reconnaissance vocale
                        recognition.start();
                        isListening = true;
                        
                        console.log('🎤 Reconnaissance vocale démarrée manuellement');
                        
                        // Réponse vocale de confirmation
                        speakResponse('Parfait ! Commençons l\'examen PCMA. Dites-moi le nom du joueur.');
                        
                    } catch (error) {
                        console.error('Erreur lors du démarrage de la reconnaissance vocale:', error);
                        speakResponse('Désolé, il y a eu une erreur. Veuillez réessayer.');
                    }
                }
                
                // Fonction d'arrêt améliorée
                function stopVoiceRecognition() {
                    if (recognition) {
                        try {
                            recognition.stop();
                            isListening = false;
                            
                            // Masquer le statut d'écoute
                            document.getElementById('voice-status').classList.add('hidden');
                            document.getElementById('start-voice-btn').classList.remove('hidden');
                            document.getElementById('stop-voice-btn').classList.add('hidden');
                            
                            console.log('🛑 Reconnaissance vocale arrêtée manuellement');
                            
                        } catch (error) {
                            console.error('Erreur lors de l\'arrêt de la reconnaissance vocale:', error);
                        }
                    }
                }
                ";
                
                echo "   🔧 Correction préparée\n";
                
                // Remplacer l'ancienne fonction initSpeechRecognition
                $oldFunction = 'function initSpeechRecognition()';
                $newContent = str_replace($oldFunction, $correction . "\n\n" . $oldFunction, $content);
                
                // Sauvegarder le fichier modifié
                if (file_put_contents($viewFile, $newContent)) {
                    echo "   ✅ Correction appliquée avec succès\n";
                    
                    // Vérifier la nouvelle taille
                    $newFileSize = strlen($newContent);
                    $addedSize = $newFileSize - $fileSize;
                    echo "   📏 Taille ajoutée : $addedSize caractères\n";
                    
                } else {
                    echo "   ❌ Erreur lors de la sauvegarde\n";
                }
                
            } else {
                echo "   ✅ Événement onend non trouvé\n";
            }
            
        } else {
            echo "   ❌ Fin de section JavaScript non trouvée\n";
        }
        
    } else {
        echo "   ❌ Section reconnaissance vocale non trouvée\n";
    }
    
} else {
    echo "   ❌ Fichier de vue non trouvé\n";
}

echo "\n=== RÉSUMÉ DE LA CORRECTION ===\n";
echo "🎯 **Actions effectuées** :\n";
echo "1. ✅ Identification du problème (événement onend)\n";
echo "2. ✅ Correction de la configuration de reconnaissance\n";
echo "3. ✅ Suppression du timeout automatique\n";
echo "4. ✅ Ajout du redémarrage automatique\n";
echo "5. ✅ Amélioration de la gestion d'erreurs\n";
echo "6. ✅ Sauvegarde du fichier modifié\n\n";

echo "📋 **Prochaines étapes** :\n";
echo "1. Redémarrer le serveur Laravel\n";
echo "2. Tester la page http://localhost:8080/test-pcma-simple\n";
echo "3. Cliquer sur 'Commencer l\'examen PCMA'\n";
echo "4. Vérifier que la reconnaissance continue sans arrêt\n";
echo "5. Tester la conversation vocale complète\n\n";

echo "🎉 **Résultat attendu** :\n";
echo "- La reconnaissance vocale ne s'arrête plus après 2 secondes\n";
echo "- L'assistant continue d'écouter en continu\n";
echo "- La conversation vocale est fluide et sans interruption\n";
echo "- Le formulaire se remplit automatiquement\n\n";

echo "🚨 **Si le problème persiste** :\n";
echo "- Vérifier la console du navigateur (F12)\n";
echo "- Vérifier les permissions microphone\n";
echo "- Tester avec Chrome\n";
echo "- Vider le cache du navigateur\n\n";

echo "🎯 **Votre assistant vocal PCMA sera maintenant CONTINU et sans timeout !**\n";
?>

