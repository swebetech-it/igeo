<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Explicitly register Livewire components to avoid discovery issues
        Livewire::component('gallery-slider', \App\Livewire\GallerySlider::class);
        Livewire::component('client-management', \App\Livewire\ClientManagement::class);
        Livewire::component('blog-management', \App\Livewire\BlogManagement::class);
        Livewire::component('service-management', \App\Livewire\ServiceManagement::class);
        Livewire::component('gallery-management', \App\Livewire\GalleryManagement::class);
        Livewire::component('document-management', \App\Livewire\DocumentManagement::class);
        Livewire::component('experience-management', \App\Livewire\ExperienceManagement::class);
        Livewire::component('project-management', \App\Livewire\ProjectManagement::class);
        Livewire::component('expense-management', \App\Livewire\ExpenseManagement::class);
        Livewire::component('income-management', \App\Livewire\IncomeManagement::class);
        Livewire::component('finance-dashboard', \App\Livewire\FinanceDashboard::class);
        Livewire::component('file-upload', \App\Livewire\FileUpload::class);
        Livewire::component('ai-assistant', \App\Livewire\AiAssistant::class);
    }
}
