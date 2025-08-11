#!/bin/bash

echo "🔗 Connexion et configuration de l'instance..."
echo ""

# Informations de l'instance
echo "=== Informations de l'instance ==="
echo "Nom: med-predictor-instance"
echo "Zone: europe-west1-b"
echo "IP externe: 34.38.85.123"
echo ""

# Se connecter à l'instance
echo "=== Connexion à l'instance ==="
echo "Connexion en cours..."
gcloud compute ssh fit@med-predictor-instance --zone=europe-west1-b

echo ""
echo "✅ Connexion établie !"
echo ""
echo "=== Prochaines étapes sur l'instance ==="
echo "1. Exécutez le script de configuration:"
echo "   curl -s https://raw.githubusercontent.com/your-repo/setup-instance.sh | bash"
echo ""
echo "2. Ou installez manuellement:"
echo "   sudo apt-get update"
echo "   sudo apt-get install -y software-properties-common curl wget unzip git"
echo "   sudo add-apt-repository ppa:ondrej/php -y"
echo "   sudo apt-get update"
echo "   sudo apt-get install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl php8.1-cli nginx"
echo ""
echo "3. Installez Composer et Node.js"
echo "4. Configurez Nginx et le service Laravel"
echo ""
echo "=== URL de l'application ==="
echo "http://34.38.85.123" 