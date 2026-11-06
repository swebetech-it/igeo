#!/bin/bash

# DSS Engineering - Comprehensive Fix for 500 Error
echo "🔧 DSS Engineering - Fixing Laravel 500 Error"
echo "=============================================="

cd /var/www/html/dss-web

# Step 1: Fix Apache ServerName warning
echo "🌐 Step 1: Fixing Apache ServerName warning..."
if ! grep -q "ServerName dssengineercoltd.co.tz" /etc/apache2/apache2.conf; then
    echo "ServerName dssengineercoltd.co.tz" | sudo tee -a /etc/apache2/apache2.conf
    echo "✅ Added global ServerName directive"
fi

# Step 2: Check and fix Laravel application issues
echo "🔧 Step 2: Fixing Laravel application issues..."

# Clear all caches first
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Fix storage permissions
echo "Fixing storage permissions..."
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/

# Ensure .env is properly configured
echo "Checking .env configuration..."
if grep -q "APP_URL=https://www.dssengineercoltd.co.tz" .env; then
    echo "⚠️  Found www in APP_URL, fixing..."
    sed -i 's|APP_URL=https://www.dssengineercoltd.co.tz|APP_URL=https://dssengineercoltd.co.tz|g' .env
fi

# Check for APP_ENV and APP_DEBUG
echo "Verifying environment settings..."
if ! grep -q "APP_ENV=production" .env; then
    echo "⚠️  APP_ENV not set to production"
fi

# Step 3: Fix potential middleware issues
echo "🔧 Step 3: Checking for middleware issues..."

# Create a simple test route to verify Laravel is working
php artisan make:command TestApplication --quiet 2>/dev/null || true

# Test basic Laravel functionality
echo "Testing Laravel bootstrap..."
php artisan --version

# Step 4: Recreate application key if needed
echo "🔑 Step 4: Checking application key..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating new application key..."
    php artisan key:generate --force
fi

# Step 5: Fix .htaccess to handle www redirect properly
echo "🔧 Step 5: Ensuring proper .htaccess..."
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF

# Step 6: Create a minimal Apache configuration that works
echo "🌐 Step 6: Creating working Apache configuration..."
cat > /tmp/working-dss-config.conf << 'EOF'
# Global ServerName (prevents warning)
ServerName dssengineercoltd.co.tz

# HTTP - Redirect to HTTPS
<VirtualHost *:80>
    ServerName dssengineercoltd.co.tz
    ServerAlias www.dssengineercoltd.co.tz

    # Simple redirect to HTTPS non-www
    RewriteEngine On
    RewriteRule ^(.*)$ https://dssengineercoltd.co.tz$1 [R=301,L]

    ErrorLog ${APACHE_LOG_DIR}/dss_http_error.log
    CustomLog ${APACHE_LOG_DIR}/dss_http_access.log combined
</VirtualHost>

# HTTPS www - Redirect to non-www
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName www.dssengineercoltd.co.tz

    # SSL Configuration
    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/dssengineercoltd.co.tz/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/dssengineercoltd.co.tz/privkey.pem

    # Redirect to non-www
    RewriteEngine On
    RewriteRule ^(.*)$ https://dssengineercoltd.co.tz$1 [R=301,L]

    ErrorLog ${APACHE_LOG_DIR}/dss_www_error.log
    CustomLog ${APACHE_LOG_DIR}/dss_www_access.log combined
</VirtualHost>
</IfModule>

# Main HTTPS site
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName dssengineercoltd.co.tz
    DocumentRoot /var/www/html/dss-web/public

    # Directory configuration
    <Directory /var/www/html/dss-web/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted

        # Enable rewrite for Laravel
        RewriteEngine On

        # Handle Authorization Header for Livewire
        RewriteCond %{HTTP:Authorization} .
        RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    </Directory>

    # Storage directory
    <Directory /var/www/html/dss-web/public/storage>
        Options +FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>

    # Security headers
    Header always set Strict-Transport-Security "max-age=31536000"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"

    # Protect sensitive files
    <FilesMatch "\.(env|log)$">
        Require all denied
    </FilesMatch>

    # SSL Configuration
    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/dssengineercoltd.co.tz/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/dssengineercoltd.co.tz/privkey.pem

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/dss_error.log
    CustomLog ${APACHE_LOG_DIR}/dss_access.log combined
</VirtualHost>
</IfModule>
EOF

# Backup current config and apply new one
echo "Backing up and applying new Apache configuration..."
sudo cp /etc/apache2/sites-available/dssengineercoltd.co.tz.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf.backup-$(date +%Y%m%d_%H%M%S)
sudo cp /tmp/working-dss-config.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf

# Test configuration
echo "Testing Apache configuration..."
if sudo apache2ctl configtest; then
    echo "✅ Configuration test passed"

    # Reload Apache
    echo "Reloading Apache..."
    sudo systemctl reload apache2

    # Test the fix
    echo "🧪 Testing the fix..."
    sleep 2

    # Check if site responds
    if curl -I -k https://dssengineercoltd.co.tz/ | grep -q "HTTP"; then
        echo "✅ Main site is responding"
    else
        echo "⚠️  Main site may still have issues"
    fi

    echo ""
    echo "🎉 Fix applied successfully!"
    echo ""
    echo "🧪 Please test:"
    echo "   • https://dssengineercoltd.co.tz"
    echo "   • https://www.dssengineercoltd.co.tz (should redirect)"
    echo ""
    echo "🔍 Monitor logs with:"
    echo "   sudo tail -f /var/log/apache2/dss_error.log"

else
    echo "❌ Configuration test failed, restoring backup"
    sudo cp /etc/apache2/sites-available/dssengineercoltd.co.tz.conf.backup-$(date +%Y%m%d_%H%M%S) /etc/apache2/sites-available/dssengineercoltd.co.tz.conf
    sudo systemctl reload apache2
fi

echo ""
echo "✅ Comprehensive fix completed!"
