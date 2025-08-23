<?php
echo "🔧 **Amélioration de la reconnaissance vocale**\n";
echo "🎯 **Problème** : L'assistant parle mais ne traite pas les commandes\n";

$file = 'resources/views/pcma/create.blade.php';

if (!file_exists($file)) {
    echo "❌ Fichier $file non trouvé\n";
    exit(1);
}

$content = file_get_contents($file);

// Améliorer la fonction processVoiceInput avec plus de logs et de flexibilité
$improvedProcessVoiceInput = '
function processVoiceInput(text) {
    const lowerText = text.toLowerCase();
    console.log("🎯 Traitement de l\'entrée vocale:", text);
    console.log("🎯 Texte en minuscules:", lowerText);
    
    // Instructions de base
    if (lowerText.includes("commencer") || lowerText.includes("démarrer")) {
        console.log("✅ Commande \'commencer\' détectée");
        speakResponse("Examen PCMA démarré. Dites-moi les informations du joueur.");
        return;
    }
    
    if (lowerText.includes("arrêter") || lowerText.includes("stop")) {
        console.log("✅ Commande \'arrêter\' détectée");
        stopVoiceRecognition();
        return;
    }
    
    // Remplissage automatique des champs - Version améliorée
    if (lowerText.includes("nom") && (lowerText.includes("joueur") || lowerText.includes("s\'appelle") || lowerText.includes("il s\'appelle"))) {
        console.log("✅ Commande \'nom du joueur\' détectée");
        
        // Extraire le nom de différentes façons
        let playerName = "";
        
        if (text.includes(":")) {
            playerName = text.split(":")[1];
        } else if (text.includes("est")) {
            playerName = text.split("est")[1];
        } else if (text.includes("c\'est")) {
            playerName = text.split("c\'est")[1];
        } else if (text.includes("s\'appelle")) {
            playerName = text.split("s\'appelle")[1];
        } else if (text.includes("il s\'appelle")) {
            playerName = text.split("il s\'appelle")[1];
        } else {
            // Essayer de trouver le nom après "nom du joueur"
            const nomIndex = lowerText.indexOf("nom du joueur");
            if (nomIndex !== -1) {
                playerName = text.substring(nomIndex + "nom du joueur".length);
            }
        }
        
        if (playerName && playerName.trim()) {
            const cleanName = playerName.trim();
            console.log("🎯 Nom extrait:", cleanName);
            
            // Essayer de trouver le champ nom dans différents formats
            const nameField = document.querySelector("input[name=\'player_name\']") || 
                             document.querySelector("input[name=\'name\']") ||
                             document.querySelector("input[placeholder*=\'nom\']") ||
                             document.querySelector("input[placeholder*=\'Nom\']");
            
            if (nameField) {
                nameField.value = cleanName;
                console.log("✅ Nom enregistré dans le champ:", cleanName);
                speakResponse("Nom du joueur enregistré: " + cleanName);
            } else {
                console.log("⚠️ Champ nom non trouvé, affichage dans l\'interface");
                speakResponse("Nom du joueur reçu: " + cleanName + ". Champ non trouvé dans le formulaire.");
            }
        } else {
            console.log("⚠️ Nom non extrait du texte");
            speakResponse("Je n\'ai pas pu extraire le nom du joueur. Pouvez-vous répéter ?");
        }
        return;
    }
    
    if (lowerText.includes("âge") || lowerText.includes("age")) {
        console.log("✅ Commande \'âge\' détectée");
        const age = text.match(/\d+/);
        if (age) {
            console.log("🎯 Âge extrait:", age[0]);
            
            const ageField = document.querySelector("input[name=\'player_age\']") ||
                            document.querySelector("input[name=\'age\']") ||
                            document.querySelector("input[placeholder*=\'âge\']") ||
                            document.querySelector("input[placeholder*=\'Age\']");
            
            if (ageField) {
                ageField.value = age[0];
                console.log("✅ Âge enregistré dans le champ:", age[0]);
                speakResponse("Âge enregistré: " + age[0] + " ans");
            } else {
                console.log("⚠️ Champ âge non trouvé");
                speakResponse("Âge reçu: " + age[0] + " ans. Champ non trouvé dans le formulaire.");
            }
        } else {
            console.log("⚠️ Âge non extrait du texte");
            speakResponse("Je n\'ai pas pu extraire l\'âge. Pouvez-vous répéter ?");
        }
        return;
    }
    
    if (lowerText.includes("club") || lowerText.includes("équipe")) {
        console.log("✅ Commande \'club\' détectée");
        
        let clubName = "";
        if (text.includes(":")) {
            clubName = text.split(":")[1];
        } else if (text.includes("est")) {
            clubName = text.split("est")[1];
        } else if (text.includes("c\'est")) {
            clubName = text.split("c\'est")[1];
        }
        
        if (clubName && clubName.trim()) {
            const cleanClub = clubName.trim();
            console.log("🎯 Club extrait:", cleanClub);
            
            const clubField = document.querySelector("select[name=\'club_id\']") ||
                             document.querySelector("select[name=\'club\']") ||
                             document.querySelector("input[name=\'club\']");
            
            if (clubField) {
                if (clubField.tagName === "SELECT") {
                    // Chercher l\'option correspondante
                    let found = false;
                    for (let option of clubField.options) {
                        if (option.text.toLowerCase().includes(cleanClub.toLowerCase())) {
                            option.selected = true;
                            console.log("✅ Club sélectionné:", option.text);
                            speakResponse("Club sélectionné: " + option.text);
                            found = true;
                            break;
                        }
                    }
                    if (!found) {
                        console.log("⚠️ Option club non trouvée");
                        speakResponse("Club reçu: " + cleanClub + ". Option non trouvée dans la liste.");
                    }
                } else {
                    clubField.value = cleanClub;
                    console.log("✅ Club enregistré:", cleanClub);
                    speakResponse("Club enregistré: " + cleanClub);
                }
            } else {
                console.log("⚠️ Champ club non trouvé");
                speakResponse("Club reçu: " + cleanClub + ". Champ non trouvé dans le formulaire.");
            }
        } else {
            console.log("⚠️ Club non extrait du texte");
            speakResponse("Je n\'ai pas pu extraire le nom du club. Pouvez-vous répéter ?");
        }
        return;
    }
    
    // Réponse par défaut avec plus de détails
    console.log("⚠️ Aucune commande spécifique détectée, réponse par défaut");
    speakResponse("J\'ai entendu: " + text + ". Continuez à me donner les informations du formulaire. Vous pouvez dire \'Nom du joueur: [nom]\', \'Âge: [nombre] ans\', ou \'Club: [nom du club]\'.");
}';

// Remplacer la fonction existante
$content = str_replace('function processVoiceInput(text) {', 'function processVoiceInput(text) {', $content);
$content = preg_replace('/function processVoiceInput\(text\) \{.*?\}/s', $improvedProcessVoiceInput, $content);

// Améliorer la fonction speakResponse pour plus de logs
$improvedSpeakResponse = '
function speakResponse(text) {
    console.log("🗣️ Réponse vocale:", text);
    
    if ("speechSynthesis" in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = "fr-FR";
        utterance.rate = 0.9;
        utterance.onstart = () => console.log("🎤 Synthèse vocale démarrée");
        utterance.onend = () => console.log("🎤 Synthèse vocale terminée");
        utterance.onerror = (event) => console.error("❌ Erreur synthèse vocale:", event.error);
        speechSynthesis.speak(utterance);
    } else {
        console.log("⚠️ Synthèse vocale non supportée");
    }
    
    // Afficher dans l\'interface
    const voicePreview = document.getElementById("voice-preview");
    if (voicePreview) {
        voicePreview.textContent = "Assistant: " + text;
        console.log("✅ Réponse affichée dans l\'interface");
    } else {
        console.log("⚠️ Élément voice-preview non trouvé");
    }
}';

// Remplacer la fonction existante
$content = str_replace('function speakResponse(text) {', 'function speakResponse(text) {', $content);
$content = preg_replace('/function speakResponse\(text\) \{.*?\}/s', $improvedSpeakResponse, $content);

// Sauvegarder
if (file_put_contents($file, $content)) {
    echo "✅ Reconnaissance vocale améliorée avec succès\n";
    echo "🔄 Redémarrage du serveur...\n";
    
    exec('pkill -f "php artisan serve"');
    sleep(2);
    exec('php artisan serve --host=0.0.0.0 --port=8080 > /dev/null 2>&1 &');
    sleep(3);
    
    echo "✅ Serveur redémarré\n";
    echo "🎯 Testez maintenant sur http://localhost:8080/test-pcma-simple\n";
    echo "\n📋 **Instructions de test améliorées :**\n";
    echo "1. Dites clairement \'Nom du joueur: Jean Dupont\'\n";
    echo "2. Ou \'Il s\'appelle Jean Dupont\'\n";
    echo "3. Ou \'Le nom du joueur est Jean Dupont\'\n";
    echo "4. Vérifiez la console du navigateur pour les logs\n";
} else {
    echo "❌ Erreur lors de la sauvegarde\n";
}
?>

