<?php
echo "🔄 **Réécriture complètement de la section vocale**\n";
echo "🎯 **Objectif** : Code propre et fonctionnel sans duplications\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Supprimer TOUT le code JavaScript existant entre les balises script
$scriptStart = strpos($content, '<script>');
$scriptEnd = strrpos($content, '</script>');

if ($scriptStart !== false && $scriptEnd !== false) {
    $beforeScript = substr($content, 0, $scriptStart);
    $afterScript = substr($content, $scriptEnd + 8);
    
    // Nouveau code JavaScript propre
    $newJavaScript = '<script>
// Voice Input Section - UPDATED 2025-08-20
let recognition = null;
let isListening = false;

function initSpeechRecognition() {
    if (!("webkitSpeechRecognition" in window)) {
        alert("La reconnaissance vocale n\'est pas supportée par votre navigateur.");
        return;
    }
    
    recognition = new webkitSpeechRecognition();
    recognition.continuous = true;           // Mode continu activé
    recognition.interimResults = true;      // Résultats intermédiaires
    recognition.lang = "fr-FR";             // Langue française
    
    recognition.onstart = function() {
        console.log("🎤 Reconnaissance vocale démarrée");
        isListening = true;
        document.getElementById("voice-status").textContent = "🎤 Écoute en cours...";
        document.getElementById("voice-status").className = "text-green-600 font-semibold";
    };
    
    recognition.onend = function() {
        console.log("🎤 Reconnaissance vocale terminée");
        if (isListening) {
            console.log("🔄 Redémarrage automatique de la reconnaissance...");
            setTimeout(() => {
                if (isListening) {
                    recognition.start();
                }
            }, 100);
        }
    };
    
    recognition.onspeechend = function() {
        console.log("🎤 Fin de parole détectée");
        // NE PAS arrêter la reconnaissance ici
    };
    
    recognition.onresult = function(event) {
        let finalTranscript = "";
        let interimTranscript = "";
        
        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            if (event.results[i].isFinal) {
                finalTranscript += transcript;
            } else {
                interimTranscript += transcript;
            }
        }
        
        if (finalTranscript) {
            console.log("🎤 Texte final:", finalTranscript);
            processVoiceInput(finalTranscript);
        }
        
        if (interimTranscript) {
            console.log("🎤 Texte intermédiaire:", interimTranscript);
            document.getElementById("voice-preview").textContent = "Vous avez dit: " + interimTranscript;
        }
    };
    
    recognition.onerror = function(event) {
        console.log("🎤 Erreur de reconnaissance:", event.error);
        if (event.error === "no-speech") {
            console.log("🔄 Tentative de redémarrage après erreur no-speech...");
            setTimeout(() => {
                if (isListening) {
                    recognition.start();
                }
            }, 100);
        } else if (event.error === "network") {
            document.getElementById("voice-status").textContent = "❌ Erreur réseau";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
        }
    };
}

function startVoiceRecognition() {
    console.log("🚀 Démarrage de la reconnaissance vocale...");
    
    if (!recognition) {
        initSpeechRecognition();
    }
    
    if (recognition && !isListening) {
        try {
            recognition.start();
            speakResponse("Assistant vocal PCMA activé. Dites-moi les informations du formulaire.");
        } catch (error) {
            console.error("Erreur lors du démarrage:", error);
            // Réinitialiser et réessayer
            recognition = null;
            initSpeechRecognition();
            recognition.start();
        }
    }
}

function stopVoiceRecognition() {
    console.log("🛑 Arrêt de la reconnaissance vocale...");
    isListening = false;
    if (recognition) {
        recognition.stop();
    }
    document.getElementById("voice-status").textContent = "⏸️ Reconnaissance arrêtée";
    document.getElementById("voice-status").className = "text-yellow-600 font-semibold";
}

function processVoiceInput(text) {
    const lowerText = text.toLowerCase();
    console.log("🎯 Traitement de l\'entrée vocale:", text);
    
    // Instructions de base
    if (lowerText.includes("commencer") || lowerText.includes("démarrer")) {
        speakResponse("Examen PCMA démarré. Dites-moi les informations du joueur.");
        return;
    }
    
    if (lowerText.includes("arrêter") || lowerText.includes("stop")) {
        stopVoiceRecognition();
        return;
    }
    
    // Remplissage automatique des champs
    if (lowerText.includes("nom du joueur") || lowerText.includes("nom joueur")) {
        const playerName = text.split(":")[1] || text.split("est")[1] || text.split("c\'est")[1];
        if (playerName) {
            const nameField = document.querySelector("input[name=\'player_name\']");
            if (nameField) {
                nameField.value = playerName.trim();
                speakResponse("Nom du joueur enregistré: " + playerName.trim());
            }
        }
        return;
    }
    
    if (lowerText.includes("âge") || lowerText.includes("age")) {
        const age = text.match(/\d+/);
        if (age) {
            const ageField = document.querySelector("input[name=\'player_age\']");
            if (ageField) {
                ageField.value = age[0];
                speakResponse("Âge enregistré: " + age[0] + " ans");
            }
        }
        return;
    }
    
    if (lowerText.includes("club") || lowerText.includes("équipe")) {
        const clubName = text.split(":")[1] || text.split("est")[1] || text.split("c\'est")[1];
        if (clubName) {
            const clubField = document.querySelector("select[name=\'club_id\']");
            if (clubField) {
                // Chercher l\'option correspondante
                for (let option of clubField.options) {
                    if (option.text.toLowerCase().includes(clubName.trim().toLowerCase())) {
                        option.selected = true;
                        speakResponse("Club sélectionné: " + option.text);
                        break;
                    }
                }
            }
        }
        return;
    }
    
    // Réponse par défaut
    speakResponse("J\'ai entendu: " + text + ". Continuez à me donner les informations du formulaire.");
}

function speakResponse(text) {
    if ("speechSynthesis" in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = "fr-FR";
        utterance.rate = 0.9;
        speechSynthesis.speak(utterance);
    }
    
    // Afficher dans l\'interface
    document.getElementById("voice-preview").textContent = "Assistant: " + text;
}

// Initialisation au chargement de la page
document.addEventListener("DOMContentLoaded", function() {
    console.log("📄 DOM Content Loaded - JavaScript is running!");
    
    // Initialiser la reconnaissance vocale
    initSpeechRecognition();
    
    // Ajouter les event listeners
    const startBtn = document.getElementById("start-voice-btn");
    const stopBtn = document.getElementById("stop-voice-btn");
    
    if (startBtn) {
        startBtn.addEventListener("click", function() {
            console.log("Bouton de démarrage cliqué");
            startVoiceRecognition();
        });
    }
    
    if (stopBtn) {
        stopBtn.addEventListener("click", function() {
            stopVoiceRecognition();
        });
    }
});
</script>';

    $content = $beforeScript . $newJavaScript . $afterScript;
    
    // Sauvegarder
    if (file_put_contents($file, $content)) {
        echo "✅ Section vocale réécrite avec succès\n";
        echo "🔄 Redémarrage du serveur...\n";
        
        exec('pkill -f "php artisan serve"');
        sleep(2);
        exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
        sleep(3);
        
        echo "✅ Serveur redémarré\n";
        echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
    } else {
        echo "❌ Erreur lors de la sauvegarde\n";
    }
} else {
    echo "❌ Balises script non trouvées\n";
}
?>

