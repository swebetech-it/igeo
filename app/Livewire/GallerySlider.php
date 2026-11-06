<?php

namespace App\Livewire;

use App\Models\Gallery;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GallerySlider extends Component
{
    public $currentPage = 1;
    public $perPage = 6; // Optimized: Show 6 images for better grid layout
    public $autoSlide = true;
    public $galleries;
    public $totalPages;
    public $totalImages;

    public function mount()
    {
        $this->loadGalleries();
        $this->calculateTotalPages();
    }

    public function loadGalleries()
    {
        try {
            // Cache gallery data for 5 minutes to reduce DB queries
            $this->galleries = Cache::remember('gallery_slider_images', 300, function () {
                return Gallery::select('id', 'title', 'description', 'category', 'file_path', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(30) // Limit to 30 most recent images for performance
                    ->get();
            });
        } catch (\Exception $e) {
            // If there's an error loading galleries, set empty collection
            $this->galleries = collect([]);
            Log::warning('Gallery Slider: Failed to load galleries', ['error' => $e->getMessage()]);
        }
    }

    public function calculateTotalPages()
    {
        $galleryCount = $this->galleries ? $this->galleries->count() : 0;
        $this->totalImages = $galleryCount;
        $this->totalPages = max(1, ceil($galleryCount / $this->perPage));
    }

    public function nextSlide()
    {
        $this->currentPage = $this->currentPage >= $this->totalPages ? 1 : $this->currentPage + 1;
    }

    public function prevSlide()
    {
        $this->currentPage = $this->currentPage <= 1 ? $this->totalPages : $this->currentPage - 1;
    }

    public function goToSlide($page)
    {
        $this->currentPage = max(1, min($page, $this->totalPages));
    }

    public function toggleAutoSlide()
    {
        $this->autoSlide = !$this->autoSlide;
    }

    // Performance: Clear cache when needed
    public function refreshGalleries()
    {
        Cache::forget('gallery_slider_images');
        $this->loadGalleries();
        $this->calculateTotalPages();
    }

    // Optimize: Reduce re-renders by caching computed properties
    public function getComputedPropertyCache()
    {
        return [
            'currentPageGalleries' => $this->getCurrentPageGalleries(),
            'totalPages' => $this->totalPages,
            'totalImages' => $this->totalImages
        ];
    }

    public function getCurrentPageGalleries()
    {
        if (!$this->galleries || $this->galleries->isEmpty()) {
            return collect([]);
        }

        $offset = ($this->currentPage - 1) * $this->perPage;
        return $this->galleries->skip($offset)->take($this->perPage);
    }

    public function render()
    {
        return view('livewire.gallery-slider')->with([
            'currentPageGalleries' => $this->getCurrentPageGalleries(),
            'totalPages' => $this->totalPages,
            'totalImages' => $this->totalImages
        ]);
    }
}
