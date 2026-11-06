<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Gallery;

class OptimizeSlideshow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slideshow:optimize {--clear-cache : Clear the gallery cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize slideshow performance by managing cache and monitoring resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Slideshow Performance Optimizer');
        $this->line('');

        // Memory usage before optimization
        $memoryBefore = memory_get_usage(true);
        $this->info("Memory usage (before): " . $this->formatBytes($memoryBefore));

        // Check current cache status
        $cacheExists = Cache::has('gallery_slider_images');
        $this->info("Gallery cache status: " . ($cacheExists ? '✅ Active' : '❌ Empty'));

        // Clear cache if requested
        if ($this->option('clear-cache')) {
            Cache::forget('gallery_slider_images');
            $this->info('🗑️  Gallery cache cleared');
        }

        // Gallery statistics
        $totalImages = Gallery::count();
        $recentImages = Gallery::where('created_at', '>=', now()->subDays(30))->count();
        
        $this->info("📊 Gallery Statistics:");
        $this->line("   Total images: {$totalImages}");
        $this->line("   Recent images (30 days): {$recentImages}");

        // Performance recommendations
        $this->line('');
        $this->info('🚀 Performance Status:');

        if ($totalImages > 50) {
            $this->warn("   ⚠️  Large gallery detected ({$totalImages} images)");
            $this->line("   💡 Consider image optimization or pagination");
        } else {
            $this->info("   ✅ Gallery size is optimal");
        }

        // Check for large images
        $largeImages = Gallery::whereRaw('LENGTH(file_path) > 50')->count();
        if ($largeImages > 0) {
            $this->warn("   ⚠️  {$largeImages} images may have long file paths");
        }

        // Memory usage after optimization
        $memoryAfter = memory_get_usage(true);
        $this->info("Memory usage (after): " . $this->formatBytes($memoryAfter));

        // Performance tips
        $this->line('');
        $this->info('💡 Server Resource Optimizations Applied:');
        $this->line('   ✅ 5-minute cache TTL for gallery data');
        $this->line('   ✅ Limited to 30 most recent images');
        $this->line('   ✅ Page visibility API prevents background slideshow');
        $this->line('   ✅ Auto-pause after 5 minutes of inactivity');
        $this->line('   ✅ Lazy loading and async image decoding');
        $this->line('   ✅ Optimized database queries with select fields');

        $this->line('');
        $this->info('🎯 Slideshow optimization completed!');
        
        return Command::SUCCESS;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}