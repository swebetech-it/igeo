<?php

// Project-specific finance dashboard (global route for Blade access)
Route::get('/projects/{project}/finance', [\App\Http\Controllers\ProjectFinanceController::class, 'show'])->middleware(['auth'])->name('projects.finance');

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ClientManagement;
use App\Livewire\BlogManagement;
use App\Livewire\ServiceManagement;
use App\Livewire\GalleryManagement;
use App\Livewire\ExperienceManagement;
use App\Livewire\DocumentManagement;
use App\Livewire\ProjectManagement;
use App\Livewire\ExpenseManagement;
use App\Livewire\IncomeManagement;
use App\Livewire\FinanceDashboard;
use App\Livewire\EmployeeManagement;
use App\Livewire\RoleManagement;
use App\Livewire\PermissionManagement;
use App\Livewire\AnalyticsDashboard;
use App\Livewire\ContractManagement;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\GalleryController;

// Public website routes
Route::get('/', [WebsiteController::class, 'homeModern'])->name('home');
Route::get('/home-new', [WebsiteController::class, 'homeNew'])->name('home.new');
Route::get('/home-radical', [WebsiteController::class, 'homeRadical'])->name('home.radical');
Route::get('/home-asymmetrical', [WebsiteController::class, 'homeAsymmetrical'])->name('home.asymmetrical');
Route::get('/new-design', function () {
    return view('new-design');
})->name('new.design');
Route::get('/radical-design', function () {
    return view('radical-design');
})->name('radical.design');
Route::get('/asymmetrical-design', function () {
    return view('asymmetrical-design');
})->name('asymmetrical.design');
Route::post('/contact', [WebsiteController::class, 'contact'])->name('contact.submit');
Route::get('/about', [WebsiteController::class, 'about'])->name('public.about');
Route::get('/team', [WebsiteController::class, 'team'])->name('public.team');

// Public Blog routes
Route::get('/blogs', [WebsiteController::class, 'blogs'])->name('public.blogs');
Route::get('/blog/{id}', [WebsiteController::class, 'blog'])->name('public.blog');

// Public Documentation routes
Route::get('/documentation', [WebsiteController::class, 'documentation'])->name('public.documentation');

// Public Experience page
Route::get('/experiences', [ExperienceController::class, 'index'])->name('public.experiences');

// Public Gallery page
Route::get('/gallery', [GalleryController::class, 'index'])->name('public.gallery');

// Public Clients page
Route::get('/clients', [WebsiteController::class, 'clients'])->name('public.clients');

// Public Careers routes
Route::get('/careers', [WebsiteController::class, 'careers'])->name('public.careers');
Route::get('/careers/{id}', [WebsiteController::class, 'careerDetail'])->name('public.career.detail');

// Public Shop route
Route::get('/shop', \App\Livewire\Shop::class)->name('public.shop');

// Public Pharmacovigilance / Adverse Event Reporting routes
Route::get('/report-adverse-event', \App\Livewire\AdverseEventReporting::class)->name('public.adverse-event');
Route::redirect('/pharmacovigilance', '/report-adverse-event');

// Authenticated routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/clients', ClientManagement::class)->name('clients');
        Route::get('/blogs', BlogManagement::class)->name('blogs');
        Route::get('/services', ServiceManagement::class)->name('services');
        Route::get('/gallery', GalleryManagement::class)->name('gallery');
        Route::get('/gallery/upload', App\Livewire\FileUpload::class)->name('gallery.upload');
        Route::get('/documents', DocumentManagement::class)->name('documents');
        Route::get('/experience', ExperienceManagement::class)->name('experience');

        // Finance Module Routes
        Route::get('/finance/dashboard', FinanceDashboard::class)->name('finance.dashboard');
        Route::get('/finance/projects', ProjectManagement::class)->name('finance.projects');
        Route::get('/finance/expenses', ExpenseManagement::class)->name('finance.expenses');
        Route::get('/finance/income', IncomeManagement::class)->name('finance.income');

        // Employee Management Routes
        Route::get('/employees', EmployeeManagement::class)->name('employees');
        Route::get('/roles', RoleManagement::class)->name('roles');
        Route::get('/permissions', PermissionManagement::class)->name('permissions');
        
        // Advanced Analytics Dashboard
        Route::get('/analytics', AnalyticsDashboard::class)->name('analytics');
        
        // Contract Management & E-Signatures
        Route::get('/contracts', ContractManagement::class)->name('contracts');

        // Shop & AI Management
        Route::get('/products', App\Livewire\ProductManagement::class)->name('products');
        Route::get('/orders', App\Livewire\OrderManagement::class)->name('orders');
        Route::get('/leads', App\Livewire\LeadManagement::class)->name('leads');

        // Career Management
        Route::get('/careers', App\Livewire\CareerManagement::class)->name('careers');

        // Pharmacovigilance / Adverse Event Management
        Route::get('/adverse-events', App\Livewire\AdverseEventManagement::class)->name('adverse-events');
        
        // Test route
        Route::get('/test-employee', function () {
            return view('test-employee');
        })->name('test.employee');

        // Button test route
        Route::get('/test-buttons', function () {
            return view('test-buttons');
        })->name('test.buttons');

    });
});

// SEO Routes
require __DIR__.'/seo.php';

require __DIR__.'/auth.php';