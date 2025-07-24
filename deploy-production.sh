#!/bin/bash

# FIT Service Production Deployment Script
# This script deploys the FIT service to a production server

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="fit-service"
DEPLOY_USER="fit"
DEPLOY_PATH="/opt/fit-service"
BACKUP_PATH="/opt/backups"
LOG_PATH="/var/log/fit-service"

echo -e "${GREEN}🚀 Starting FIT Service Production Deployment${NC}"

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   echo -e "${RED}This script should not be run as root${NC}"
   exit 1
fi

# Function to log messages
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[ERROR] $1${NC}"
    exit 1
}

# Check prerequisites
log "Checking prerequisites..."

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    error "Node.js is not installed. Please install Node.js 18+ first."
fi

# Check Node.js version
NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 18 ]; then
    error "Node.js version 18+ is required. Current version: $(node -v)"
fi

# Check if PM2 is installed
if ! command -v pm2 &> /dev/null; then
    log "Installing PM2..."
    npm install -g pm2
fi

# Create necessary directories
log "Creating deployment directories..."
sudo mkdir -p $DEPLOY_PATH
sudo mkdir -p $BACKUP_PATH
sudo mkdir -p $LOG_PATH
sudo chown -R $USER:$USER $DEPLOY_PATH
sudo chown -R $USER:$USER $BACKUP_PATH
sudo chown -R $USER:$USER $LOG_PATH

# Backup existing deployment
if [ -d "$DEPLOY_PATH/app" ]; then
    log "Creating backup of existing deployment..."
    BACKUP_NAME="fit-backup-$(date +%Y%m%d-%H%M%S)"
    cp -r $DEPLOY_PATH $BACKUP_PATH/$BACKUP_NAME
    log "Backup created: $BACKUP_PATH/$BACKUP_NAME"
fi

# Copy application files
log "Copying application files..."
cp -r src/* $DEPLOY_PATH/
cp package.json $DEPLOY_PATH/ 2>/dev/null || true
cp .env.example $DEPLOY_PATH/.env 2>/dev/null || true

# Install dependencies
log "Installing Node.js dependencies..."
cd $DEPLOY_PATH
npm install --production

# Create PM2 ecosystem file
log "Creating PM2 configuration..."
cat > $DEPLOY_PATH/ecosystem.config.js << EOF
module.exports = {
  apps: [{
    name: '$APP_NAME',
    script: 'app.js',
    cwd: '$DEPLOY_PATH',
    instances: 'max',
    exec_mode: 'cluster',
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    },
    env_production: {
      NODE_ENV: 'production',
      PORT: 3000
    },
    log_file: '$LOG_PATH/combined.log',
    out_file: '$LOG_PATH/out.log',
    error_file: '$LOG_PATH/error.log',
    log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
    merge_logs: true,
    max_memory_restart: '1G',
    min_uptime: '10s',
    max_restarts: 10,
    autorestart: true,
    watch: false,
    ignore_watch: ['node_modules', 'logs'],
    source_map_support: false
  }]
};
EOF

# Create systemd service file
log "Creating systemd service..."
sudo tee /etc/systemd/system/fit-service.service > /dev/null << EOF
[Unit]
Description=FIT Service
After=network.target

[Service]
Type=forking
User=$USER
WorkingDirectory=$DEPLOY_PATH
ExecStart=/usr/bin/pm2 start ecosystem.config.js --env production
ExecReload=/usr/bin/pm2 reload ecosystem.config.js --env production
ExecStop=/usr/bin/pm2 stop ecosystem.config.js
Restart=on-failure
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF

# Reload systemd and enable service
sudo systemctl daemon-reload
sudo systemctl enable fit-service

# Create nginx configuration
log "Creating nginx configuration..."
sudo tee /etc/nginx/sites-available/fit-service > /dev/null << EOF
server {
    listen 80;
    server_name your-domain.com;  # Replace with your domain

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_cache_bypass \$http_upgrade;
        proxy_read_timeout 86400;
    }

    # Health check endpoint
    location /health {
        proxy_pass http://localhost:3000/health;
        access_log off;
    }
}
EOF

# Enable nginx site
sudo ln -sf /etc/nginx/sites-available/fit-service /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# Start the service
log "Starting FIT service..."
pm2 start ecosystem.config.js --env production
pm2 save
pm2 startup

log "✅ Deployment completed successfully!"
log "Service is running on: http://localhost:3000"
log "Health check: http://localhost:3000/health"
log "PM2 status: pm2 status"
log "Logs: pm2 logs $APP_NAME"
log "Nginx config: /etc/nginx/sites-available/fit-service"

echo -e "${GREEN}🎉 FIT Service is now deployed and running!${NC}" 