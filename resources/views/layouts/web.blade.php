@php
    $navRegulatoryServices = \App\Support\RegulatoryServicesCatalog::all();
@endphp
<!DOCTYPE html>
<html class="light scroll-smooth" lang="en">
<head>
    <!-- Character Encoding & Viewport -->
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    <!-- SEO Meta Tags -->
    <title>IGEO | @yield('title', $meta_title ?? 'Pharmaceutical Regulatory Affairs & Compliance Services')</title>
    <meta name="description" content="@yield('meta_description', $meta_description ?? 'iGEO Pharmacy & Consulting - Specialist pharmaceutical regulatory affairs, market-entry and compliance consultancy in Tanzania')"/>
    <meta name="keywords" content="@yield('meta_keywords', $meta_keywords ?? 'TMDA registration, pharmaceutical regulatory affairs Tanzania, pharmacovigilance, Pharmacy Council, GSDP GDP, iGEO Pharmacy & Consulting')"/>
    <meta name="author" content="IGEO"/>
    <meta name="robots" content="index, follow"/>
    <link rel="canonical" href="{{ url()->current() }}"/>
    
    <!-- Open Graph Meta Tags for Social Sharing -->
    <meta property="og:site_name" content="IGEO"/>
    <meta property="og:title" content="@yield('og_title', $meta_title ?? 'IGEO')"/>
    <meta property="og:description" content="@yield('og_description', $meta_description ?? 'Specialist pharmaceutical regulatory affairs, market-entry and compliance consultancy in Tanzania')"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="@yield('og_type', 'website')"/>
    <meta property="og:image" content="@yield('og_image', asset('Logo.png?v=20261125b'))"/>
    <meta property="og:image:width" content="640"/>
    <meta property="og:image:height" content="640"/>
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="@yield('twitter_title', $meta_title ?? 'IGEO')"/>
    <meta name="twitter:description" content="@yield('twitter_description', $meta_description ?? 'IGEO - Regulatory Affairs & Compliance')"/>
    <meta name="twitter:image" content="@yield('twitter_image', asset('Logo.png?v=20261125b'))"/>
    
    <!-- Additional SEO Tags -->
    <meta name="language" content="English"/>
    <meta name="country" content="Tanzania"/>
    <meta name="theme-color" content="#0b1d33"/>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png?v=20261125') }}"/>
    <link rel="apple-touch-icon" href="{{ asset('Logo-emblem.png?v=20261125') }}"/>
    
    <!-- Structured Data -->
    @hasSection('structured_data')
        @yield('structured_data')
    @else
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "IGEO",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('Logo.png?v=20261125b') }}",
            "description": "Specialist pharmaceutical regulatory affairs, market-entry and compliance consultancy supporting manufacturers, MAHs, importers and healthcare institutions in Tanzania.",
            "sameAs": [
                "https://www.facebook.com/igeo",
                "https://www.twitter.com/igeo",
                "https://www.linkedin.com/company/igeo",
                "https://www.instagram.com/igeo"
            ],
            "contactPoint": {
                "@@type": "ContactPoint",
                "contactType": "Regulatory Affairs & Customer Support",
                "telephone": "{{ env('CONTACT_PHONE', '+255713035438') }}",
                "email": "{{ env('CONTACT_EMAIL', 'info@igeo.co.tz') }}"
            },
            "address": {
                "@@type": "PostalAddress",
                "streetAddress": "{{ env('BUSINESS_STREET', 'Dar es Salaam') }}",
                "addressLocality": "{{ env('BUSINESS_CITY', 'Dar es Salaam') }}",
                "addressRegion": "{{ env('BUSINESS_STATE', 'Dar es Salaam Region') }}",
                "postalCode": "{{ env('BUSINESS_ZIP', '') }}",
                "addressCountry": "TZ"
            }
        }
        </script>
    @endif
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Plus+Jakarta+Sans:wght@500;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "brand-navy": "#0b1d33",
                        "brand-crimson": "#9e1b1e",
                        "background-light": "#f8fafc",
                        "background-dark": "#0f1c14",
                        "slate-custom": "#475569",
                        "teal-custom": "#0d9488",
                    },
                    fontFamily: {
                        "sans": ["Inter", "system-ui", "-apple-system", "sans-serif"],
                        "display": ["Plus Jakarta Sans", "Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            letter-spacing: -0.02em;
        }
        .split-hero-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 80vh;
        }
        .data-grid-line {
            background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px), linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .data-grid-line-dark {
             background-image: linear-gradient(to right, #1a2e1a 1px, transparent 1px), linear-gradient(to bottom, #1a2e1a 1px, transparent 1px);
             background-size: 40px 40px;
        }
        .hover-reveal:hover .reveal-content {
            max-height: 200px;
            opacity: 1;
        }
        .reveal-content {
            max-height: 0;
            opacity: 0;
            transition: all 0.35s ease-in-out;
            overflow: hidden;
        }
        /* Pure-CSS + Alpine resilient drop-line menus */
        .nav-dropdown-group:hover .nav-dropdown-panel,
        .nav-dropdown-group:focus-within .nav-dropdown-panel {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        .nav-dropdown-panel {
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        }
        [x-cloak] { display: none !important; }
        @yield('styles')
    </style>
    @livewireStyles
</head>
<body class="bg-background-light dark:bg-background-dark font-sans text-slate-800 dark:text-gray-100 selection:bg-emerald-500/20">
<div class="relative flex min-h-screen flex-col overflow-x-hidden">
    <!-- Navigation -->
    <header x-data="{ mobileMenuOpen: false, mobileServicesOpen: false, mobileAboutOpen: false }" class="sticky top-0 z-50 w-full border-b border-slate-200/80 dark:border-gray-800/70 bg-white/95 dark:bg-background-dark/95 backdrop-blur-md shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-10 py-3.5">
            <!-- Brand Logo (Exact Nov 14 Eagle + IGEO without Pharmacy & Consulting) -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group cursor-pointer shrink-0">
                <img src="{{ asset('Logo-header.png?v=20261125b') }}" alt="IGEO Logo" class="h-11 sm:h-12 w-auto object-contain transition-transform duration-200 group-hover:scale-105 dark:hidden">
                <div class="hidden dark:flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white p-0.5 shadow-sm">
                        <img src="{{ asset('Logo-emblem.png?v=20261125b') }}" alt="IGEO Logo" class="h-10 w-10 object-contain">
                    </div>
                    <span class="font-heading text-2xl font-extrabold tracking-wider text-white leading-none">IGEO</span>
                </div>
            </a>

            <!-- Streamlined Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                <!-- 1. Home -->
                <a class="px-3.5 py-2 rounded-lg text-[14.5px] font-semibold {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50/80 dark:text-primary dark:bg-white/5' : 'text-slate-700 dark:text-gray-200 hover:text-emerald-700 hover:bg-slate-50 dark:hover:bg-white/5' }} transition-all" href="{{ route('home') }}">
                    Home
                </a>

                <!-- 2. About (with Company, Gallery, Team, Experience, Clients & Careers tucked cleanly inside) -->
                <div class="relative nav-dropdown-group">
                    <a href="{{ route('public.about') }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-lg text-[14.5px] font-semibold {{ request()->routeIs('public.about', 'public.team', 'public.experiences', 'public.clients', 'public.gallery', 'public.careers*') ? 'text-emerald-700 bg-emerald-50/80 dark:text-primary dark:bg-white/5' : 'text-slate-700 dark:text-gray-200 hover:text-emerald-700 hover:bg-slate-50 dark:hover:bg-white/5' }} transition-all">
                        <span>About</span>
                        <span class="material-symbols-outlined text-[18px] opacity-75">expand_more</span>
                    </a>
                    <div class="nav-dropdown-panel absolute left-0 top-full pt-2 w-64 z-50">
                        <div class="rounded-2xl border border-slate-200/90 dark:border-gray-800 bg-white dark:bg-slate-900 p-2 shadow-xl shadow-slate-900/10">
                            <a href="{{ route('public.about') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">apartment</span>
                                <span>About iGEO</span>
                            </a>
                            <a href="{{ route('public.team') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">groups</span>
                                <span>Leadership &amp; Team</span>
                            </a>
                            <a href="{{ route('public.experiences') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">timeline</span>
                                <span>Track Record &amp; Experience</span>
                            </a>
                            <a href="{{ route('public.clients') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">handshake</span>
                                <span>Clients &amp; Partners</span>
                            </a>
                            <div class="my-1.5 border-t border-slate-100 dark:border-gray-800"></div>
                            <a href="{{ route('public.gallery') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">photo_library</span>
                                <span>Media &amp; Project Gallery</span>
                            </a>
                            <a href="{{ route('public.careers') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">work</span>
                                <span>Careers</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Services (Drop-Line Menu of all 8 Core Regulatory Service Lines) -->
                <div class="relative nav-dropdown-group">
                    <a href="{{ route('public.services') }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-lg text-[14.5px] font-semibold {{ request()->routeIs('public.services', 'public.service.detail') ? 'text-emerald-700 bg-emerald-50/80 dark:text-primary dark:bg-white/5' : 'text-slate-700 dark:text-gray-200 hover:text-emerald-700 hover:bg-slate-50 dark:hover:bg-white/5' }} transition-all">
                        <span>Services</span>
                        <span class="material-symbols-outlined text-[18px] opacity-75">expand_more</span>
                    </a>
                    <div class="nav-dropdown-panel absolute left-1/2 -translate-x-1/2 top-full pt-2 w-[430px] z-50">
                        <div class="rounded-2xl border border-slate-200/90 dark:border-gray-800 bg-white dark:bg-slate-900 shadow-2xl shadow-slate-900/15 overflow-hidden">
                            <div class="bg-slate-50/90 dark:bg-slate-800/70 px-4 py-3 border-b border-slate-200/70 dark:border-gray-800 flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-700 dark:text-primary">8 Core Service Lines</p>
                                    <p class="text-xs text-slate-500 dark:text-gray-400">Select any service to view full scope &amp; request consultation</p>
                                </div>
                                <a href="{{ route('public.services') }}" class="text-xs font-bold text-emerald-700 dark:text-primary hover:underline whitespace-nowrap">
                                    View All &rarr;
                                </a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-gray-800/80 max-h-[72vh] overflow-y-auto py-1">
                                @foreach($navRegulatoryServices as $navService)
                                    <a href="{{ route('public.service.detail', ['slug' => $navService['slug']]) }}"
                                       class="group/item flex items-start gap-3.5 px-4 py-3 hover:bg-emerald-50/70 dark:hover:bg-white/5 transition-colors">
                                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#0b1d33]/5 dark:bg-white/10 text-xs font-extrabold text-[#0b1d33] dark:text-primary group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                                            {{ $navService['number'] }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[13.5px] font-semibold text-slate-800 dark:text-gray-100 group-hover/item:text-emerald-800 dark:group-hover/item:text-primary leading-snug">
                                                {{ $navService['name'] }}
                                            </p>
                                            <p class="mt-0.5 text-[11.5px] text-slate-500 dark:text-gray-400 truncate">
                                                {{ $navService['category_badge'] }}
                                            </p>
                                        </div>
                                        <span class="material-symbols-outlined text-[16px] text-slate-300 group-hover/item:text-emerald-600 group-hover/item:translate-x-0.5 transition-all mt-1">
                                            chevron_right
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Insights & Documentation -->
                <div class="relative nav-dropdown-group">
                    <a href="{{ route('public.blogs') }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-lg text-[14.5px] font-semibold {{ request()->routeIs('public.blogs', 'public.blog', 'public.documentation') ? 'text-emerald-700 bg-emerald-50/80 dark:text-primary dark:bg-white/5' : 'text-slate-700 dark:text-gray-200 hover:text-emerald-700 hover:bg-slate-50 dark:hover:bg-white/5' }} transition-all">
                        <span>Insights</span>
                        <span class="material-symbols-outlined text-[18px] opacity-75">expand_more</span>
                    </a>
                    <div class="nav-dropdown-panel absolute left-0 top-full pt-2 w-60 z-50">
                        <div class="rounded-2xl border border-slate-200/90 dark:border-gray-800 bg-white dark:bg-slate-900 p-2 shadow-xl shadow-slate-900/10">
                            <a href="{{ route('public.blogs') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">newspaper</span>
                                <span>Regulatory Blog &amp; Updates</span>
                            </a>
                            <a href="{{ route('public.documentation') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-white/5 transition-colors">
                                <span class="material-symbols-outlined text-[19px] text-emerald-600">menu_book</span>
                                <span>Guides &amp; Documentation</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 5. Shop -->
                <a class="px-3.5 py-2 rounded-lg text-[14.5px] font-semibold {{ request()->routeIs('public.shop') ? 'text-emerald-700 bg-emerald-50/80 dark:text-primary dark:bg-white/5' : 'text-slate-700 dark:text-gray-200 hover:text-emerald-700 hover:bg-slate-50 dark:hover:bg-white/5' }} transition-all" href="{{ route('public.shop') }}">
                    Shop
                </a>
            </nav>

            <!-- Right Actions Cluster -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <a class="hidden xl:inline-flex items-center gap-1.5 rounded-full px-3.5 py-2 text-xs font-bold transition-all {{ request()->routeIs('public.adverse-event') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-primary border border-emerald-200 dark:border-emerald-700/50 hover:bg-emerald-600 hover:text-white' }}" href="{{ route('public.adverse-event') }}">
                    <span class="material-symbols-outlined text-[16px]">health_and_safety</span>
                    <span>Report Adverse Event</span>
                </a>

                <a href="{{ route('public.services') }}#consultation" class="hidden sm:inline-flex h-10 items-center justify-center rounded-xl bg-[#0b1d33] hover:bg-emerald-700 text-white px-4 text-xs sm:text-[13px] font-bold tracking-wide shadow-sm transition-all">
                    Request Consultation
                </a>

                @auth
                    <a href="{{ url('/dashboard') }}" class="hidden md:inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 dark:border-gray-700 px-4 text-[13px] font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800 transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 dark:border-gray-700 px-4 text-[13px] font-semibold text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800 transition-all">
                        Login
                    </a>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle Menu" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300">
                    <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="mobileMenuOpen" x-cloak>close</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-slate-200 dark:border-gray-800 bg-white dark:bg-background-dark px-5 py-6 shadow-2xl max-h-[85vh] overflow-y-auto" x-cloak
             @click.away="mobileMenuOpen = false">
            <nav class="flex flex-col gap-2">
                <a class="rounded-xl px-3 py-2.5 text-base font-bold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 dark:bg-white/5 dark:text-primary' : 'text-slate-800 dark:text-gray-100' }}" href="{{ route('home') }}" @click="mobileMenuOpen = false">
                    Home
                </a>

                <!-- Mobile About Accordion (includes Gallery, Team, Experiences, Clients, Careers) -->
                <div class="rounded-xl border border-slate-100 dark:border-gray-800">
                    <button type="button" @click="mobileAboutOpen = !mobileAboutOpen" class="flex w-full items-center justify-between px-3 py-2.5 text-base font-bold text-slate-800 dark:text-gray-100">
                        <span>About &amp; Company</span>
                        <span class="material-symbols-outlined transition-transform" :class="mobileAboutOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="mobileAboutOpen" x-cloak class="border-t border-slate-100 dark:border-gray-800 px-3 py-2 space-y-1 bg-slate-50/60 dark:bg-white/5">
                        <a href="{{ route('public.about') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">About iGEO</a>
                        <a href="{{ route('public.team') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">Leadership &amp; Team</a>
                        <a href="{{ route('public.experiences') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">Track Record &amp; Experience</a>
                        <a href="{{ route('public.clients') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">Clients &amp; Partners</a>
                        <a href="{{ route('public.gallery') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">Media &amp; Project Gallery</a>
                        <a href="{{ route('public.careers') }}" class="block rounded-lg px-2.5 py-2 text-sm font-medium text-slate-700 dark:text-gray-200" @click="mobileMenuOpen = false">Careers</a>
                    </div>
                </div>

                <!-- Mobile Services Drop-Line Accordion -->
                <div class="rounded-xl border border-emerald-200/70 dark:border-emerald-800/50 bg-emerald-50/30 dark:bg-emerald-950/20">
                    <button type="button" @click="mobileServicesOpen = !mobileServicesOpen" class="flex w-full items-center justify-between px-3 py-2.5 text-base font-bold text-slate-900 dark:text-white">
                        <span>Services (8 Core Lines)</span>
                        <span class="material-symbols-outlined transition-transform" :class="mobileServicesOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="mobileServicesOpen" x-cloak class="border-t border-emerald-200/60 dark:border-gray-800 divide-y divide-slate-100 dark:divide-gray-800 bg-white dark:bg-slate-900 rounded-b-xl">
                        <a href="{{ route('public.services') }}" class="block px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider text-emerald-700 dark:text-primary" @click="mobileMenuOpen = false">
                            View All Regulatory Services &rarr;
                        </a>
                        @foreach($navRegulatoryServices as $navService)
                            <a href="{{ route('public.service.detail', ['slug' => $navService['slug']]) }}" class="flex items-start gap-2.5 px-3.5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-200 hover:bg-emerald-50" @click="mobileMenuOpen = false">
                                <span class="text-xs font-extrabold text-emerald-700 mt-0.5">{{ $navService['number'] }}.</span>
                                <span class="leading-snug">{{ $navService['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <a class="rounded-xl px-3 py-2.5 text-base font-bold text-slate-800 dark:text-gray-100" href="{{ route('public.blogs') }}" @click="mobileMenuOpen = false">Regulatory Insights &amp; Blog</a>
                <a class="rounded-xl px-3 py-2.5 text-base font-bold text-slate-800 dark:text-gray-100" href="{{ route('public.documentation') }}" @click="mobileMenuOpen = false">Documentation</a>
                <a class="rounded-xl px-3 py-2.5 text-base font-bold text-slate-800 dark:text-gray-100" href="{{ route('public.shop') }}" @click="mobileMenuOpen = false">Shop</a>

                <div class="pt-3 mt-2 border-t border-slate-200 dark:border-gray-800 flex flex-col gap-2.5">
                    <a class="flex items-center justify-center gap-2 rounded-xl bg-emerald-50 border border-emerald-200 py-3 text-sm font-bold text-emerald-800" href="{{ route('public.adverse-event') }}" @click="mobileMenuOpen = false">
                        <span class="material-symbols-outlined text-lg">health_and_safety</span>
                        <span>Report Adverse Event</span>
                    </a>
                    <a href="{{ route('public.services') }}#consultation" class="flex h-12 items-center justify-center rounded-xl bg-[#0b1d33] text-white text-sm font-bold shadow-sm" @click="mobileMenuOpen = false">
                        Request Consultation
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex h-11 items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-800 text-sm font-semibold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="flex h-11 items-center justify-center rounded-xl bg-slate-100 dark:bg-gray-800 text-sm font-semibold">Login</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="flex-1">
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-[#0b1d33] border-t border-white/10 py-16 px-6 lg:px-16 text-slate-300">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12">
            <!-- Brand Column -->
            <div class="lg:col-span-4 space-y-5">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3.5 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white p-0.5 shadow-md">
                        <img src="{{ asset('Logo-emblem.png?v=20261125b') }}" alt="IGEO Logo" class="h-11 w-11 object-contain">
                    </div>
                    <h2 class="font-heading text-2xl font-extrabold tracking-wider text-white leading-none">IGEO</h2>
                </a>
                <p class="text-sm leading-relaxed text-slate-300/90">
                    Specialist pharmaceutical regulatory affairs, market-entry and quality compliance consultancy supporting manufacturers, MAHs, importers, distributors and healthcare institutions across Tanzania.
                </p>
                <div class="flex flex-col gap-2.5 pt-2">
                    <a href="mailto:info@igeo.co.tz" class="inline-flex items-center gap-2.5 text-sm text-slate-200 hover:text-emerald-400 transition-colors">
                        <span class="material-symbols-outlined text-emerald-400 text-[19px]">mail</span> 
                        <span class="font-medium">info@igeo.co.tz</span>
                    </a>
                    <a href="tel:+255713035438" class="inline-flex items-center gap-2.5 text-sm text-slate-200 hover:text-emerald-400 transition-colors">
                        <span class="material-symbols-outlined text-emerald-400 text-[19px]">call</span> 
                        <span class="font-medium">+255 713 035 438</span>
                    </a>
                </div>
            </div>

            <!-- Core Regulatory Services -->
            <div class="lg:col-span-4">
                <h4 class="font-heading text-white font-bold text-sm uppercase tracking-wider mb-5">Regulatory Service Lines</h4>
                <ul class="space-y-2.5 text-sm">
                    @foreach(array_slice($navRegulatoryServices, 0, 6) as $footerService)
                        <li>
                            <a class="hover:text-emerald-400 transition-colors inline-flex items-start gap-2" href="{{ route('public.service.detail', ['slug' => $footerService['slug']]) }}">
                                <span class="text-emerald-400/80 font-semibold text-xs mt-0.5">{{ $footerService['number'] }}.</span>
                                <span>{{ $footerService['short_name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                    <li class="pt-1">
                        <a class="text-emerald-400 font-semibold hover:underline text-xs uppercase tracking-wider" href="{{ route('public.services') }}">
                            Explore All 8 Service Lines &rarr;
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Company & Media (including Gallery) -->
            <div class="lg:col-span-2">
                <h4 class="font-heading text-white font-bold text-sm uppercase tracking-wider mb-5">Company</h4>
                <ul class="space-y-3 text-sm">
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.about') }}">About Us</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.team') }}">Our Team</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.experiences') }}">Experiences</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.clients') }}">Clients</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.gallery') }}">Media Gallery</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.careers') }}">Careers</a></li>
                </ul>
            </div>

            <!-- Safety & Compliance Actions -->
            <div class="lg:col-span-2">
                <h4 class="font-heading text-white font-bold text-sm uppercase tracking-wider mb-5">Compliance &amp; Safety</h4>
                <ul class="space-y-3.5 text-sm">
                    <li>
                        <a class="inline-flex items-center gap-1.5 font-bold text-emerald-400 hover:underline transition-colors" href="{{ route('public.adverse-event') }}">
                            <span class="material-symbols-outlined text-base">health_and_safety</span>
                            Report Adverse Event
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-emerald-400 transition-colors font-semibold text-white" href="{{ route('public.services') }}#consultation">
                            Request Consultation
                        </a>
                    </li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.documentation') }}">Documentation</a></li>
                    <li><a class="hover:text-emerald-400 transition-colors" href="{{ route('public.blogs') }}">Regulatory Updates</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-14 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
            <p>© {{ date('Y') }} IGEO. All rights reserved.</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a class="text-emerald-400 hover:text-white transition-colors font-semibold" href="{{ route('public.adverse-event') }}">24/7 Pharmacovigilance</a>
                <a class="hover:text-white transition-colors" href="{{ route('public.services') }}">Regulatory Services</a>
                <a class="hover:text-white transition-colors" href="{{ route('public.gallery') }}">Gallery</a>
            </div>
        </div>
    </footer>
</div>
@livewireScripts
</body>
</html>
