#!/bin/bash

# DSS Engineering 500 Error Diagnostic and Fix Script
echo "🚨 DSS Engineering 500 Error Diagnostic"
echo "======================================="

# Navigate to the web directory
cd /var/www/html/dss-web

echo "📊 System Information:"
echo "Current time: $(date)"
echo "Apache version: $(apache2 -v | head -1)"
echo "PHP version: $(php -v | head -1)"
echo ""

echo "🔍 Step 1: Checking Apache Error Logs (Last 20 lines)"
echo "=====================================================";
echo "Main Apache Error Log:"
sudo tail -20 /var/log/apache2/error.log
echo ""
echo "DSS-specific Error Log:"
sudo tail -20 /var/log/apache2/dss_error.log 2>/dev/null || echo "DSS error log not found"
echo ""

echo "🔍 Step 2: Checking Apache Configuration"
echo "========================================"
echo "Testing Apache configuration syntax:"
sudo apache2ctl configtest
echo ""

echo "Active Apache sites:"
sudo apache2ctl -S
echo ""

echo "🔍 Step 3: Checking Laravel Application"
echo "======================================"
echo "Laravel application permissions:"
ls -la public/
echo ""
echo "Storage permissions:"
ls -la storage/
echo ""

echo "Laravel logs (last 10 lines):"
tail -10 storage/logs/laravel.log 2>/dev/null || echo "Laravel log not found"
echo ""

echo "🔍 Step 4: Checking .htaccess and Rewrite Rules"
echo "=============================================="
echo "Public/.htaccess exists:"
ls -la public/.htaccess
echo ""
echo ".htaccess content:"
cat public/.htaccess 2>/dev/null || echo ".htaccess not found"
echo ""

echo "🔍 Step 5: Testing SSL Certificate"
echo "================================="
echo "SSL certificate status:"
openssl s_client -connect dssengineercoltd.co.tz:443 -servername dssengineercoltd.co.tz </dev/null 2>/dev/null | openssl x509 -noout -dates
echo ""

echo "🔍 Step 6: Testing Domain Resolution"
echo "==================================="
echo "DNS resolution for main domain:"
nslookup dssengineercoltd.co.tz
echo ""
echo "DNS resolution for www subdomain:"
nslookup www.dssengineercoltd.co.tz
echo ""

echo "🔧 Step 7: Applying Fixes"
echo "========================="

# Fix 1: Ensure proper permissions
echo "Setting correct permissions..."
sudo chown -R www-data:www-data /var/www/html/dss-web/
sudo chmod -R 755 /var/www/html/dss-web/
sudo chmod -R 775 /var/www/html/dss-web/storage/
sudo chmod -R 775 /var/www/html/dss-web/bootstrap/cache/

# Fix 2: Ensure .htaccess exists with correct content
echo "Creating/fixing .htaccess..."
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

# Fix 3: Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Fix 4: Recreate storage link
echo "Recreating storage link..."
php artisan storage:link --force

echo ""
echo "🧪 Step 8: Testing Fixes"
echo "========================"
echo "Testing local Laravel serving:"
php artisan route:list | head -5
echo ""

echo "✅ Diagnostic and fix script completed!"
echo ""
echo "🔍 Next steps:"
echo "1. Check if the www domain now works: https://www.dssengineercoltd.co.tz"
echo "2. Verify redirect works properly"
echo "3. If issues persist, check the Apache error log in real-time:"
echo "   sudo tail -f /var/log/apache2/error.log"
echo ""
echo "🚨 If you still see 500 errors, the most recent error details should be above."
