#!/bin/bash
# Ubuntu Server Setup Script for swebetech.co.tz Laravel Application

# Exit on any error
set -e

echo "Starting Ubuntu Server Setup for swebetech.co.tz"
echo "==============================================="

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

# Install PHP 8.1 and extensions
log "Installing PHP 8.1 and required extensions..."
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-json php8.1-ldap php8.1-intl php8.1-readline php8.1-fpm -y

# Enable Apache PHP module
sudo a2enmod php8.1

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

# Install Node.js
log "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Create document root directory
log "Creating document root directory..."
sudo mkdir -p /var/www/html/swebetech
sudo chown -R $USER:$USER /var/www/html/swebetech
sudo chmod -R 755 /var/www/html/swebetech

# Enable required Apache modules
log "Enabling required Apache modules..."
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod expires
sudo a2enmod deflate
sudo a2enmod setenvif

# Copy virtual host configuration (assuming it exists in current directory)
if [ -f "apache-vhost-swebetech.conf" ]; then
    log "Copying virtual host configuration..."
    sudo cp apache-vhost-swebetech.conf /etc/apache2/sites-available/swebetech.conf
else
    log "Warning: apache-vhost-swebetech.conf not found in current directory."
    log "Please ensure the configuration file exists before proceeding."
fi

# Enable the site
log "Enabling the swebetech site..."
sudo a2ensite swebetech.conf
sudo systemctl reload apache2

# Set up Laravel directory structure if not already done
log "Setting up Laravel directory structure..."
if [ ! -d "/var/www/html/swebetech" ]; then
    log "Creating Laravel project..."
    composer create-project laravel/laravel /var/www/html/swebetech
else
    log "Laravel directory already exists."
fi

# Set proper permissions
log "Setting proper permissions..."
sudo chown -R www-data:www-data /var/www/html/swebetech/storage
sudo chown -R www-data:www-data /var/www/html/swebetech/bootstrap/cache
sudo chmod -R 775 /var/www/html/swebetech/storage
sudo chmod -R 775 /var/www/html/swebetech/bootstrap/cache

# Install SSL certificate if certbot is available
if command -v certbot &> /dev/null; then
    log "Installing Certbot for SSL certificates..."
    sudo apt install certbot python3-certbot-apache -y
    
    read -p "Would you like to install SSL certificate now? (y/n): " install_ssl
    if [[ $install_ssl == "y" || $install_ssl == "Y" ]]; then
        log "Installing SSL certificate with Let's Encrypt..."
        sudo certbot --apache -d swebetech.co.tz -d www.swebetech.co.tz
    fi
else
    log "Certbot not found. Installing Certbot for SSL certificates..."
    sudo apt install certbot python3-certbot-apache -y
fi

# Restart services
log "Restarting services..."
sudo systemctl restart apache2
sudo systemctl restart php8.1-fpm

# Display completion message
log "Setup completed successfully!"
echo ""
echo "Next steps:"
echo "1. Upload your Laravel application files to /var/www/html/swebetech"
echo "2. Configure your .env file with database settings"
echo "3. Run database migrations: php artisan migrate"
echo "4. Generate application key: php artisan key:generate"
echo ""
echo "Your site should now be accessible at http://swebetech.co.tz"
echo "After SSL installation, it will be available at https://swebetech.co.tz"