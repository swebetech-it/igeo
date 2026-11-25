<?php

use Illuminate\Support\Facades\Route;
use App\Models\Blog;
use App\Models\Experience;
use App\Models\Career;
use App\Support\RegulatoryServicesCatalog;

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
        ['route' => 'public.services', 'changefreq' => 'weekly', 'priority' => 0.95],
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

    // Add all 8 Regulatory & Pharmaceutical Compliance Service pages
    foreach (RegulatoryServicesCatalog::all() as $regService) {
        $sitemap[] = [
            'url' => route('public.service.detail', ['slug' => $regService['slug']]),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => 0.9,
        ];
    }

    // Add dynamic blog posts
    try {
        Blog::orderBy('created_at', 'desc')->get()->each(function (Blog $blog) use (&$sitemap) {
            if (isset($blog->id)) {
                $sitemap[] = [
                    'url' => route('public.blog', ['id' => $blog->id]),
                    'lastmod' => optional($blog->updated_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => 0.8,
                ];
            }
        });
    } catch (\Throwable $e) {
    }

    // Add career posts
    try {
        Career::active()->get()->each(function (Career $career) use (&$sitemap) {
            if (isset($career->id)) {
                $sitemap[] = [
                    'url' => route('public.career.detail', ['id' => $career->id]),
                    'lastmod' => optional($career->updated_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => 0.9,
                ];
            }
        });
    } catch (\Throwable $e) {
    }

    // Add experiences
    try {
        Experience::all()->each(function (Experience $exp) use (&$sitemap) {
            if (isset($exp->id)) {
                $sitemap[] = [
                    'url' => route('public.experiences') . '?id=' . $exp->id,
                    'lastmod' => optional($exp->updated_at)->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => 0.7,
                ];
            }
        });
    } catch (\Throwable $e) {
    }

    return response()->view('sitemap', ['sitemap' => $sitemap], 200)
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

// Organization Schema endpoint
Route::get('/schema/organization.json', function () {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('app.name', 'iGEO Pharmacy & Consulting'),
        'url' => url('/'),
        'logo' => asset('Logo.png?v=20261125'),
        'description' => 'Specialist pharmaceutical regulatory affairs, market-entry and compliance consultancy supporting manufacturers, MAHs, importers and healthcare institutions in Tanzania.',
        'sameAs' => [
            'https://www.facebook.com/igeo',
            'https://www.twitter.com/igeo',
            'https://www.linkedin.com/company/igeo',
            'https://www.instagram.com/igeo',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'Customer Service & Regulatory Affairs',
            'telephone' => env('CONTACT_PHONE', '+255 713 035 438'),
            'email' => env('CONTACT_EMAIL', 'info@igeo.co.tz'),
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => env('BUSINESS_STREET', 'Dar es Salaam'),
            'addressLocality' => env('BUSINESS_CITY', 'Dar es Salaam'),
            'addressRegion' => env('BUSINESS_STATE', 'Tanzania'),
            'postalCode' => env('BUSINESS_ZIP', ''),
            'addressCountry' => 'TZ',
        ],
    ];

    return response()->json($schema);
})->name('schema.organization');
