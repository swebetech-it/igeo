<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for search engine optimization features
    |
    */

    'site_name' => env('APP_NAME', 'iGEO'),

    'default_description' => 'Leading construction and engineering company delivering innovative solutions for modern infrastructure',

    'default_keywords' => 'construction, engineering, infrastructure, building, projects, Tanzania',

    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK'),
        'twitter' => env('SOCIAL_TWITTER'),
        'linkedin' => env('SOCIAL_LINKEDIN'),
        'instagram' => env('SOCIAL_INSTAGRAM'),
    ],

    'business' => [
        'name' => env('APP_NAME'),
        'phone' => env('CONTACT_PHONE'),
        'email' => env('CONTACT_EMAIL'),
        'address' => env('BUSINESS_STREET'),
        'city' => env('BUSINESS_CITY', 'Dar es Salaam'),
        'state' => env('BUSINESS_STATE', 'Tanzania'),
        'zip' => env('BUSINESS_ZIP'),
        'country' => 'Tanzania',
        'country_code' => 'TZ',
    ],

    'og' => [
        'default_image' => '/images/og-image.jpg',
        'image_width' => 1200,
        'image_height' => 630,
    ],

    'pagination' => [
        'enable_canonical_for_pages' => true,
        'robots_meta_for_additional_pages' => 'noindex, follow',
    ],

    'sitemap' => [
        'enabled' => true,
        'cache_minutes' => 60,
        'max_entries' => 50000,
    ],
];
