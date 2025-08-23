#!/bin/bash

echo "🌐 Configuration ngrok pour Google Home - FIT Med Assistant"
echo "=========================================================="
echo ""

# Vérifier si ngrok est installé
if ! command -v ngrok &> /dev/null; then
    echo "❌ ngrok n'est pas installé."
    echo ""
    echo "📥 Installation de ngrok :"
    echo "1. Allez sur https://ngrok.com/"
    echo "2. Créez un compte gratuit"
    echo "3. Téléchargez ngrok pour macOS"
    echo "4. Décompressez dans /usr/local/bin/"
    echo "5. Authentifiez avec : ngrok authtoken YOUR_TOKEN"
    echo ""
    echo "Ou installez via Homebrew :"
    echo "brew install ngrok"
    echo ""
    exit 1
fi

echo "✅ ngrok est installé"
echo ""

# Vérifier si le serveur Laravel fonctionne
if ! curl -s "http://localhost:8000/api/google-assistant/health" &> /dev/null; then
    echo "❌ Le serveur Laravel n'est pas accessible sur localhost:8000"
    echo "Démarrez d'abord le serveur avec : php artisan serve --host=0.0.0.0 --port=8000"
    echo ""
    exit 1
fi

echo "✅ Serveur Laravel accessible"
echo ""

# Démarrer ngrok
echo "🚀 Démarrage de ngrok..."
echo "URL publique : https://xxxx-xx-xx-xxx-xx.ngrok.io"
echo ""

# Démarrer ngrok en arrière-plan
ngrok http 8000 > /dev/null 2>&1 &

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
# Configuration ngrok pour Google Home
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

echo "🔍 Monitoring ngrok :"
echo "- Interface web : http://localhost:4040"
echo "- Logs en temps réel : tail -f ~/.ngrok2/ngrok.log"
echo ""

echo "⏹️  Pour arrêter ngrok : pkill ngrok"
echo ""

echo "Configuration terminée ! 🎉"
