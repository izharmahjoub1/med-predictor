#!/bin/bash

echo "⏹️  Arrêt des services Google Home de test"
echo "=========================================="
echo ""

# Arrêter le serveur Laravel
echo "🔄 Arrêt du serveur Laravel..."
if [ -f .laravel.pid ]; then
    LARAVEL_PID=$(cat .laravel.pid)
    if kill -0 $LARAVEL_PID 2>/dev/null; then
        kill $LARAVEL_PID
        echo "✅ Serveur Laravel arrêté (PID: $LARAVEL_PID)"
    else
        echo "ℹ️  Serveur Laravel déjà arrêté"
    fi
    rm -f .laravel.pid
else
    echo "ℹ️  Aucun serveur Laravel en cours d'exécution"
fi

# Arrêter ngrok
echo "🔄 Arrêt de ngrok..."
if [ -f .ngrok.pid ]; then
    NGROK_PID=$(cat .ngrok.pid)
    if kill -0 $NGROK_PID 2>/dev/null; then
        kill $NGROK_PID
        echo "✅ ngrok arrêté (PID: $NGROK_PID)"
    else
        echo "ℹ️  ngrok déjà arrêté"
    fi
    rm -f .ngrok.pid
else
    echo "ℹ️  Aucun ngrok en cours d'exécution"
fi

# Arrêter tous les processus liés (sécurité)
echo "🔄 Nettoyage des processus..."
pkill -f "php artisan serve" 2>/dev/null
pkill ngrok 2>/dev/null

echo ""
echo "✅ Tous les services ont été arrêtés"
echo ""

# Nettoyer les fichiers temporaires
if [ -f .env.ngrok ]; then
    echo "🗑️  Suppression du fichier de configuration ngrok"
    rm -f .env.ngrok
fi

echo "🧹 Nettoyage terminé"
echo ""
echo "📋 Pour redémarrer les services :"
echo "./scripts/start-google-home-test.sh"
