<?php
echo "🔧 **Correction des erreurs réseau - Version simplifiée**\n";
echo "🎯 **Problème** : Boucle infinie d'erreurs 'network'\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Remplacer la gestion d'erreur existante par une version simplifiée
$oldErrorHandling = 'recognition.onerror = function(event) {
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
    };';

$newErrorHandling = 'recognition.onerror = function(event) {
        console.log("🎤 Erreur de reconnaissance:", event.error);
        
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
            
            // Message d\'erreur vocal
            speakResponse("Erreur réseau détectée. Cliquez sur le bouton pour réessayer.");
        } else if (event.error === "not-allowed") {
            console.log("❌ Accès au microphone refusé");
            document.getElementById("voice-status").textContent = "❌ Microphone refusé - Autorisez l\'accès";
            document.getElementById("voice-status").className = "text-red-600 font-semibold";
            isListening = false;
            speakResponse("Accès au microphone refusé. Veuillez autoriser l\'accès et réessayer.");
        } else {
            console.log("⚠️ Erreur inconnue:", event.error);
            // Ne pas redémarrer automatiquement pour les erreurs inconnues
            isListening = false;
        }
    };';

// Remplacer la gestion d'erreur
if (strpos($content, $oldErrorHandling) !== false) {
    $content = str_replace($oldErrorHandling, $newErrorHandling, $content);
    echo "✅ Gestion d'erreur remplacée\n";
} else {
    echo "⚠️ Ancienne gestion d'erreur non trouvée, recherche par pattern...\n";
    
    // Recherche par pattern plus flexible
    $pattern = '/recognition\.onerror\s*=\s*function\s*\(event\)\s*\{[^}]*\};/s';
    if (preg_match($pattern, $content, $matches)) {
        $content = preg_replace($pattern, $newErrorHandling, $content);
        echo "✅ Gestion d'erreur remplacée par pattern\n";
    } else {
        echo "❌ Aucune gestion d'erreur trouvée\n";
        exit(1);
    }
}

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
    echo "✅ Gestion intelligente des erreurs\n";
    echo "✅ Messages d'erreur clairs\n";
    echo "✅ Boutons réactivés automatiquement\n";
    echo "✅ Plus de boucle infinie !\n";
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}
?>

