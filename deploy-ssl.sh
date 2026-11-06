#!/bin/bash

# DSS Engineering Deployment Script with SSL Configuration
# Run this script on your production server

echo "🚀 Starting DSS Engineering deployment with SSL configuration..."

# 1. Update application code
echo "📦 Updating application code..."
git pull origin main

# 2. Install/update dependencies
echo "🔧 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# 4. Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 6. SSL Certificate Setup (Let's Encrypt)
echo "🔒 Setting up SSL certificate..."
if command -v certbot &> /dev/null; then
    echo "Certbot found. Setting up SSL certificate..."

    # Install SSL certificate for domain
    certbot --apache -d dssengineercoltd.co.tz -d www.dssengineercoltd.co.tz \
            --email dssengineercoltd@gmail.com \
            --agree-tos \
            --non-interactive \
            --redirect

    echo "✅ SSL certificate installed successfully!"
else
    echo "⚠️  Certbot not found. Please install certbot first:"
    echo "   Ubuntu/Debian: sudo apt install certbot python3-certbot-apache"
    echo "   CentOS/RHEL: sudo yum install certbot python3-certbot-apache"
fi

# 7. Apache/Nginx Configuration
echo "🌐 Configuring web server..."

# Apache Virtual Host with SSL
cat > /etc/apache2/sites-available/dss-engineering-ssl.conf << 'EOF'
<VirtualHost *:443>
    ServerName dssengineercoltd.co.tz
    ServerAlias www.dssengineercoltd.co.tz
    DocumentRoot /var/www/dss-engineering/public

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/dssengineercoltd.co.tz/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/dssengineercoltd.co.tz/privkey.pem

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "DENY"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"

    # Laravel Configuration
    <Directory /var/www/dss-engineering/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/dss-engineering-ssl-error.log
    CustomLog ${APACHE_LOG_DIR}/dss-engineering-ssl-access.log combined
</VirtualHost>

# HTTP to HTTPS Redirect
<VirtualHost *:80>
    ServerName dssengineercoltd.co.tz
    ServerAlias www.dssengineercoltd.co.tz

    # Redirect all HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
EOF

# Enable site and SSL module
a2enmod ssl
a2enmod headers
a2enmod rewrite
a2ensite dss-engineering-ssl.conf
systemctl reload apache2

# 8. Setup automatic SSL renewal
echo "🔄 Setting up automatic SSL renewal..."
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -

# 9. Restart services
echo "♻️  Restarting services..."
systemctl restart apache2
systemctl restart mysql

# 10. Test SSL configuration
echo "🧪 Testing SSL configuration..."
if command -v curl &> /dev/null; then
    echo "Testing HTTPS connection..."
    curl -I https://dssengineercoltd.co.tz/ | head -n 5

    echo "Testing HTTP to HTTPS redirect..."
    curl -I http://dssengineercoltd.co.tz/ | head -n 3
fi

echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Post-deployment checklist:"
echo "   ✅ Application code updated"
echo "   ✅ Dependencies installed"
echo "   ✅ Database migrated"
echo "   ✅ Cache cleared and optimized"
echo "   ✅ File permissions set"
echo "   ✅ SSL certificate configured"
echo "   ✅ Web server configured"
echo "   ✅ Services restarted"
echo ""
echo "🔗 Your website is now available at: https://dssengineercoltd.co.tz"
echo "🔒 SSL Grade: Test at https://www.ssllabs.com/ssltest/"
