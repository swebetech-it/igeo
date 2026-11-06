#!/bin/bash

echo "Starting Server Fixes..."

# 1. Update Apache Config (Copy the file we edited locally)
echo "Updating Apache Configuration..."
if [ -f "/var/www/html/igeo/apache-vhost-igeo.conf" ]; then
    sudo cp /var/www/html/igeo/apache-vhost-igeo.conf /etc/apache2/sites-available/igeo.conf
    echo "Apache config copied."
else
    echo "ERROR: apache-vhost-igeo.conf not found in /var/www/html/igeo/"
fi

# 2. Fix Storage Link
echo "Fixing Storage Link..."
cd /var/www/html/igeo
if [ -L "public/storage" ]; then
    rm public/storage
fi
php artisan storage:link

# 3. Fix Ownership & Permissions (Aggressive)
echo "Setting Permissions..."
sudo chown -R www-data:www-data /var/www/html/igeo
sudo chown -R $USER:www-data /var/www/html/igeo/public
sudo find /var/www/html/igeo -type d -exec chmod 755 {} \;
sudo find /var/www/html/igeo -type f -exec chmod 644 {} \;

# Storage and Cache need to be writable
sudo chmod -R 775 /var/www/html/igeo/storage
sudo chmod -R 775 /var/www/html/igeo/bootstrap/cache

# 4. Restart Apache
echo "Reloading Apache..."
sudo systemctl reload apache2

echo "Fixes Complete! Please check the site."
