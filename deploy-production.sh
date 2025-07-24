#!/bin/bash

# Production Deployment Script for Med Predictor
# This script handles the complete production deployment process

set -e  # Exit on any error

echo "🚀 Starting Med Predictor Production Deployment..."

# Configuration
APP_NAME="med-predictor"
DEPLOY_PATH="/var/www/med-predictor"
BACKUP_PATH="/var/backups/med-predictor"
LOG_FILE="/var/log/med-predictor/deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}" | tee -a "$LOG_FILE"
    exit 1
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}" | tee -a "$LOG_FILE"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root"
fi

# Check if deployment directory exists
if [ ! -d "$DEPLOY_PATH" ]; then
    error "Deployment directory $DEPLOY_PATH does not exist"
fi

# Navigate to deployment directory
cd "$DEPLOY_PATH" || error "Cannot navigate to deployment directory"

log "📁 Current directory: $(pwd)"

# Create backup
log "💾 Creating backup..."
if [ ! -d "$BACKUP_PATH" ]; then
    mkdir -p "$BACKUP_PATH"
fi

BACKUP_FILE="$BACKUP_PATH/backup-$(date +%Y%m%d-%H%M%S).tar.gz"
tar -czf "$BACKUP_FILE" --exclude='.git' --exclude='node_modules' --exclude='storage/logs/*' . || warning "Backup creation failed"

log "✅ Backup created: $BACKUP_FILE"

# Pull latest changes
log "📥 Pulling latest changes from repository..."
git pull origin main || error "Failed to pull latest changes"

# Install/update dependencies
log "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction || error "Composer install failed"

log "📦 Installing Node.js dependencies..."
npm ci --production || error "NPM install failed"

# Build assets
log "🔨 Building production assets..."
npm run build || error "Asset building failed"

# Set proper permissions
log "🔐 Setting proper permissions..."
sudo chown -R www-data:www-data "$DEPLOY_PATH"
sudo chmod -R 755 "$DEPLOY_PATH"
sudo chmod -R 775 "$DEPLOY_PATH/storage"
sudo chmod -R 775 "$DEPLOY_PATH/bootstrap/cache"

# Environment configuration
log "⚙️ Configuring environment..."
if [ ! -f ".env" ]; then
    error "Environment file .env not found. Please create it from .env.example"
fi

# Clear all caches
log "🧹 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
log "🗄️ Running database migrations..."
php artisan migrate --force || error "Database migration failed"

# Optimize for production
log "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
log "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm || warning "PHP-FPM restart failed"
sudo systemctl restart nginx || warning "Nginx restart failed"
sudo systemctl restart redis || warning "Redis restart failed"

# Health check
log "🏥 Performing health check..."
sleep 5
if curl -f http://localhost/health > /dev/null 2>&1; then
    log "✅ Health check passed"
else
    warning "Health check failed - application may not be responding"
fi

# Cleanup old backups (keep last 7 days)
log "🧹 Cleaning up old backups..."
find "$BACKUP_PATH" -name "backup-*.tar.gz" -mtime +7 -delete

# Final status
log "🎉 Deployment completed successfully!"
log "📊 Deployment Summary:"
log "   - Backup: $BACKUP_FILE"
log "   - Deploy Path: $DEPLOY_PATH"
log "   - Log File: $LOG_FILE"

# Optional: Send notification
if command -v curl &> /dev/null; then
    log "📧 Sending deployment notification..."
    # Add your notification webhook here
    # curl -X POST -H "Content-Type: application/json" -d '{"text":"Med Predictor deployment completed successfully"}' YOUR_WEBHOOK_URL
fi

echo ""
echo "✅ Production deployment completed!"
echo "🌐 Application should be available at: https://your-domain.com"
echo "📋 Next steps:"
echo "   1. Verify all features are working"
echo "   2. Check error logs for any issues"
echo "   3. Monitor performance metrics"
echo "   4. Test FIFA Connect and HL7 FHIR integrations" 