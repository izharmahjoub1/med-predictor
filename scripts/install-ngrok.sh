#!/bin/bash

echo "📥 Installation automatique de ngrok"
echo "===================================="
echo ""

# Vérifier si Homebrew est installé
if ! command -v brew &> /dev/null; then
    echo "❌ Homebrew n'est pas installé."
    echo ""
    echo "📥 Installation de Homebrew :"
    echo "/bin/bash -c \"\$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)\""
    echo ""
    echo "Après installation, relancez ce script."
    exit 1
fi

echo "✅ Homebrew est installé"
echo ""

# Installer ngrok via Homebrew
echo "🚀 Installation de ngrok..."
brew install ngrok

if [ $? -eq 0 ]; then
    echo "✅ ngrok installé avec succès"
    echo ""
    echo "🔑 Configuration de ngrok :"
    echo "1. Allez sur https://ngrok.com/"
    echo "2. Créez un compte gratuit"
    echo "3. Récupérez votre authtoken"
    echo "4. Exécutez : ngrok authtoken YOUR_TOKEN"
    echo ""
    echo "🎯 Après configuration, lancez :"
    echo "./scripts/setup-ngrok.sh"
else
    echo "❌ Erreur lors de l'installation de ngrok"
    echo ""
    echo "📥 Installation manuelle :"
    echo "1. Allez sur https://ngrok.com/"
    echo "2. Téléchargez ngrok pour macOS"
    echo "3. Décompressez dans /usr/local/bin/"
    echo "4. Authentifiez avec : ngrok authtoken YOUR_TOKEN"
fi
