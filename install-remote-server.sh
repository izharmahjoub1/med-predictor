#!/bin/bash

echo "🚀 Installation complète de la machine distante Laravel"
echo "====================================================="
echo ""

# Vérifier si gcloud est disponible
if ! command -v gcloud &> /dev/null; then
    echo "❌ gcloud CLI non trouvé"
    echo "💡 Installez Google Cloud SDK ou utilisez Google Cloud Shell"
    echo "🌐 Allez sur: https://console.cloud.google.com/"
    exit 1
fi

echo "✅ gcloud CLI disponible"
echo ""

# Vérifier le projet actuel
echo "=== Projet actuel ==="
PROJECT_ID=$(gcloud config get-value project)
echo "Projet: $PROJECT_ID"
echo ""

# Lister les zones disponibles
echo "=== Zones disponibles ==="
gcloud compute zones list --filter="status=UP" --format="table(name,description)" | head -10
echo ""

# Créer l'instance
echo "=== Création de l'instance ==="
echo "Création de l'instance med-predictor-instance..."

# Essayer Ubuntu 22.04 d'abord
echo "Tentative avec Ubuntu 22.04..."
if gcloud compute instances create med-predictor-instance \
  --zone=europe-west1-b \
  --machine-type=e2-medium \
  --image-family=ubuntu-2204-lts \
  --image-project=ubuntu-os-cloud \
  --tags=http-server,https-server \
  --metadata=startup-script='#! /bin/bash
# Installation automatique des dépendances
apt-get update
apt-get install -y software-properties-common curl wget unzip git

# Installation PHP 8.1
add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl php8.1-cli

# Installation Nginx
apt-get install -y nginx

# Installation Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Installation Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

# Configuration Nginx
cat > /etc/nginx/sites-available/laravel << "EOF"
server {
    listen 80;
    server_name _;
    root /opt/laravel-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
EOF

ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
systemctl restart nginx

# Créer le répertoire de l'application
mkdir -p /opt/laravel-app
chown -R www-data:www-data /opt/laravel-app

# Service Laravel
cat > /etc/systemd/system/laravel-app.service << "EOF"
[Unit]
Description=Laravel Application
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/opt/laravel-app
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable laravel-app

# Permissions
chown -R www-data:www-data /opt/laravel-app
chmod -R 755 /opt/laravel-app

echo "Installation terminée!" > /opt/laravel-app/install-complete.txt'; then
    echo "✅ Instance créée avec Ubuntu 22.04"
else
    echo "⚠️  Ubuntu 22.04 non disponible, tentative avec Ubuntu 18.04..."
    
    if gcloud compute instances create med-predictor-instance \
      --zone=europe-west1-b \
      --machine-type=e2-medium \
      --image-family=ubuntu-1804-lts \
      --image-project=ubuntu-os-cloud \
      --tags=http-server,https-server \
      --metadata=startup-script='#! /bin/bash
# Installation automatique des dépendances
apt-get update
apt-get install -y software-properties-common curl wget unzip git

# Installation PHP 8.1
add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get install -y php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl php8.1-cli

# Installation Nginx
apt-get install -y nginx

# Installation Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Installation Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

# Configuration Nginx
cat > /etc/nginx/sites-available/laravel << "EOF"
server {
    listen 80;
    server_name _;
    root /opt/laravel-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
EOF

ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
systemctl restart nginx

# Créer le répertoire de l'application
mkdir -p /opt/laravel-app
chown -R www-data:www-data /opt/laravel-app

# Service Laravel
cat > /etc/systemd/system/laravel-app.service << "EOF"
[Unit]
Description=Laravel Application
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/opt/laravel-app
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable laravel-app

# Permissions
chown -R www-data:www-data /opt/laravel-app
chmod -R 755 /opt/laravel-app

echo "Installation terminée!" > /opt/laravel-app/install-complete.txt'; then
        echo "✅ Instance créée avec Ubuntu 18.04"
    else
        echo "❌ Échec de la création de l'instance"
        echo "💡 Vérifiez les quotas et permissions Google Cloud"
        exit 1
    fi
fi

echo ""
echo "⏳ Attente de la création de l'instance (2-3 minutes)..."
sleep 30

# Obtenir l'IP externe
echo "=== Récupération de l'IP externe ==="
EXTERNAL_IP=$(gcloud compute instances describe med-predictor-instance --zone=europe-west1-b --format="value(networkInterfaces[0].accessConfigs[0].natIP)")

if [ -z "$EXTERNAL_IP" ]; then
    echo "❌ Impossible de récupérer l'IP externe"
    exit 1
fi

echo "✅ IP externe: $EXTERNAL_IP"
echo ""

# Attendre que l'installation soit terminée
echo "=== Attente de l'installation des dépendances ==="
echo "Cela peut prendre 3-5 minutes..."
for i in {1..30}; do
    if gcloud compute ssh fit@med-predictor-instance --zone=europe-west1-b --command="test -f /opt/laravel-app/install-complete.txt" 2>/dev/null; then
        echo "✅ Installation terminée !"
        break
    fi
    echo "⏳ Installation en cours... ($i/30)"
    sleep 10
done

echo ""
echo "=== Vérification de l'installation ==="
gcloud compute ssh fit@med-predictor-instance --zone=europe-west1-b --command="
echo '=== Services ==='
systemctl status nginx --no-pager -l
echo ''
echo '=== PHP ==='
php --version
echo ''
echo '=== Composer ==='
composer --version
echo ''
echo '=== Node.js ==='
node --version
npm --version
echo ''
echo '=== Répertoires ==='
ls -la /opt/laravel-app/
"

echo ""
echo "🎉 Installation terminée !"
echo ""
echo "=== Informations de connexion ==="
echo "Instance: med-predictor-instance"
echo "Zone: europe-west1-b"
echo "IP externe: $EXTERNAL_IP"
echo ""
echo "=== Connexion SSH ==="
echo "gcloud compute ssh fit@med-predictor-instance --zone=europe-west1-b"
echo ""
echo "=== URL de l'application ==="
echo "http://$EXTERNAL_IP"
echo ""
echo "=== Prochaines étapes ==="
echo "1. Connectez-vous à l'instance"
echo "2. Déployez l'application avec: ./deploy-archive.sh"
echo "3. Configurez le fichier .env"
echo "4. Démarrez le service Laravel" 