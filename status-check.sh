#!/bin/bash

# DSS Engineering - Current Status Check
echo "🔍 DSS Engineering - Current Status Check"
echo "========================================"

# Check current time
echo "📅 Current time: $(date)"
echo ""

# Step 1: Test website accessibility
echo "🌐 Step 1: Testing website accessibility..."
echo "----------------------------------------"

echo "Testing main domain (HTTPS):"
if curl -s -o /dev/null -w "%{http_code}" https://dssengineercoltd.co.tz/ | grep -q "200\|301\|302"; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://dssengineercoltd.co.tz/)
    echo "✅ https://dssengineercoltd.co.tz/ responds with HTTP $HTTP_CODE"
else
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://dssengineercoltd.co.tz/)
    echo "❌ https://dssengineercoltd.co.tz/ responds with HTTP $HTTP_CODE"
fi

echo ""
echo "Testing www subdomain (HTTPS):"
if curl -s -o /dev/null -w "%{http_code}" https://www.dssengineercoltd.co.tz/ | grep -q "200\|301\|302"; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://www.dssengineercoltd.co.tz/)
    echo "✅ https://www.dssengineercoltd.co.tz/ responds with HTTP $HTTP_CODE"

    # Check if it redirects to non-www
    REDIRECT_URL=$(curl -s -I https://www.dssengineercoltd.co.tz/ | grep -i "location:" | cut -d' ' -f2 | tr -d '\r')
    if [[ "$REDIRECT_URL" == *"dssengineercoltd.co.tz"* ]] && [[ "$REDIRECT_URL" != *"www"* ]]; then
        echo "✅ Properly redirects to non-www: $REDIRECT_URL"
    elif [ -n "$REDIRECT_URL" ]; then
        echo "⚠️  Redirects to: $REDIRECT_URL"
    else
        echo "ℹ️  No redirect detected (may be serving content directly)"
    fi
else
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://www.dssengineercoltd.co.tz/)
    echo "❌ https://www.dssengineercoltd.co.tz/ responds with HTTP $HTTP_CODE"
fi

# Step 2: Check for recent errors
echo ""
echo "🔍 Step 2: Checking for recent errors..."
echo "--------------------------------------"

echo "Recent Apache errors (last 5 minutes):"
FIVE_MIN_AGO=$(date -d '5 minutes ago' '+%d/%b/%Y:%H:%M')
if sudo grep "$FIVE_MIN_AGO\|$(date '+%d/%b/%Y:%H:%M')" /var/log/apache2/error.log 2>/dev/null | tail -10; then
    echo "Found recent errors above"
else
    echo "✅ No recent Apache errors found"
fi

echo ""
echo "Recent Laravel errors (last 10 lines):"
if [ -f "/var/www/html/dss-web/storage/logs/laravel.log" ]; then
    tail -10 /var/www/html/dss-web/storage/logs/laravel.log | grep -E "(ERROR|CRITICAL|EMERGENCY)" || echo "✅ No recent Laravel errors"
else
    echo "ℹ️  Laravel log file not found"
fi

# Step 3: Check Apache configuration
echo ""
echo "⚙️  Step 3: Apache configuration status..."
echo "----------------------------------------"

echo "Apache configuration test:"
if sudo apache2ctl configtest 2>&1 | grep -q "Syntax OK"; then
    echo "✅ Apache configuration is valid"
else
    echo "❌ Apache configuration has issues:"
    sudo apache2ctl configtest
fi

echo ""
echo "Active Apache sites:"
sudo apache2ctl -S | grep -E "(Main DocumentRoot|dssengineercoltd)" || echo "Configuration not found"

# Step 4: Check Laravel application
echo ""
echo "🔧 Step 4: Laravel application status..."
echo "--------------------------------------"

cd /var/www/html/dss-web 2>/dev/null || { echo "❌ Cannot access Laravel directory"; exit 1; }

echo "Laravel version:"
php artisan --version 2>/dev/null || echo "❌ Laravel not accessible"

echo ""
echo "Laravel routes (first 5):"
php artisan route:list 2>/dev/null | head -5 || echo "❌ Cannot list routes"

# Step 5: Quick fixes if issues found
echo ""
echo "🔧 Step 5: Quick status fixes..."
echo "------------------------------"

# Fix ServerName warning if present
if sudo apache2ctl configtest 2>&1 | grep -q "Could not reliably determine"; then
    echo "⚠️  Fixing ServerName warning..."
    if ! grep -q "ServerName dssengineercoltd.co.tz" /etc/apache2/apache2.conf; then
        echo "ServerName dssengineercoltd.co.tz" | sudo tee -a /etc/apache2/apache2.conf > /dev/null
        sudo systemctl reload apache2
        echo "✅ ServerName directive added and Apache reloaded"
    else
        echo "✅ ServerName directive already exists"
    fi
fi

# Summary
echo ""
echo "📊 SUMMARY"
echo "=========="

# Test final status
MAIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://dssengineercoltd.co.tz/)
WWW_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://www.dssengineercoltd.co.tz/)

echo "Main domain status: HTTP $MAIN_STATUS"
echo "WWW domain status: HTTP $WWW_STATUS"

if [ "$MAIN_STATUS" = "200" ] && [ "$WWW_STATUS" = "301" -o "$WWW_STATUS" = "302" -o "$WWW_STATUS" = "200" ]; then
    echo ""
    echo "🎉 SUCCESS: Website appears to be working!"
    echo "   • Main site is accessible"
    echo "   • WWW handling is working"
    echo ""
elif [ "$MAIN_STATUS" = "500" ] || [ "$WWW_STATUS" = "500" ]; then
    echo ""
    echo "🚨 ISSUE: 500 error still present"
    echo "   Run the comprehensive fix script:"
    echo "   ./comprehensive-fix.sh"
    echo ""
else
    echo ""
    echo "⚠️  PARTIAL: Some issues may remain"
    echo "   Check individual domain responses above"
    echo ""
fi

echo "🔍 Monitor in real-time:"
echo "   sudo tail -f /var/log/apache2/error.log"
echo "   tail -f /var/www/html/dss-web/storage/logs/laravel.log"
