#!/bin/bash

# iGEO Ultimate Fix Script (v2 - Fixed Syntax)
# Target: Ubuntu Server - Fixing 404 and SSL Mismatch

echo "🚀 Starting iGEO Ultimate Fix..."
echo "================================"

if [[ $EUID -ne 0 ]]; then
   echo "❌ This script must be run as root (sudo)"
   exit 1
fi

PROJECT_ROOT="/var/www/html/igeo"
CONF_FILE="/etc/apache2/sites-available/igeo.conf"

# --- Logic to find SSL Certs BEFORE writing the config ---
CERT_FILE="/etc/letsencrypt/live/igeo.co.tz/fullchain.pem"
KEY_FILE="/etc/letsencrypt/live/igeo.co.tz/privkey.pem"

if [ ! -f "$CERT_FILE" ]; then
    echo "⚠️  WARNING: igeo.co.tz certificate not found in /etc/letsencrypt/live/igeo.co.tz/"
    echo "Checking for greatautoworks cert as backup..."
    if [ -f "/etc/letsencrypt/live/greatautoworks.co.tz/fullchain.pem" ]; then
        CERT_FILE="/etc/letsencrypt/live/greatautoworks.co.tz/fullchain.pem"
        KEY_FILE="/etc/letsencrypt/live/greatautoworks.co.tz/privkey.pem"
        echo "✅ Using greatautoworks cert as temporary placeholder."
    else
        echo "❌ ERROR: No usable SSL certificates found!"
        # We'll still write the config but Apache might fail to start if paths are missing.
    fi
fi

echo "🔧 1. Creating definitive configuration at $CONF_FILE..."

cat > "$CONF_FILE" <<EOF
<VirtualHost *:80>
    ServerName igeo.co.tz
    ServerAlias www.igeo.co.tz
    DocumentRoot $PROJECT_ROOT/public

    RewriteEngine On
    RewriteCond %{HTTP_HOST} ^www\.igeo\.co\.tz$ [NC]
    RewriteRule ^(.*)$ https://igeo.co.tz\$1 [R=301,L]
    RewriteCond %{HTTP_HOST} ^igeo\.co\.tz$ [NC]
    RewriteRule ^(.*)$ https://igeo.co.tz\$1 [R=301,L]

    ErrorLog \${APACHE_LOG_DIR}/igeo_error.log
    CustomLog \${APACHE_LOG_DIR}/igeo_access.log combined
</VirtualHost>

<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName igeo.co.tz
    ServerAlias www.igeo.co.tz
    DocumentRoot $PROJECT_ROOT/public

    <Directory $PROJECT_ROOT/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # SSL Configuration
    SSLEngine on
    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile $CERT_FILE
    SSLCertificateKeyFile $KEY_FILE

    ErrorLog \${APACHE_LOG_DIR}/igeo_error.log
    CustomLog \${APACHE_LOG_DIR}/igeo_access.log combined
</VirtualHost>
</IfModule>
EOF

echo "✅ Configuration file updated."

echo "🔧 2. Disabling duplicate/broken configurations..."
# Use dry-run logic for a2dissite
[ -f "/etc/apache2/sites-enabled/igeo.co.tz.conf" ] && a2dissite igeo.co.tz.conf
[ -f "/etc/apache2/sites-enabled/igeo.co.tz-le-ssl.conf" ] && a2dissite igeo.co.tz-le-ssl.conf
[ -f "/etc/apache2/sites-enabled/default-ssl" ] && a2dissite default-ssl

echo "🔧 3. Enabling correct configuration..."
a2ensite igeo.conf > /dev/null

echo "🔧 4. Ensuring mod_rewrite and ssl are enabled..."
a2enmod rewrite ssl > /dev/null

echo "🔧 5. Fixing permissions..."
chown -R www-data:www-data "$PROJECT_ROOT/storage" "$PROJECT_ROOT/bootstrap/cache" 2>/dev/null
chmod -R 775 "$PROJECT_ROOT/storage" "$PROJECT_ROOT/bootstrap/cache" 2>/dev/null

echo "🔧 6. Testing and Restarting Apache..."
if apache2ctl configtest 2>&1 | grep -q "Syntax OK"; then
    systemctl restart apache2
    echo "🎉 SUCCESS: iGEO is now correctly configured!"
else
    echo "❌ Apache configuration test failed!"
    apache2ctl configtest
fi

echo "================================"
echo "Final Step: If the site loads but has an SSL warning, run:"
echo "sudo certbot --apache -d igeo.co.tz -d www.igeo.co.tz"
