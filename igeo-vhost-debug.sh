#!/bin/bash

# iGEO Deep Diagnostic Script
echo "🔍 iGEO Deep Diagnostic..."
echo "=========================="

if [[ $EUID -ne 0 ]]; then
   echo "❌ Run as root"
   exit 1
fi

echo "--- Apache Sites Enabled ---"
ls -la /etc/apache2/sites-enabled/

echo ""
echo "--- Searching for 'igeo.co.tz' in all configs ---"
grep -r "igeo.co.tz" /etc/apache2/sites-enabled/

echo ""
echo "--- Apache -S Output (VirtualHost precedence) ---"
apache2ctl -S

echo ""
echo "--- Checking DocumentRoot Content ---"
if [ -d "/var/www/html/igeo/public" ]; then
    echo "✅ /var/www/html/igeo/public exists."
    ls -la /var/www/html/igeo/public | head -n 20
else
    echo "❌ /var/www/html/igeo/public NOT FOUND!"
    echo "Searching for public folder..."
    find /var/www -name "index.php" | grep "public/index.php"
fi

echo ""
echo "--- Checking .htaccess Content ---"
if [ -f "/var/www/html/igeo/public/.htaccess" ]; then
    cat /var/www/html/igeo/public/.htaccess
else
    echo "❌ .htaccess NOT FOUND!"
fi

echo ""
echo "--- Checking for Certbot-generated files ---"
ls -la /etc/apache2/sites-available/*-le-ssl.conf 2>/dev/null

echo "=========================="
echo "Please copy the output of this script and paste it here."
