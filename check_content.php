<?php

use App\Models\AboutUs;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$about = AboutUs::first();
if ($about) {
    echo "Current DB Content: " . substr($about->content, 0, 100) . "...\n";
    echo "This overrides the static fallback text.\n";
} else {
    echo "No DB Content found. Fallback text should show.\n";
}
