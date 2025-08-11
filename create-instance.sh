#!/bin/bash

echo "🚀 Création d'une nouvelle instance Google Cloud..."
echo ""

# Vérifier si l'instance existe déjà
echo "=== Vérification de l'instance existante ==="
existing_instance=$(gcloud compute instances list --filter="name=med-predictor-instance" --format="value(name,zone,status)")

if [ ! -z "$existing_instance" ]; then
    echo "✅ Instance trouvée: $existing_instance"
    echo "L'instance existe déjà, pas besoin de la créer."
    exit 0
fi

echo "❌ Instance non trouvée, création en cours..."
echo ""

# Créer l'instance
echo "=== Création de l'instance ==="
gcloud compute instances create med-predictor-instance \
  --zone=europe-west1-b \
  --machine-type=e2-medium \
  --image-family=ubuntu-2004-lts \
  --image-project=ubuntu-os-cloud \
  --tags=http-server,https-server \
  --metadata=startup-script='#! /bin/bash
# Installation des dépendances
apt-get update
apt-get install -y nginx php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl composer nodejs npm

# Configuration de Nginx
cat > /etc/nginx/sites-available/laravel << EOF
server {
    listen 80;
    server_name _;
    root /opt/laravel-app/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
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
cat > /etc/systemd/system/laravel-app.service << EOF
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

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable laravel-app'

echo ""
echo "✅ Instance créée avec succès !"
echo ""
echo "=== Prochaines étapes ==="
echo "1. Attendez que l'instance soit prête (2-3 minutes)"
echo "2. Connectez-vous à l'instance:"
echo "   gcloud compute ssh fit@med-predictor-instance --zone=europe-west1-b"
echo "3. Déployez l'application avec:"
echo "   ./deploy-archive.sh"
echo ""
echo "=== Informations de l'instance ==="
gcloud compute instances describe med-predictor-instance --zone=europe-west1-b --format="value(networkInterfaces[0].accessConfigs[0].natIP)" 