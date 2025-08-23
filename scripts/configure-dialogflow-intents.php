<?php
/**
 * Script de configuration des nouveaux intents Dialogflow pour PCMA
 * Intents de confirmation, correction et redémarrage
 */

echo "🤖 Configuration des nouveaux intents Dialogflow pour PCMA\n";
echo "========================================================\n\n";

echo "📋 Instructions pour configurer les nouveaux intents :\n\n";

echo "🎯 1. Intent 'yes_intent' (Confirmation positive)\n";
echo "   - Nom : yes_intent\n";
echo "   - Phrases d'entraînement :\n";
echo "     * oui\n";
echo "     * d'accord\n";
echo "     * c'est correct\n";
echo "     * parfait\n";
echo "     * exactement\n";
echo "     * bien sûr\n";
echo "     * confirmé\n";
echo "   - Action : yes_intent\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 2. Intent 'no_intent' (Confirmation négative)\n";
echo "   - Nom : no_intent\n";
echo "   - Phrases d'entraînement :\n";
echo "     * non\n";
echo "     * pas du tout\n";
echo "     * ce n'est pas correct\n";
echo "     * je veux corriger\n";
echo "     * ce n'est pas ça\n";
echo "     * arrêter\n";
echo "     * annuler\n";
echo "   - Action : no_intent\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 3. Intent 'confirm_submit' (Confirmation de soumission)\n";
echo "   - Nom : confirm_submit\n";
echo "   - Phrases d'entraînement :\n";
echo "     * soumettre le formulaire\n";
echo "     * envoyer les données\n";
echo "     * valider le PCMA\n";
echo "     * terminer le formulaire\n";
echo "     * confirmer l'envoi\n";
echo "     * oui envoyer\n";
echo "     * c'est bon pour moi\n";
echo "   - Action : confirm_submit\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 4. Intent 'cancel_pcma' (Annulation)\n";
echo "   - Nom : cancel_pcma\n";
echo "   - Phrases d'entraînement :\n";
echo "     * annuler le formulaire\n";
echo "     * arrêter le PCMA\n";
echo "     * je ne veux plus continuer\n";
echo "     * arrêter tout\n";
echo "     * abandonner\n";
echo "     * je change d'avis\n";
echo "     * stop\n";
echo "   - Action : cancel_pcma\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 5. Intent 'restart_pcma' (Redémarrage)\n";
echo "   - Nom : restart_pcma\n";
echo "   - Phrases d'entraînement :\n";
echo "     * recommencer\n";
echo "     * reprendre depuis le début\n";
echo "     * tout recommencer\n";
echo "     * repartir à zéro\n";
echo "     * nouveau formulaire\n";
echo "     * recommencer le PCMA\n";
echo "     * effacer tout\n";
echo "   - Action : restart_pcma\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 6. Intent 'correct_field' (Correction de champ)\n";
echo "   - Nom : correct_field\n";
echo "   - Phrases d'entraînement :\n";
echo "     * corriger le nom\n";
echo "     * changer l'âge\n";
echo "     * modifier la position\n";
echo "     * le nom n'est pas bon\n";
echo "     * l'âge est faux\n";
echo "     * la position est incorrecte\n";
echo "     * je me suis trompé sur le nom\n";
echo "     * rectifier l'âge\n";
echo "   - Paramètres :\n";
echo "     * field (Entity : @field)\n";
echo "     * correction_field (Entity : @field)\n";
echo "   - Action : correct_field\n";
echo "   - Fulfillment : ✅ Activé (webhook)\n";
echo "   - Contextes : start_pcma-followup (input)\n\n";

echo "🎯 7. Entity 'field' (Champs à corriger)\n";
echo "   - Nom : field\n";
echo "   - Entrées :\n";
echo "     * nom | name, prénom\n";
echo "     * age | âge, années\n";
echo "     * position | poste, rôle\n";
echo "   - Options avancées : ❌ Non cochées\n\n";

echo "📝 Étapes de configuration :\n";
echo "1. Aller sur https://dialogflow.cloud.google.com/\n";
echo "2. Sélectionner l'agent PCMA-FIT\n";
echo "3. Créer chaque intent avec les paramètres ci-dessus\n";
echo "4. Activer le webhook pour chaque intent\n";
echo "5. Tester avec l'émulateur Dialogflow\n\n";

echo "🔗 Test des nouveaux intents :\n";
echo "php scripts/test-new-intents.php\n\n";

echo "✅ Configuration terminée !\n";
echo "Les nouveaux intents sont maintenant disponibles pour la gestion des confirmations et corrections.\n";
