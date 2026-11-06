#!/bin/bash

# DSS Engineering Apache Configuration Deployment Script
echo "🌐 DSS Engineering Apache Configuration Update"
echo "============================================="

# Backup existing configuration
echo "📋 Creating backup of existing configuration..."
sudo cp /etc/apache2/sites-available/dssengineercoltd.co.tz.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf.backup.$(date +%Y%m%d_%H%M%S)

# Copy new configuration
echo "📄 Installing new Apache configuration..."
sudo cp dssengineercoltd-complete.conf /etc/apache2/sites-available/dssengineercoltd.co.tz.conf

# Test Apache configuration
echo "🔍 Testing Apache configuration..."
sudo apache2ctl configtest

if [ $? -eq 0 ]; then
    echo "✅ Apache configuration test passed!"

    # Enable required modules
    echo "🔧 Enabling required Apache modules..."
    sudo a2enmod rewrite
    sudo a2enmod ssl
    sudo a2enmod headers
    sudo a2enmod expires
    sudo a2enmod deflate

    # Enable the site
    echo "🌐 Enabling DSS Engineering site..."
    sudo a2ensite dssengineercoltd.co.tz

    # Disable default site if it's enabled
    sudo a2dissite 000-default 2>/dev/null || true

    # Reload Apache
    echo "🔄 Reloading Apache configuration..."
    sudo systemctl reload apache2

    # Check Apache status
    echo "📊 Checking Apache status..."
    sudo systemctl status apache2 --no-pager -l

    echo ""
    echo "✅ Apache configuration updated successfully!"
    echo ""
    echo "🧪 Testing URLs:"
    echo "   • http://dssengineercoltd.co.tz (should redirect to HTTPS)"
    echo "   • http://www.dssengineercoltd.co.tz (should redirect to https://dssengineercoltd.co.tz)"
    echo "   • https://dssengineercoltd.co.tz (main site)"
    echo "   • https://www.dssengineercoltd.co.tz (should redirect to non-www)"
    echo ""
    echo "🔍 To monitor access logs:"
    echo "   sudo tail -f /var/log/apache2/dss_access.log"
    echo ""
    echo "🚨 To monitor error logs:"
    echo "   sudo tail -f /var/log/apache2/dss_error.log"

else
    echo "❌ Apache configuration test failed!"
    echo "🔧 Please check the configuration file for syntax errors."
    exit 1
fi
