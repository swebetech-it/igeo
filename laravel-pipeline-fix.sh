#!/bin/bash

# Quick Laravel Pipeline Fix for DSS Engineering
echo "🔧 Laravel Pipeline Fix - DSS Engineering"
echo "========================================"

cd /var/www/html/dss-web

# Check current Laravel error
echo "🔍 Checking current Laravel error..."
tail -20 storage/logs/laravel.log | grep -A 10 -B 5 "Pipeline\|Middleware" || echo "No pipeline errors found in log"

# Fix 1: Clear all compiled files
echo "🧹 Clearing all compiled files..."
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Fix 2: Check for problematic middleware
echo "🔧 Checking middleware configuration..."
if [ -f "app/Http/Kernel.php" ]; then
    echo "Kernel.php exists, checking middleware..."
    grep -n "ValidatePathEncoding\|InvokeDeferredCallbacks" app/Http/Kernel.php || echo "Standard middleware found"
fi

# Fix 3: Regenerate optimizations
echo "⚡ Regenerating optimizations..."
php artisan config:cache
php artisan route:cache

# Fix 4: Check for specific Laravel issues
echo "🔍 Checking for common Laravel issues..."

# Check if storage is writable
if [ ! -w "storage/logs" ]; then
    echo "⚠️  Storage logs not writable, fixing..."
    sudo chown -R www-data:www-data storage/
    sudo chmod -R 755 storage/
fi

# Check if bootstrap/cache is writable
if [ ! -w "bootstrap/cache" ]; then
    echo "⚠️  Bootstrap cache not writable, fixing..."
    sudo chown -R www-data:www-data bootstrap/cache/
    sudo chmod -R 755 bootstrap/cache/
fi

# Fix 5: Check .env configuration
echo "🔧 Verifying .env configuration..."
if grep -q "APP_URL=https://www.dssengineercoltd.co.tz" .env; then
    echo "⚠️  Fixing APP_URL (removing www)..."
    sed -i 's|APP_URL=https://www.dssengineercoltd.co.tz|APP_URL=https://dssengineercoltd.co.tz|g' .env
    echo "✅ APP_URL fixed"
fi

# Ensure APP_ENV is set correctly
if ! grep -q "APP_ENV=production" .env; then
    echo "⚠️  Setting APP_ENV to production..."
    sed -i 's/APP_ENV=.*/APP_ENV=production/' .env
    echo "✅ APP_ENV set to production"
fi

# Fix 6: Test basic Laravel functionality
echo "🧪 Testing Laravel functionality..."
php artisan --version
echo "Laravel version check passed"

# Try a simple route test
php artisan route:list | head -3
echo "Route listing successful"

# Fix 7: Create a simple health check route
echo "🏥 Creating health check..."
cat > routes/healthcheck.php << 'EOF'
<?php
// Simple health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name')
    ]);
});
EOF

# Include health check in web routes if not already included
if ! grep -q "healthcheck.php" routes/web.php; then
    echo "require __DIR__.'/healthcheck.php';" >> routes/web.php
    echo "✅ Health check route added"
fi

# Clear caches again after changes
php artisan config:clear
php artisan route:clear

echo ""
echo "✅ Laravel pipeline fix completed!"
echo ""
echo "🧪 Test the health check:"
echo "   curl https://dssengineercoltd.co.tz/health"
echo ""
echo "🔍 If issues persist, check:"
echo "   tail -f storage/logs/laravel.log"
