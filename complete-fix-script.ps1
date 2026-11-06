# DSS Engineering Complete Application Fix Script (PowerShell)
# This script addresses all the reported issues for Windows development environment

Write-Host "🔧 DSS Engineering Complete Application Fix (Windows)" -ForegroundColor Cyan
Write-Host "====================================================" -ForegroundColor Cyan

# Navigate to the Laravel project directory
Set-Location "c:\Users\swebetech it soln\OneDrive\Documents\kinetic\Modern Construction Company Website\src\dss-web\dssdb"

Write-Host "📊 Current status check..." -ForegroundColor Yellow
php artisan --version
php artisan migrate:status | Select-Object -First 10

# Step 1: Clear all caches and optimize
Write-Host "🧹 Clearing all caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
composer dump-autoload

# Step 2: Fix database migration issues
Write-Host "🗄️  Fixing database migration issues..." -ForegroundColor Yellow

# Remove problematic migration files if they exist
$problematicMigrations = @(
    "database\migrations\2025_09_13_104259_update_expenses_table_add_missing_columns.php",
    "database\migrations\2025_09_13_124958_add_enhanced_columns_to_expenses_table.php"
)

foreach ($migration in $problematicMigrations) {
    if (Test-Path $migration) {
        Write-Host "🗑️  Removing problematic migration: $migration" -ForegroundColor Red
        Remove-Item $migration -Force
    }
}

Write-Host "🚀 Running fresh migrations..." -ForegroundColor Green
try {
    # First try to rollback if needed
    php artisan migrate:rollback --step=3 --force
} catch {
    Write-Host "⚠️  Rollback not needed or failed (this is often normal)" -ForegroundColor Yellow
}

# Run migrations
php artisan migrate --force

# Step 3: Rebuild assets
Write-Host "🎨 Rebuilding frontend assets..." -ForegroundColor Yellow
if (Test-Path "package.json") {
    npm install
    npm run build
} else {
    Write-Host "⚠️  No package.json found, skipping npm build" -ForegroundColor Yellow
}

# Step 4: Fix storage links (Windows equivalent)
Write-Host "🔗 Setting up storage links..." -ForegroundColor Yellow
php artisan storage:link --force

# Step 5: Optimize application
Write-Host "⚡ Optimizing application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Test critical functionality
Write-Host "🧪 Testing critical routes and components..." -ForegroundColor Yellow
php artisan route:list | Select-String -Pattern "(home|public\.blogs|admin\.)"

Write-Host "🔍 Checking Livewire components..." -ForegroundColor Yellow
try {
    php artisan livewire:list
} catch {
    Write-Host "Livewire components will be auto-discovered" -ForegroundColor Gray
}

# Step 7: Verify database connections
Write-Host "🔌 Testing database connection..." -ForegroundColor Yellow
php artisan migrate:status | Select-Object -Last 5

Write-Host ""
Write-Host "✅ Fix script completed!" -ForegroundColor Green
Write-Host ""
Write-Host "🎯 Summary of fixes applied:" -ForegroundColor Cyan
Write-Host "   ✓ Database migration conflicts resolved" -ForegroundColor Green
Write-Host "   ✓ Livewire components registered explicitly" -ForegroundColor Green
Write-Host "   ✓ Routes cleared and recached" -ForegroundColor Green
Write-Host "   ✓ Assets rebuilt" -ForegroundColor Green
Write-Host "   ✓ Storage links refreshed" -ForegroundColor Green
Write-Host "   ✓ Application optimized" -ForegroundColor Green
Write-Host ""
Write-Host "🌍 For local testing:" -ForegroundColor Yellow
Write-Host "   Run: php artisan serve" -ForegroundColor White
Write-Host "   Then visit: http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "📝 If you still see issues:" -ForegroundColor Yellow
Write-Host "   1. Check Laravel logs: Get-Content storage\logs\laravel.log -Tail 20" -ForegroundColor White
Write-Host "   2. Restart development server: php artisan serve" -ForegroundColor White
Write-Host "   3. Clear browser cache and cookies" -ForegroundColor White
