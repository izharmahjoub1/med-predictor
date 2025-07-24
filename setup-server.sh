#!/bin/bash

# Complete Server Setup Script for FIT Service
# Run this on your production server

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[ERROR] $1${NC}"
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING] $1${NC}"
}

echo -e "${GREEN}🚀 Starting Complete Server Setup for FIT Service${NC}"

# Update system
log "Updating system packages..."
apt update && apt upgrade -y

# Install essential packages
log "Installing essential packages..."
apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release

# Install Node.js 18
log "Installing Node.js 18..."
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Verify Node.js installation
NODE_VERSION=$(node -v)
log "Node.js version: $NODE_VERSION"

# Install PM2 globally
log "Installing PM2..."
npm install -g pm2

# Install MongoDB
log "Installing MongoDB..."
wget -qO - https://www.mongodb.org/static/pgp/server-6.0.asc | apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/6.0 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-6.0.list
apt update
apt install -y mongodb-org

# Start and enable MongoDB
systemctl start mongod
systemctl enable mongod

# Install PostgreSQL
log "Installing PostgreSQL..."
apt install -y postgresql postgresql-contrib

# Start and enable PostgreSQL
systemctl start postgresql
systemctl enable postgresql

# Install Redis
log "Installing Redis..."
apt install -y redis-server

# Start and enable Redis
systemctl start redis-server
systemctl enable redis-server

# Install Nginx
log "Installing Nginx..."
apt install -y nginx

# Start and enable Nginx
systemctl start nginx
systemctl enable nginx

# Configure firewall
log "Configuring firewall..."
ufw allow ssh
ufw allow 80
ufw allow 443
ufw allow 3000
ufw --force enable

# Create application user
log "Creating application user..."
useradd -m -s /bin/bash fit
usermod -aG sudo fit

# Create application directories
log "Creating application directories..."
mkdir -p /opt/fit-service
mkdir -p /opt/backups
mkdir -p /var/log/fit-service
chown -R fit:fit /opt/fit-service
chown -R fit:fit /opt/backups
chown -R fit:fit /var/log/fit-service

# Install SSL certificate (Let's Encrypt)
log "Installing Certbot for SSL..."
apt install -y certbot python3-certbot-nginx

# Create database user and database
log "Setting up PostgreSQL database..."
sudo -u postgres psql -c "CREATE USER fit_user WITH PASSWORD 'fit_password';"
sudo -u postgres psql -c "CREATE DATABASE fit_database OWNER fit_user;"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE fit_database TO fit_user;"

# Create MongoDB database
log "Setting up MongoDB database..."
mongo fit_database --eval "db.createUser({user: 'fit_user', pwd: 'fit_password', roles: [{role: 'readWrite', db: 'fit_database'}]})" || true

# Install additional tools
log "Installing additional tools..."
apt install -y htop tree

# Create system monitoring script
log "Creating monitoring script..."
cat > /opt/monitor-fit.sh << 'EOF'
#!/bin/bash
echo "=== FIT Service Status ==="
pm2 status
echo ""
echo "=== System Resources ==="
free -h
echo ""
echo "=== Disk Usage ==="
df -h
echo ""
echo "=== Service Status ==="
systemctl status mongod --no-pager -l
systemctl status postgresql --no-pager -l
systemctl status redis-server --no-pager -l
systemctl status nginx --no-pager -l
EOF

chmod +x /opt/monitor-fit.sh

# Create backup script
log "Creating backup script..."
cat > /opt/backup-fit.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/opt/backups"
DATE=$(date +%Y%m%d-%H%M%S)
BACKUP_NAME="fit-backup-$DATE"

# Create backup directory
mkdir -p "$BACKUP_DIR/$BACKUP_NAME"

# Backup application files
cp -r /opt/fit-service "$BACKUP_DIR/$BACKUP_NAME/"

# Backup databases
pg_dump -h localhost -U fit_user fit_database > "$BACKUP_DIR/$BACKUP_NAME/fit_database.sql"
mongodump --db fit_database --out "$BACKUP_DIR/$BACKUP_NAME/mongodb"

# Create compressed archive
tar -czf "$BACKUP_DIR/$BACKUP_NAME.tar.gz" -C "$BACKUP_DIR" "$BACKUP_NAME"
rm -rf "$BACKUP_DIR/$BACKUP_NAME"

echo "Backup created: $BACKUP_DIR/$BACKUP_NAME.tar.gz"
EOF

chmod +x /opt/backup-fit.sh

# Create log rotation
log "Setting up log rotation..."
cat > /etc/logrotate.d/fit-service << EOF
/var/log/fit-service/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 644 fit fit
    postrotate
        pm2 reloadLogs
    endscript
}
EOF

log "✅ Server setup completed successfully!"
log ""
log "📋 Next steps:"
log "1. Clone your repository: git clone https://github.com/izharmahjoub1/med-predictor.git"
log "2. Run the deployment script: ./deploy-production.sh"
log "3. Configure your environment variables"
log "4. Set up your domain and SSL certificate"
log ""
log "🔧 Useful commands:"
log "- Monitor: /opt/monitor-fit.sh"
log "- Backup: /opt/backup-fit.sh"
log "- PM2 status: pm2 status"
log "- View logs: pm2 logs fit-service"
log ""
log "🌐 Your server is ready for deployment!" 