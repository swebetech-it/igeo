#!/bin/bash
# Ubuntu Server Setup Script for igeo.co.tz Laravel Application

# Exit on any error
set -e

echo "Starting Ubuntu Server Setup for igeo.co.tz"
echo "=========================================="

# Function to log messages
log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   log "This script should not be run as root. Please run as a regular user with sudo privileges."
   exit 1
fi

# Update system packages
log "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Apache
log "Installing Apache web server..."
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2

# Install PHP 8.3 and extensions
# Install PHP 8.3 and extensions
log "Installing PHP 8.3 and required extensions..."

# Check if Ubuntu version is Plucky (25.04) which doesn't have ondrej PPA yet but has newer PHP in repos
UBUNTU_CODENAME=$(grep "VERSION_CODENAME=" /etc/os-release | cut -d= -f2)

if [ "$UBUNTU_CODENAME" != "plucky" ]; then
    sudo apt install software-properties-common -y
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update
else
    log "Ubuntu Plucky detected. Skipping ondrej/php PPA as it may not be supported yet. Attempting default repositories."
fi

sudo apt install php8.3 php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-json php8.3-ldap php8.3-intl php8.3-readline php8.3-fpm -y || {
    log "PHP 8.3 install failed. Trying default php package..."
    sudo apt install php php-cli php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-bcmath php-json php-ldap php-intl php-readline php-fpm -y
}

# Enable Apache PHP module
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
    log "Detected PHP version: $PHP_VERSION"
    sudo a2enmod php$PHP_VERSION || sudo a2enmod php
else
    log "Could not detect PHP version. Trying common versions..."
    sudo a2enmod php8.4 || sudo a2enmod php8.3 || sudo a2enmod php
fi

# Install MySQL
log "Installing MySQL database server..."
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql

# Install additional tools
log "Installing Git, Composer, and Node.js..."
sudo apt install git curl wget unzip -y

# Install Composer
log "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js (LTS 20)
log "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Create document root directory
log "Creating document root directory..."
sudo mkdir -p /var/www/html/igeo
sudo chown -R $USER:$USER /var/www/html/igeo
sudo chmod -R 755 /var/www/html/igeo

# Enable required Apache modules
log "Enabling required Apache modules..."
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod expires
sudo a2enmod deflate
sudo a2enmod setenvif

# Copy virtual host configuration (assuming it exists in current directory)
if [ -f "apache-vhost-igeo.conf" ]; then
    log "Copying virtual host configuration..."
    sudo cp apache-vhost-igeo.conf /etc/apache2/sites-available/igeo.conf
else
    log "Warning: apache-vhost-igeo.conf not found in current directory."
    log "Please ensure the configuration file exists before proceeding."
fi

# Enable the site
log "Enabling the igeo site..."
sudo a2ensite igeo.conf
sudo systemctl reload apache2

# Set up Laravel directory structure if not already done
log "Setting up Laravel directory structure..."
if [ ! -d "/var/www/html/igeo" ]; then
    log "Creating/Linking Laravel project directory..."
    # Warning: Ideally, users upload code here. We won't run create-project indiscriminately in this script.
    # But for initial setup, we ensure the directory exists and permissions are right.
    mkdir -p /var/www/html/igeo
else
    log "Laravel directory already exists."
fi

# Set proper permissions
log "Setting proper permissions..."
# Assuming 'storage' and 'bootstrap/cache' exist after upload, we pre-emptively try to set permissions or warn.
if [ -d "/var/www/html/igeo/storage" ]; then
    sudo chown -R www-data:www-data /var/www/html/igeo/storage
    sudo chmod -R 775 /var/www/html/igeo/storage
fi
if [ -d "/var/www/html/igeo/bootstrap/cache" ]; then
    sudo chown -R www-data:www-data /var/www/html/igeo/bootstrap/cache
    sudo chmod -R 775 /var/www/html/igeo/bootstrap/cache
fi

# Install SSL certificate if certbot is available
if command -v certbot &> /dev/null; then
    log "Installing Certbot for SSL certificates..."
    sudo apt install certbot python3-certbot-apache -y
    
    read -p "Would you like to install SSL certificate now? (y/n): " install_ssl
    if [[ $install_ssl == "y" || $install_ssl == "Y" ]]; then
        log "Installing SSL certificate with Let's Encrypt..."
        sudo certbot --apache -d igeo.co.tz -d www.igeo.co.tz
    fi
else
    log "Certbot not found. Installing Certbot for SSL certificates..."
    sudo apt install certbot python3-certbot-apache -y
fi

# Restart services
log "Restarting services..."
sudo systemctl restart apache2
sudo systemctl restart php8.3-fpm

# Display completion message
log "Setup completed successfully!"
echo ""
echo "Next steps:"
echo "1. Upload your Laravel application files to /var/www/html/igeo"
echo "2. Configure your .env file with database settings"
echo "3. Run database migrations: php artisan migrate"
echo "4. Generate application key: php artisan key:generate"
echo ""
echo "Your site should now be accessible at http://igeo.co.tz"
echo "After SSL installation, it will be available at https://igeo.co.tz"
