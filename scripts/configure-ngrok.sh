#!/bin/bash

echo "🔑 Configuration guidée de ngrok"
echo "================================="
echo ""

# Vérifier si ngrok est installé
if ! command -v ngrok &> /dev/null; then
    echo "❌ ngrok n'est pas installé. Lancez d'abord : ./scripts/install-ngrok.sh"
    exit 1
fi

echo "✅ ngrok est installé"
echo ""

# Demander l'authtoken
echo "📝 Configuration de ngrok :"
echo "1. Allez sur https://ngrok.com/"
echo "2. Créez un compte gratuit"
echo "3. Connectez-vous à votre compte"
echo "4. Allez dans 'Your Authtoken'"
echo "5. Copiez le token (format: 2abc123def456ghi789...)"
echo ""

read -p "🔑 Entrez votre authtoken ngrok : " AUTHTOKEN

if [ -z "$AUTHTOKEN" ]; then
    echo "❌ Aucun token fourni. Configuration annulée."
    exit 1
fi

echo ""
echo "🚀 Configuration de ngrok avec votre token..."

# Configurer ngrok
ngrok authtoken "$AUTHTOKEN"

if [ $? -eq 0 ]; then
    echo "✅ ngrok configuré avec succès !"
    echo ""
    echo "🎯 Configuration terminée. Vous pouvez maintenant :"
    echo "1. Démarrer le serveur Laravel : php artisan serve --host=0.0.0.0 --port=8000"
    echo "2. Lancer ngrok : ./scripts/setup-ngrok.sh"
    echo ""
    echo "📱 Ensuite, configurez Google Actions avec l'URL ngrok"
else
    echo "❌ Erreur lors de la configuration de ngrok"
    echo "Vérifiez que votre token est correct"
    exit 1
fi
