#!/bin/bash

# Emergency fix for DSS Engineering 500 error
echo "🚨 Emergency 500 Error Fix for DSS Engineering"
echo "=============================================="

cd /var/www/html/dss-web

# 1. Check what's causing the 500 error
echo "🔍 Last 5 Apache errors:"
sudo tail -5 /var/log/apache2/error.log
echo ""

# 2. Check Laravel errors
echo "🔍 Last 5 Laravel errors:"
tail -5 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log errors"
echo ""

# 3. Quick permission fix
echo "🔧 Fixing permissions..."
sudo chown -R www-data:www-data /var/www/html/dss-web/
sudo chmod 755 /var/www/html/dss-web/public/

# 4. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear --quiet
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet

# 5. Fix .htaccess
echo "🔧 Ensuring .htaccess exists..."
if [ ! -f "public/.htaccess" ]; then
    echo "Creating .htaccess..."
    cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
fi

# 6. Test basic Laravel functionality
echo "🧪 Testing Laravel..."
php artisan --version

# 7. Check if we can backup and apply simple config
echo "🔧 Backing up current Apache config..."
sudo cp /etc/apache2/sites-available/dssengineercoltd.co.tz.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf.emergency-backup

# 8. Apply the simplified configuration
if [ -f "dssengineercoltd-fixed-500.conf" ]; then
    echo "📄 Applying emergency Apache configuration..."
    sudo cp dssengineercoltd-fixed-500.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf

    # Test the configuration
    if sudo apache2ctl configtest; then
        echo "✅ Configuration test passed, reloading Apache..."
        sudo systemctl reload apache2
        echo "🎉 Emergency fix applied!"
    else
        echo "❌ Configuration test failed, restoring backup..."
        sudo cp /etc/apache2/sites-available/dssengineercoltd.co.tz.conf.emergency-backup /etc/apache2/sites-available/dssengineercoltd.co.tz.conf
        sudo systemctl reload apache2
    fi
else
    echo "⚠️  Emergency config file not found. Creating minimal config..."

    # Create ultra-minimal working config
    cat > /tmp/emergency.conf << 'EOF'
<VirtualHost *:80>
    ServerName dssengineercoltd.co.tz
    ServerAlias www.dssengineercoltd.co.tz
    Redirect permanent / https://dssengineercoltd.co.tz/
</VirtualHost>

<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerName www.dssengineercoltd.co.tz
    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/dssengineercoltd.co.tz/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/dssengineercoltd.co.tz/privkey.pem
    Redirect permanent / https://dssengineercoltd.co.tz/
</VirtualHost>

<VirtualHost *:443>
    ServerName dssengineercoltd.co.tz
    DocumentRoot /var/www/html/dss-web/public

    <Directory /var/www/html/dss-web/public>
        Options +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/dssengineercoltd.co.tz/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/dssengineercoltd.co.tz/privkey.pem
</VirtualHost>
</IfModule>
EOF

    sudo cp /tmp/emergency.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf

    if sudo apache2ctl configtest; then
        sudo systemctl reload apache2
        echo "🎉 Minimal emergency configuration applied!"
    fi
fi

echo ""
echo "✅ Emergency fix completed!"
echo ""
echo "🧪 Please test:"
echo "   • https://dssengineercoltd.co.tz"
echo "   • https://www.dssengineercoltd.co.tz (should redirect)"
echo ""
echo "🔍 If still having issues, check:"
echo "   sudo tail -f /var/log/apache2/error.log"
