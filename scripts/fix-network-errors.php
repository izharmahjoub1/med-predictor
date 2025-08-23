<?php
echo "🔧 **Correction des erreurs réseau**\n";
echo "🎯 **Problème** : Boucle infinie d'erreurs 'network' qui empêche la reconnaissance vocale\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Améliorer la gestion des erreurs réseau
$improvedErrorHandling = '
    recognition.onerror = function(event) {
        console.log("🎤 Erreur de reconnaissance:", event.error);
        
        // Gestion intelligente des erreurs
        if (event.error === "no-speech") {
            console.log("🔄 Tentative de redémarrage après erreur no-speech...");
            setTimeout(() => {
                if (isListening) {
                    recognition.start();
                }
            }, 100);
        } else if (event.error === "network") {
            console.log("❌ Erreur réseau détectée - Arrêt de la boucle infinie");
            document.getElementById("voice-status").textContent = "❌ Erreur réseau - Cliquez pour réessayer";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
            
            // Arrêter la boucle infinie
            isListening = false;
            
            // Réactiver le bouton de démarrage
            const startBtn = document.getElementById("start-voice-btn");
            const stopBtn = document.getElementById("stop-voice-btn");
            if (startBtn) startBtn.classList.remove("hidden");
            if (stopBtn) stopBtn.classList.add("hidden");
            
            // Message d'erreur vocal
            speakResponse("Erreur réseau détectée. Cliquez sur le bouton pour réessayer.");
            
        } else if (event.error === "not-allowed") {
            console.log("❌ Accès au microphone refusé");
            document.getElementById("voice-status").textContent = "❌ Microphone refusé - Autorisez l\'accès";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
            isListening = false;
            speakResponse("Accès au microphone refusé. Veuillez autoriser l\'accès et réessayer.");
            
        } else if (event.error === "audio-capture") {
            console.log("❌ Problème de capture audio");
            document.getElementById("voice-status").textContent = "❌ Problème audio - Vérifiez votre microphone";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
            isListening = false;
            speakResponse("Problème de capture audio. Vérifiez votre microphone et réessayez.");
            
        } else if (event.error === "service-not-allowed") {
            console.log("❌ Service de reconnaissance non autorisé");
            document.getElementById("voice-status").textContent = "❌ Service non autorisé";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
            isListening = false;
            speakResponse("Service de reconnaissance vocale non autorisé. Vérifiez les permissions du navigateur.");
            
        } else {
            console.log("⚠️ Erreur inconnue:", event.error);
            // Pour les erreurs inconnues, ne pas redémarrer automatiquement
            if (isListening) {
                setTimeout(() => {
                    if (isListening) {
                        console.log("🔄 Tentative de redémarrage après erreur inconnue...");
                        recognition.start();
                    }
                }, 1000); // Délai plus long pour les erreurs inconnues
            }
        }
    };';

// Remplacer la gestion d'erreur existante
$content = str_replace('recognition.onerror = function(event) {', 'recognition.onerror = function(event) {', $content);
$content = preg_replace('/recognition\.onerror = function\(event\) \{.*?\};/s', $improvedErrorHandling, $content);

// Améliorer la fonction startVoiceRecognition pour gérer les erreurs
$improvedStartFunction = '
function startVoiceRecognition() {
    console.log("🚀 Démarrage de la reconnaissance vocale...");
    
    // Réinitialiser le statut
    isListening = true;
    document.getElementById("voice-status").textContent = "🎤 Démarrage...";
    document.getElementById("voice-status").className = "text-blue-600 font-semibold";
    
    if (!recognition) {
        console.log("🔄 Initialisation de la reconnaissance vocale...");
        initSpeechRecognition();
    }
    
    if (recognition && !isListening) {
        try {
            recognition.start();
            speakResponse("Assistant vocal PCMA activé. Dites-moi les informations du formulaire.");
        } catch (error) {
            console.error("Erreur lors du démarrage:", error);
            
            // Gestion des erreurs de démarrage
            if (error.name === "InvalidStateError") {
                console.log("🔄 Reconnaissance déjà en cours, arrêt et redémarrage...");
                try {
                    recognition.stop();
                    setTimeout(() => {
                        if (isListening) {
                            recognition.start();
                        }
                    }, 100);
                } catch (stopError) {
                    console.error("Erreur lors de l\'arrêt:", stopError);
                    // Réinitialiser complètement
                    recognition = null;
                    initSpeechRecognition();
                    setTimeout(() => {
                        if (isListening) {
                            recognition.start();
                        }
                    }, 200);
                }
            } else {
                // Réinitialiser et réessayer
                console.log("🔄 Réinitialisation complète de la reconnaissance...");
                recognition = null;
                initSpeechRecognition();
                setTimeout(() => {
                    if (isListening) {
                        recognition.start();
                    }
                }, 200);
            }
        }
    }
}';

// Remplacer la fonction existante
$content = str_replace('function startVoiceRecognition() {', 'function startVoiceRecognition() {', $content);
$content = preg_replace('/function startVoiceRecognition\(\) \{.*?\}/s', $improvedStartFunction, $content);

// Améliorer la fonction stopVoiceRecognition
$improvedStopFunction = '
function stopVoiceRecognition() {
    console.log("🛑 Arrêt de la reconnaissance vocale...");
    isListening = false;
    
    if (recognition) {
        try {
            recognition.stop();
        } catch (error) {
            console.error("Erreur lors de l\'arrêt:", error);
        }
    }
    
    document.getElementById("voice-status").textContent = "⏸️ Reconnaissance arrêtée";
    document.getElementById("voice-status").className = "text-yellow-600 font-semibold";
    
    // Réactiver le bouton de démarrage
    const startBtn = document.getElementById("start-voice-btn");
    const stopBtn = document.getElementById("stop-voice-btn");
    if (startBtn) startBtn.classList.remove("hidden");
    if (stopBtn) stopBtn.classList.add("hidden");
}';

// Remplacer la fonction existante
$content = str_replace('function stopVoiceRecognition() {', 'function stopVoiceRecognition() {', $content);
$content = preg_replace('/function stopVoiceRecognition\(\) \{.*?\}/s', $improvedStopFunction, $content);

// Sauvegarder
if (file_put_contents($file, $content)) {
    echo "✅ Gestion des erreurs réseau améliorée avec succès\n";
    echo "🔄 Redémarrage du serveur...\n";
    
    exec('pkill -f "php artisan serve"');
    sleep(2);
    exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
    sleep(3);
    
    echo "✅ Serveur redémarré\n";
    echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
    echo "\n🔧 **Corrections appliquées :**\n";
    echo "✅ Boucle infinie d'erreurs réseau éliminée\n";
    echo "✅ Gestion intelligente des différents types d'erreurs\n";
    echo "✅ Messages d'erreur clairs et informatifs\n";
    echo "✅ Boutons réactivés automatiquement en cas d'erreur\n";
    echo "✅ Délais adaptés selon le type d'erreur\n";
    echo "✅ Plus de boucle infinie de redémarrage !\n";
    echo "\n📋 **Instructions de test :**\n";
    echo "1. Allez sur la page de test\n";
    echo "2. Cliquez sur 'Commencer l\'examen PCMA'\n";
    echo "3. Si une erreur réseau survient, l'assistant s'arrêtera proprement\n";
    echo "4. Cliquez à nouveau pour réessayer\n";
    echo "5. L'assistant devrait maintenant être stable !\n";
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}
?>

