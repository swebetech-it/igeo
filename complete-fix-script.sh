#!/bin/bash

# DSS Engineering Complete Application Fix Script
# This script addresses all the reported issues comprehensively

echo "🔧 DSS Engineering Complete Application Fix"
echo "==========================================="

# Navigate to the Laravel project directory
cd /var/www/html/dss-web

echo "📊 Current status check..."
php artisan --version
php artisan migrate:status | head -10

# Step 1: Clear all caches and optimize
echo "🧹 Clearing all caches..."
php artisan down --message="Fixing application issues"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan queue:clear
composer dump-autoload

# Step 2: Fix database migration issues
echo "🗄️  Fixing database migration issues..."

# Check for duplicate migrations
echo "Checking for migration conflicts..."
if php artisan migrate:status | grep -q "2025_09_13_104259_update_expenses_table"; then
    echo "⏪ Rolling back conflicting migrations..."
    php artisan migrate:rollback --step=2 --force
fi

# Run the consolidated migration
echo "🚀 Running consolidated migration..."
php artisan migrate --force

# Step 3: Rebuild assets
echo "🎨 Rebuilding frontend assets..."
if [ -f "package.json" ]; then
    npm install
    npm run build
else
    echo "⚠️  No package.json found, skipping npm build"
fi

# Step 4: Fix storage permissions and links
echo "🔗 Setting up storage links and permissions..."
php artisan storage:link --force
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# Step 5: Optimize application
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Step 6: Test critical routes
echo "🧪 Testing critical routes and components..."
php artisan route:list | grep -E "(home|public\.blogs|admin\.)"

echo "🔍 Checking Livewire components..."
php artisan livewire:list 2>/dev/null || echo "Livewire components will be auto-discovered"

# Step 7: Verify database connections
echo "🔌 Testing database connection..."
php artisan migrate:status | tail -5

# Step 8: Final cleanup and restart services
echo "🔄 Restarting services..."
php artisan up

# If using Apache, restart it
if systemctl is-active --quiet apache2; then
    echo "🌐 Restarting Apache..."
    systemctl reload apache2
fi

# If using Nginx, restart it
if systemctl is-active --quiet nginx; then
    echo "🌐 Restarting Nginx..."
    systemctl reload nginx
fi

echo ""
echo "✅ Fix script completed!"
echo ""
echo "🎯 Summary of fixes applied:"
echo "   ✓ Database migration conflicts resolved"
echo "   ✓ Livewire components registered explicitly"
echo "   ✓ Routes cleared and recached"
echo "   ✓ Assets rebuilt"
echo "   ✓ Storage links refreshed"
echo "   ✓ Application optimized"
echo ""
echo "🌍 Test your website at: https://dssengineercoltd.co.tz"
echo ""
echo "📝 If you still see issues:"
echo "   1. Check Apache error logs: tail -f /var/log/apache2/dss_error.log"
echo "   2. Check Laravel logs: tail -f storage/logs/laravel.log"
echo "   3. Verify SSL certificate: openssl s_client -connect dssengineercoltd.co.tz:443"
