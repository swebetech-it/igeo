#!/bin/bash

# DSS Engineering Database Migration Fix Script
# This script fixes the duplicate column and foreign key constraint issues

echo "🔧 DSS Engineering Database Migration Fix"
echo "========================================"

# Navigate to the Laravel project directory
cd /var/www/html/dss-web

# Step 1: Rollback problematic migrations
echo "📝 Rolling back problematic migrations..."

# Get the migration status
php artisan migrate:status

# Rollback the specific problematic migrations
echo "⏪ Rolling back expenses table migrations..."
php artisan migrate:rollback --path=database/migrations/2025_09_13_124958_add_enhanced_columns_to_expenses_table.php
php artisan migrate:rollback --path=database/migrations/2025_09_13_104259_update_expenses_table_add_missing_columns.php

# Step 2: Clear all caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Step 3: Run the consolidated migration
echo "🚀 Running consolidated expenses table migration..."
php artisan migrate --path=database/migrations/2025_09_14_120000_fix_expenses_table_consolidated.php

# Step 4: Optimize the application
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 5: Check migration status
echo "✅ Final migration status:"
php artisan migrate:status

echo "🎉 Database migration fixes completed!"
echo "💡 Next steps:"
echo "   1. Test the expenses management functionality"
echo "   2. Verify gallery slider is working"
echo "   3. Check all routes are accessible"
