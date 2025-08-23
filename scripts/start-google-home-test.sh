#!/bin/bash

echo "🏠 Démarrage Google Home de Test - FIT Med Assistant"
echo "===================================================="
echo ""

# Vérifier si ngrok est configuré
if ! ngrok config check &> /dev/null; then
    echo "❌ ngrok n'est pas configuré."
    echo ""
    echo "🔑 Configurez d'abord ngrok :"
    echo "./scripts/configure-ngrok.sh"
    echo ""
    exit 1
fi

echo "✅ ngrok est configuré"
echo ""

# Vérifier si le serveur Laravel fonctionne
if ! curl -s "http://localhost:8000/api/google-assistant/health" &> /dev/null; then
    echo "❌ Le serveur Laravel n'est pas accessible sur localhost:8000"
    echo ""
    echo "🚀 Démarrage du serveur Laravel..."
    echo "Le serveur va démarrer en arrière-plan sur le port 8000"
    echo ""
    
    # Démarrer Laravel en arrière-plan
    php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &
    LARAVEL_PID=$!
    
    # Attendre que le serveur démarre
    echo "⏳ Attente du démarrage du serveur..."
    sleep 5
    
    # Vérifier que le serveur fonctionne
    if curl -s "http://localhost:8000/api/google-assistant/health" &> /dev/null; then
        echo "✅ Serveur Laravel démarré (PID: $LARAVEL_PID)"
    else
        echo "❌ Erreur lors du démarrage du serveur Laravel"
        exit 1
    fi
else
    echo "✅ Serveur Laravel déjà en cours d'exécution"
fi

echo ""

# Démarrer ngrok
echo "🌐 Démarrage de ngrok..."
echo "URL publique : https://xxxx-xx-xx-xxx-xx.ngrok.io"
echo ""

# Démarrer ngrok en arrière-plan
ngrok http 8000 > /dev/null 2>&1 &
NGROK_PID=$!

# Attendre que ngrok démarre
sleep 3

# Récupérer l'URL publique
NGROK_URL=$(curl -s http://localhost:4040/api/tunnels | grep -o '"public_url":"[^"]*"' | head -1 | cut -d'"' -f4)

if [ -z "$NGROK_URL" ]; then
    echo "❌ Impossible de récupérer l'URL ngrok"
    echo "Vérifiez que ngrok fonctionne sur http://localhost:4040"
    echo ""
    exit 1
fi

echo "🌐 URL publique ngrok : $NGROK_URL"
echo ""

# Créer le fichier de configuration pour Google Actions
cat > .env.ngrok << EOF
# Configuration ngrok pour Google Home de test
NGROK_URL=$NGROK_URL
GOOGLE_ACTIONS_WEBHOOK_URL=$NGROK_URL/api/google-assistant/webhook
EOF

echo "📝 Fichier .env.ngrok créé avec l'URL : $NGROK_URL"
echo ""

echo "🎯 Configuration Google Actions :"
echo "1. Allez sur https://console.actions.google.com/"
echo "2. Créez un nouveau projet : fit-med-assistant"
echo "3. Dans Fulfillment, configurez le webhook :"
echo "   URL: $NGROK_URL/api/google-assistant/webhook"
echo "4. Testez avec le simulateur Google"
echo ""

echo "📱 Configuration Google Home :"
echo "1. Ouvrez l'app Google Home"
echo "2. Allez dans Paramètres > Assistant > Actions sur Google"
echo "3. Activez le mode développeur"
echo "4. Dites : 'OK Google, parle à FIT Med Assistant'"
echo ""

echo "🔍 Monitoring :"
echo "- Interface ngrok : http://localhost:4040"
echo "- Serveur Laravel : http://localhost:8000"
echo "- API Google Assistant : $NGROK_URL/api/google-assistant/health"
echo ""

echo "🧪 Tests disponibles :"
echo "- Test API : php scripts/test-google-actions.php"
echo "- Test fallback : php scripts/test-fallback-simple.php"
echo ""

echo "⏹️  Pour arrêter tous les services :"
echo "pkill -f 'php artisan serve' && pkill ngrok"
echo ""

echo "🚀 Configuration Google Home de test terminée ! 🎉"
echo ""
echo "📋 Prochaines étapes :"
echo "1. Configurez Google Actions avec l'URL webhook ci-dessus"
echo "2. Activez le mode développeur sur Google Home"
echo "3. Testez : 'OK Google, parle à FIT Med Assistant'"
echo ""

# Sauvegarder les PIDs pour arrêt facile
echo $LARAVEL_PID > .laravel.pid
echo $NGROK_PID > .ngrok.pid

echo "📁 Fichiers de configuration créés :"
echo "- .env.ngrok : Configuration ngrok"
echo "- .laravel.pid : PID du serveur Laravel"
echo "- .ngrok.pid : PID de ngrok"
