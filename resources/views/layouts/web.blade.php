<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <!-- Character Encoding & Viewport -->
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    
    <!-- SEO Meta Tags -->
    <title>{{ config('app.name', 'iGEO PHARMACY & CONSULTING') }} | @yield('title', 'Agribusiness & Pharmacy Solutions')</title>
    <meta name="description" content="@yield('meta_description', 'iGEO Pharmacy & Consulting - Leading pharmaceutical and agribusiness solutions provider in Tanzania')"/>
    <meta name="keywords" content="@yield('meta_keywords', 'pharmacy, consulting, agribusiness, healthcare, solutions, Tanzania')"/>
    <meta name="author" content="{{ config('app.name') }}"/>
    <meta name="robots" content="index, follow"/>
    <link rel="canonical" href="{{ url()->current() }}"/>
    
    <!-- Open Graph Meta Tags for Social Sharing -->
    <meta property="og:site_name" content="{{ config('app.name') }}"/>
    <meta property="og:title" content="@yield('og_title', config('app.name'))"/>
    <meta property="og:description" content="@yield('og_description', 'iGEO Pharmacy & Consulting - Leading pharmaceutical and agribusiness solutions provider in Tanzania')"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="@yield('og_type', 'website')"/>
    <meta property="og:image" content="@yield('og_image', asset('Logo.png'))"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name'))"/>
    <meta name="twitter:description" content="@yield('twitter_description', 'iGEO Pharmacy & Consulting')"/>
    <meta name="twitter:image" content="@yield('twitter_image', asset('Logo.png'))"/>
    
    <!-- Additional SEO Tags -->
    <meta name="language" content="English"/>
    <meta name="country" content="Tanzania"/>
    <meta name="theme-color" content="#13ec13"/>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"/>
    
    <!-- Structured Data -->
    @hasSection('structured_data')
        @yield('structured_data')
    @else
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "{{ config('app.name') }}",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('Logo.png') }}",
            "description": "iGEO Pharmacy & Consulting - Leading pharmaceutical and agribusiness solutions provider",
            "sameAs": [
                "https://www.facebook.com/igeo",
                "https://www.twitter.com/igeo",
                "https://www.linkedin.com/company/igeo",
                "https://www.instagram.com/igeo"
            ],
            "contactPoint": {
                "@@type": "ContactPoint",
                "contactType": "Customer Service",
                "telephone": "{{ env('CONTACT_PHONE', '+255123456789') }}",
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "slate-custom": "#4a5568",
                        "teal-custom": "#14b8a6",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
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
        .split-hero-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 80vh;
        }
        .data-grid-line {
            background-image: linear-gradient(to right, #dbe6db 1px, transparent 1px), linear-gradient(to bottom, #dbe6db 1px, transparent 1px);
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
            transition: all 0.4s ease-in-out;
            overflow: hidden;
        }
        [x-cloak] { display: none !important; }
        @yield('styles')
    </style>
    @livewireStyles
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-gray-100 selection:bg-primary/30">
<div class="relative flex min-h-screen flex-col overflow-x-hidden">
    <!-- Navigation -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 w-full border-b border-gray-200/50 dark:border-gray-800/50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-6 lg:px-20">
        <div class="mx-auto flex max-w-7xl items-center justify-between py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group cursor-pointer">
                <img src="{{ asset('Logo.png') }}" alt="iGEO Logo" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                <div class="hidden sm:block">
                    <h2 class="text-xl font-black tracking-tighter text-background-dark dark:text-white leading-tight">iGEO</h2>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">Pharmacy & Consulting</p>
                </div>
            </a>
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                <a class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('home') }}">Home</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.about') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.about') }}">About</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.gallery') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.gallery') }}">Gallery</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.experiences') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.experiences') }}">Experiences</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.blogs') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.blogs') }}">Blog</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.documentation') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.documentation') }}">Documentation</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.clients') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.clients') }}">Clients</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.careers') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.careers') }}">Careers</a>
                <a class="text-sm font-semibold {{ request()->routeIs('public.shop') ? 'text-primary' : 'hover:text-primary' }} transition-colors" href="{{ route('public.shop') }}">Shop</a>
                <a class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-extrabold transition-all {{ request()->routeIs('public.adverse-event') ? 'bg-primary text-background-dark shadow-sm' : 'bg-emerald-500/10 text-emerald-800 dark:text-primary border border-emerald-500/30 hover:bg-primary hover:text-background-dark' }}" href="{{ route('public.adverse-event') }}">
                    <span class="material-symbols-outlined text-sm">health_and_safety</span>
                    <span>Report Adverse Event</span>
                </a>
            </nav>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="hidden sm:flex h-10 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-6 text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:flex h-10 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-700 px-6 text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        Login
                    </a>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-slate-600 dark:text-gray-400">
                    <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="mobileMenuOpen">close</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden absolute top-full left-0 right-0 bg-white dark:bg-background-dark border-b border-gray-200 dark:border-gray-800 p-6 shadow-xl" x-cloak
             @click.away="mobileMenuOpen = false">
            <nav class="flex flex-col gap-4">
                <a class="text-lg font-bold {{ request()->routeIs('home') ? 'text-primary' : '' }}" href="{{ route('home') }}" @click="mobileMenuOpen = false">Home</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.about') ? 'text-primary' : '' }}" href="{{ route('public.about') }}" @click="mobileMenuOpen = false">About</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.gallery') ? 'text-primary' : '' }}" href="{{ route('public.gallery') }}" @click="mobileMenuOpen = false">Gallery</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.experiences') ? 'text-primary' : '' }}" href="{{ route('public.experiences') }}" @click="mobileMenuOpen = false">Experiences</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.blogs') ? 'text-primary' : '' }}" href="{{ route('public.blogs') }}" @click="mobileMenuOpen = false">Blog</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.documentation') ? 'text-primary' : '' }}" href="{{ route('public.documentation') }}" @click="mobileMenuOpen = false">Documentation</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.clients') ? 'text-primary' : '' }}" href="{{ route('public.clients') }}" @click="mobileMenuOpen = false">Clients</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.careers') ? 'text-primary' : '' }}" href="{{ route('public.careers') }}" @click="mobileMenuOpen = false">Careers</a>
                <a class="text-lg font-bold {{ request()->routeIs('public.shop') ? 'text-primary' : '' }}" href="{{ route('public.shop') }}" @click="mobileMenuOpen = false">Shop</a>
                <a class="inline-flex items-center gap-2 text-lg font-extrabold text-emerald-700 dark:text-primary" href="{{ route('public.adverse-event') }}" @click="mobileMenuOpen = false">
                    <span class="material-symbols-outlined text-xl">health_and_safety</span>
                    <span>Report Adverse Event</span>
                </a>
                <hr class="border-gray-100 dark:border-gray-800 my-2">
                <a href="{{ route('login') }}" class="flex h-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-sm font-bold">Login</a>
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
    <footer class="bg-background-dark border-t border-white/5 py-16 px-6 lg:px-20 text-gray-400">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="space-y-6">
                <div class="flex items-center gap-3 group cursor-pointer mb-6">
                    <img src="{{ asset('Logo.png') }}" alt="iGEO Logo" class="h-10 w-auto object-contain">
                    <h2 class="text-lg font-black tracking-tighter text-white">iGEO PHARMACY & CONSULTING</h2>
                </div>
                <p class="text-sm leading-relaxed">
                    Redefining the global supply chain from seed to shelf. Sustainable health through precision agriculture and pharmaceutical excellence.
                </p>
                <div class="flex flex-col gap-2 pt-4">
                    <a href="mailto:info@igeo.co.tz" class="flex items-center gap-2 text-sm hover:text-primary transition-colors group/link">
                        <span class="material-symbols-outlined text-primary group-hover/link:scale-110 transition-transform">mail</span> 
                        <span class="font-medium">info@igeo.co.tz</span>
                    </a>
                    <a href="tel:+255713035438" class="flex items-center gap-2 text-sm hover:text-primary transition-colors group/link">
                        <span class="material-symbols-outlined text-primary group-hover/link:scale-110 transition-transform">call</span> 
                        <span class="font-medium">+255 713 035 438</span>
                    </a>
                </div>
                <div class="flex gap-4">
                    <a class="h-10 w-10 flex items-center justify-center rounded-lg bg-white/5 hover:bg-primary/20 hover:text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-lg">public</span>
                    </a>
                    <a class="h-10 w-10 flex items-center justify-center rounded-lg bg-white/5 hover:bg-primary/20 hover:text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-lg">contact_mail</span>
                    </a>
                    <a class="h-10 w-10 flex items-center justify-center rounded-lg bg-white/5 hover:bg-primary/20 hover:text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-lg">science</span>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Solutions</h4>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="#">Precision Agriculture</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Pharma Logistics</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Sustainable Consulting</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Supply Chain Tech</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Company</h4>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('public.about') }}">About Us</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Global Impact</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Sustainability Reports</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('public.careers') }}">Career Hub</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Support</h4>
                <ul class="space-y-4 text-sm">
                    <li>
                        <a class="inline-flex items-center gap-1.5 font-bold text-primary hover:underline transition-colors" href="{{ route('public.adverse-event') }}">
                            <span class="material-symbols-outlined text-base">health_and_safety</span>
                            Report Adverse Event
                        </a>
                    </li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('public.documentation') }}">Documentation</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">API References</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Contact Support</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Legal &amp; Privacy</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium uppercase tracking-widest">
            <p>© {{ date('Y') }} {{ config('app.name', 'iGEO PHARMACY & CONSULTING') }}. All rights reserved.</p>
            <div class="flex gap-8">
                <a class="text-primary hover:text-white transition-colors" href="{{ route('public.adverse-event') }}">Pharmacovigilance</a>
                <a class="hover:text-white transition-colors" href="#">Terms of Service</a>
                <a class="hover:text-white transition-colors" href="#">Privacy Policy</a>
                <a class="hover:text-white transition-colors" href="#">Cookies</a>
            </div>
        </div>
    </footer>
    {{-- @livewire('ai-assistant') --}}{{-- TODO: Create AiAssistant Livewire component --}}
</div>
@livewireScripts
</body>
</html>
