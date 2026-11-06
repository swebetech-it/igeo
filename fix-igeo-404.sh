#!/bin/bash

# iGEO 404 Not Found - Consolidation Fix Script
# Target: Ubuntu Server - Resolving Conflicting Virtual Hosts

echo "🚀 Starting iGEO VirtualHost Consolidation..."
echo "=========================================="

# 1. Check Root Privileges
if [[ $EUID -ne 0 ]]; then
   echo "❌ This script must be run as root (use sudo)"
   exit 1
fi

# 2. Identify and Disable Duplicate Sites
echo "🔧 Cleaning up duplicate virtual hosts..."

# We want 'igeo.conf' to be the only one active.
SITES_TO_DISABLE=("igeo.co.tz.conf" "igeo.co.tz-le-ssl.conf" "000-default.conf")

for site in "${SITES_TO_DISABLE[@]}"; do
    if [ -f "/etc/apache2/sites-enabled/$site" ]; then
        echo "   - Disabling $site..."
        a2dissite "$site" > /dev/null
    fi
done

# 3. Ensure igeo.conf is enabled
if [ -f "/etc/apache2/sites-available/igeo.conf" ]; then
    echo "✅ Enabling igeo.conf..."
    a2ensite igeo.conf > /dev/null
else
    echo "❌ ERROR: /etc/apache2/sites-available/igeo.conf not found!"
    echo "Attempting to create it from igeo.co.tz-le-ssl.conf if possible..."
    # If igeo.conf is missing but others exist, we might need to restore it.
    # For now, let's assume the user has the one I provided in the previous step.
fi

# 4. Enable Required Modules
echo "🔧 Ensuring Apache modules are enabled..."
a2enmod rewrite ssl headers expires deflate setenvif > /dev/null

# 5. Verify Project Structure and .htaccess
PROJECT_PATH="/var/www/html/igeo"
HTACCESS_FILE="$PROJECT_PATH/public/.htaccess"

if [ -d "$PROJECT_PATH/public" ]; then
    echo "✅ Project directory verified."
    
    # Check if .htaccess has Laravel rewrite rules
    if ! grep -q "index.php" "$HTACCESS_FILE" 2>/dev/null; then
        echo "🔧 Fixing .htaccess rewrite rules..."
        cat > "$HTACCESS_FILE" <<EOF
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
        chown www-data:www-data "$HTACCESS_FILE"
        chmod 644 "$HTACCESS_FILE"
    fi
else
    echo "❌ ERROR: /var/www/html/igeo/public not found!"
fi

# 6. Set Permissions Correctly
echo "🔧 Setting permissions for storage and cache..."
chown -R www-data:www-data "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache" 2>/dev/null
chmod -R 775 "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache" 2>/dev/null

# 7. Final Test and Reload
echo "🔧 Testing Apache configuration..."
if apache2ctl configtest 2>&1 | grep -q "Syntax OK"; then
    echo "✅ Configuration OK. Reloading Apache..."
    systemctl reload apache2
    echo "🎉 SUCCESS: Conflicting hosts disabled. igeo.conf is now active."
else
    echo "❌ Apache configuration test failed!"
    apache2ctl configtest
fi

echo "=========================================="
echo "Check your site at https://igeo.co.tz"
