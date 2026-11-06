# Emergency 500 Error Diagnostic Script (PowerShell)
Write-Host "🚨 DSS Engineering 500 Error Diagnosis (Windows)" -ForegroundColor Red
Write-Host "================================================" -ForegroundColor Red

Set-Location "c:\Users\swebetech it soln\OneDrive\Documents\kinetic\Modern Construction Company Website\src\dss-web\dssdb"

# Check Laravel application status
Write-Host "📊 Laravel Application Status:" -ForegroundColor Yellow
Write-Host "Laravel version:" -ForegroundColor Gray
php artisan --version

Write-Host "`n🔍 Testing Laravel routes:" -ForegroundColor Yellow
php artisan route:list | Select-Object -First 5

# Check for common issues
Write-Host "`n🔧 Checking for common issues:" -ForegroundColor Yellow

# Check if .env exists
if (Test-Path ".env") {
    Write-Host "✅ .env file exists" -ForegroundColor Green

    # Check APP_URL
    $appUrl = Get-Content .env | Select-String "APP_URL"
    Write-Host "APP_URL setting: $appUrl" -ForegroundColor Gray
} else {
    Write-Host "❌ .env file missing!" -ForegroundColor Red
}

# Check Laravel logs
Write-Host "`n📋 Laravel Logs (last 10 lines):" -ForegroundColor Yellow
if (Test-Path "storage\logs\laravel.log") {
    Get-Content "storage\logs\laravel.log" | Select-Object -Last 10
} else {
    Write-Host "No Laravel log found" -ForegroundColor Gray
}

# Clear caches
Write-Host "`n🧹 Clearing Laravel caches:" -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check storage link
Write-Host "`n🔗 Checking storage link:" -ForegroundColor Yellow
if (Test-Path "public\storage") {
    Write-Host "✅ Storage link exists" -ForegroundColor Green
} else {
    Write-Host "⚠️  Creating storage link..." -ForegroundColor Yellow
    php artisan storage:link
}

# Test local server
Write-Host "`n🧪 Testing local development server:" -ForegroundColor Yellow
Write-Host "To test locally, run: php artisan serve" -ForegroundColor Green
Write-Host "Then visit: http://localhost:8000" -ForegroundColor Green

Write-Host "`n📝 Common 500 Error Causes:" -ForegroundColor Cyan
Write-Host "1. Wrong file permissions (Linux/Apache)" -ForegroundColor Gray
Write-Host "2. Missing .htaccess file" -ForegroundColor Gray
Write-Host "3. PHP errors in application" -ForegroundColor Gray
Write-Host "4. Database connection issues" -ForegroundColor Gray
Write-Host "5. Missing Laravel dependencies" -ForegroundColor Gray

Write-Host "`n🔍 Next steps for server:" -ForegroundColor Cyan
Write-Host "1. Run the emergency-500-fix.sh script on the server" -ForegroundColor Gray
Write-Host "2. Check Apache error logs: sudo tail -f /var/log/apache2/error.log" -ForegroundColor Gray
Write-Host "3. Check Laravel logs on server: tail -f storage/logs/laravel.log" -ForegroundColor Gray
Write-Host "4. Verify SSL certificates are valid" -ForegroundColor Gray

# Test database connection
Write-Host "`n🗄️  Testing database connection:" -ForegroundColor Yellow
try {
    php artisan migrate:status | Select-Object -First 5
    Write-Host "✅ Database connection working" -ForegroundColor Green
} catch {
    Write-Host "❌ Database connection issue" -ForegroundColor Red
}

Write-Host "`n✅ Local diagnosis completed!" -ForegroundColor Green
