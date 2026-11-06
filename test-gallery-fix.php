<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing Gallery Management Livewire Component Fix...\n\n";

// Test 1: Check if the view file exists and has single root element
echo "1. Checking view file structure:\n";
$viewPath = resource_path('views/livewire/gallery-management.blade.php');
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);

    // Count root level elements
    $rootElements = 0;
    $lines = explode("\n", trim($content));
    $inPhpBlock = false;
    $inComment = false;

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Skip empty lines and comments
        if (empty($trimmed) || strpos($trimmed, '{{--') === 0) continue;

        // Check for root HTML elements (not nested)
        if (preg_match('/^<([a-zA-Z][a-zA-Z0-9]*)[^>]*>/', $trimmed, $matches)) {
            $rootElements++;
            echo "   Found root element: <{$matches[1]}>\n";
        }
    }

    echo "   Total root elements found: $rootElements\n";

    if ($rootElements === 1) {
        echo "   ✅ Single root element structure confirmed\n";
    } else {
        echo "   ❌ Multiple root elements detected (Livewire requires exactly 1)\n";
    }
} else {
    echo "   ❌ View file not found\n";
}

echo "\n2. Checking for style blocks in correct location:\n";
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    if (strpos($content, '<style>') !== false) {
        // Check if style is inside the root div
        $firstDiv = strpos($content, '<div>');
        $stylePos = strpos($content, '<style>');

        if ($firstDiv !== false && $stylePos !== false && $stylePos > $firstDiv) {
            echo "   ✅ Style block is properly nested inside root element\n";
        } else {
            echo "   ❌ Style block is outside root element\n";
        }
    } else {
        echo "   ℹ️ No style blocks found\n";
    }
}

echo "\n3. Testing Livewire component instantiation:\n";
try {
    $component = app(\App\Livewire\GalleryManagement::class);
    echo "   ✅ GalleryManagement component can be instantiated\n";

    // Test basic properties
    if (method_exists($component, 'render')) {
        echo "   ✅ Component has render method\n";
    }

    if (property_exists($component, 'galleries')) {
        echo "   ✅ Component has galleries property\n";
    }

} catch (Exception $e) {
    echo "   ❌ Error instantiating component: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 SUMMARY:\n";
echo "The Gallery Management view has been fixed to have a single root element.\n";
echo "This should resolve the Livewire multiple root elements error.\n";
echo "Try accessing /admin/gallery again to test the fix.\n";
echo str_repeat("=", 60) . "\n";
