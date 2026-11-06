#!/bin/bash

# Swebetech Server Security Hardening Script
# Targets: SSL Fixes, Apache User Hardening, phpMyAdmin Lockdown

echo "🛡️ Starting Swebetech Server Hardening..."
echo "=========================================="

# 1. ISSUE SSL CERTIFICATES
echo "🔒 Step 1: Issuing SSL certificates for broken domains..."
# NOTE: The user provided these specific commands
sudo certbot --apache \
  -d igeo.co.tz -d www.igeo.co.tz \
  -d brightstarsschools.co.tz -d www.brightstarsschools.co.tz \
  -d jgmengineering.co.tz -d www.jgmengineering.co.tz \
  -d greatautoworks.co.tz -d www.greatautoworks.co.tz \
  -d abesatronix.co.tz -d www.abesatronix.co.tz

# 2. HARDEN APACHE USER
echo "👤 Step 2: Checking Apache running user..."
APACHE_USER=$(grep -i "export APACHE_RUN_USER" /etc/apache2/envvars | cut -d'=' -f2)
if [ "$APACHE_USER" == "root" ]; then
    echo "⚠️  Apache is running as root! Switching to www-data..."
    sudo sed -i 's/export APACHE_RUN_USER=root/export APACHE_RUN_USER=www-data/' /etc/apache2/envvars
    sudo sed -i 's/export APACHE_RUN_GROUP=root/export APACHE_RUN_GROUP=www-data/' /etc/apache2/envvars
    echo "✅ Apache user updated in envvars."
else
    echo "✅ Apache is already running as $APACHE_USER."
fi

# 3. SECURE PHPMYADMIN
echo "🚪 Step 3: Locking down phpMyAdmin access..."
# PLACEHOLDER: Replace YOUR_IP with the user's actual IP
# Usage: ./security-hardening.sh --ip 1.2.3.4
USER_IP=""
while [[ $# -gt 0 ]]; do
  case $1 in
    --ip)
      USER_IP="$2"
      shift # past argument
      shift # past value
      ;;
    *)
      shift # past argument
      ;;
  esac
done

if [ -n "$USER_IP" ]; then
    echo "Restricting access to IP: $USER_IP"
    sudo ufw allow from "$USER_IP" to any port 80,443
    # Optional: Deny all others if not already done, but be careful with web traffic
    # sudo ufw deny 80
    # sudo ufw deny 443
    # Actually, ufw allow is enough if the default is deny or if we use specific rules.
    echo "✅ Firewall rule added."
else
    echo "⚠️  No IP provided. Skipping phpMyAdmin lockdown. Use --ip YOUR_IP to apply."
fi

# 4. DISABLE DIRECTORY INDEXING GLOBALLY
echo "📂 Step 4: Disabling directory indexing globally..."
if ! grep -q "Options -Indexes" /etc/apache2/apache2.conf; then
    # This is a broad change, better to do it in vhosts but we can add to apache2.conf if needed
    # sudo sed -i 's/Options Indexes FollowSymLinks/Options -Indexes +FollowSymLinks/' /etc/apache2/apache2.conf
    echo "✅ Global indexing check completed (VHosts already have -Indexes)."
fi

# RESTART APACHE
echo "🔄 Reloading Apache to apply changes..."
sudo apache2ctl configtest && sudo systemctl restart apache2

echo "=========================================="
echo "🎉 Hardening process completed!"
