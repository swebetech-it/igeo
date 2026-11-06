<?php

use Illuminate\Support\Facades\Route;
use App\Models\Blog;
use App\Models\Experience;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Job;

/**
 * SEO Routes - Sitemap and structured data endpoints
 */

// Dynamic Sitemap Generation
Route::get('/sitemap.xml', function () {
    $sitemap = [];

    // Add homepage
    $sitemap[] = [
        'url' => route('home'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'daily',
        'priority' => 1.0,
    ];

    // Static pages
    $staticPages = [
        ['route' => 'public.about', 'changefreq' => 'monthly', 'priority' => 0.9],
        ['route' => 'public.team', 'changefreq' => 'monthly', 'priority' => 0.8],
        ['route' => 'public.blogs', 'changefreq' => 'weekly', 'priority' => 0.9],
        ['route' => 'public.documentation', 'changefreq' => 'monthly', 'priority' => 0.8],
        ['route' => 'public.experiences', 'changefreq' => 'monthly', 'priority' => 0.8],
        ['route' => 'public.gallery', 'changefreq' => 'weekly', 'priority' => 0.8],
        ['route' => 'public.clients', 'changefreq' => 'monthly', 'priority' => 0.8],
        ['route' => 'public.careers', 'changefreq' => 'weekly', 'priority' => 0.9],
        ['route' => 'public.adverse-event', 'changefreq' => 'monthly', 'priority' => 0.9],
    ];

    foreach ($staticPages as $page) {
        if (route($page['route']) !== null) {
            $sitemap[] = [
                'url' => route($page['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }
    }

    // Add dynamic blog posts
    Blog::published()->orderBy('created_at', 'desc')->get()->each(function (Blog $blog) use (&$sitemap) {
        if (isset($blog->id)) {
            $sitemap[] = [
                'url' => route('public.blog', ['id' => $blog->id]),
                'lastmod' => $blog->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.8,
            ];
        }
    });

    // Add career posts
    Job::where('status', 'active')->get()->each(function (Job $job) use (&$sitemap) {
        if (isset($job->id)) {
            $sitemap[] = [
                'url' => route('public.career.detail', ['id' => $job->id]),
                'lastmod' => $job->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 0.9,
            ];
        }
    });

    // Add experiences
    Experience::published()->get()->each(function (Experience $exp) use (&$sitemap) {
        if (isset($exp->id)) {
            $sitemap[] = [
                'url' => route('public.experiences') . '?id=' . $exp->id,
                'lastmod' => $exp->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.7,
            ];
        }
    });

    return response()->view('sitemap', ['sitemap' => $sitemap], 200)
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

// Organization Schema endpoint
Route::get('/schema/organization.json', function () {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('app.name', 'iGEO'),
        'url' => url('/'),
        'logo' => url('/images/logo.png'),
        'description' => 'Innovative construction and engineering company providing comprehensive solutions',
        'sameAs' => [
            'https://www.facebook.com/igeo',
            'https://www.twitter.com/igeo',
            'https://www.linkedin.com/company/igeo',
            'https://www.instagram.com/igeo',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'Customer Service',
            'telephone' => env('CONTACT_PHONE'),
            'email' => env('CONTACT_EMAIL'),
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => env('BUSINESS_STREET'),
            'addressLocality' => env('BUSINESS_CITY', 'Dar es Salaam'),
            'addressRegion' => env('BUSINESS_STATE', 'Tanzania'),
            'postalCode' => env('BUSINESS_ZIP'),
            'addressCountry' => 'TZ',
        ],
    ];

    return response()->json($schema);
})->name('schema.organization');
