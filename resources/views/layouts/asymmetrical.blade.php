<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'iGEO Pharmacy & Consulting - Software Development & Tech Services' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/asymmetrical-layout.css'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
</head>
<body>
    <!-- Asymmetrical Header -->
    <header class="asym-header" x-data="{ mobileMenuOpen: false }">
        <a href="{{ route('home') }}" class="asym-logo">
            <img src="{{ asset('Logo.png') }}" alt="iGEO Pharmacy & Consulting Logo" style="height: 50px; width: auto;">
        </a>
        
        <nav class="asym-nav">
            <a href="{{ route('home') }}" class="asym-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <div class="dropdown">
                <a href="#services" class="asym-link">Services</a>
                <div class="dropdown-content">
                    @foreach($services as $service)
                        <a href="#service-{{ Str::slug($service->name) }}" class="dropdown-link">{{ $service->name }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('public.clients') }}" class="asym-link {{ request()->routeIs('public.clients') ? 'active' : '' }}">Clients</a>
            <a href="{{ route('public.gallery') }}" class="asym-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}">Portfolio</a>
            <a href="{{ route('public.experiences') }}" class="asym-link {{ request()->routeIs('public.experiences') ? 'active' : '' }}">Milestones</a>
            <a href="{{ route('public.blogs') }}" class="asym-link {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'active' : '' }}">Insights</a>
            <a href="{{ route('public.documentation') }}" class="asym-link {{ request()->routeIs('public.documentation') ? 'active' : '' }}">Documentation</a>
            <a href="#contact" class="asym-link">Contact</a>
        </nav>
        
        <div class="header-actions-asym">
            <a href="{{ route('login') }}" class="asym-btn hidden-mobile">
                <i class="fas fa-user mr-2"></i> CLIENT LOGIN
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="asym-toggle-btn">
                <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>

        <!-- Mobile Menu Asym -->
        <div x-show="mobileMenuOpen" 
             style="display: none;" 
             class="asym-mobile-menu"
             x-transition:enter="transition-all duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             @click.away="mobileMenuOpen = false">
            <nav class="asym-mobile-nav">
                <a href="{{ route('home') }}" class="mobile-asym-link {{ request()->routeIs('home') ? 'active' : '' }}" @click="mobileMenuOpen = false">Home</a>
                <a href="{{ route('public.about') }}" class="mobile-asym-link {{ request()->routeIs('public.about') ? 'active' : '' }}" @click="mobileMenuOpen = false">About</a>
                <a href="{{ route('public.clients') }}" class="mobile-asym-link {{ request()->routeIs('public.clients') ? 'active' : '' }}" @click="mobileMenuOpen = false">Clients</a>
                <a href="{{ route('public.gallery') }}" class="mobile-asym-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}" @click="mobileMenuOpen = false">Portfolio</a>
                <a href="{{ route('public.experiences') }}" class="mobile-asym-link {{ request()->routeIs('public.experiences') ? 'active' : '' }}" @click="mobileMenuOpen = false">Milestones</a>
                <a href="{{ route('public.blogs') }}" class="mobile-asym-link {{ request()->routeIs('public.blogs') ? 'active' : '' }}" @click="mobileMenuOpen = false">Insights</a>
                <a href="{{ route('public.documentation') }}" class="mobile-asym-link {{ request()->routeIs('public.documentation') ? 'active' : '' }}" @click="mobileMenuOpen = false">Docs</a>
                <a href="{{ route('public.careers') }}" class="mobile-asym-link {{ request()->routeIs('public.careers') ? 'active' : '' }}" @click="mobileMenuOpen = false">Careers</a>
                <a href="{{ route('login') }}" class="mobile-asym-btn" @click="mobileMenuOpen = false">LOGIN</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-asym">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-heading">iGEO Pharmacy & Consulting</h3>
                    <p>Professional pharmaceutical distribution, healthcare consulting, and medical supply services with quality and reliability.</p>
                    <div class="social-links-asym">
                        <a href="#" class="social-link-asym"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link-asym"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link-asym"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link-asym"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">OUR SERVICES</h3>
                    <ul class="footer-links">
                        @if($services && $services->count() > 0)
                            @foreach($services as $service)
                                <li><a href="#service-{{ Str::slug($service->name) }}">{{ $service->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="#services">Software Development</a></li>
                            <li><a href="#services">Tech Device Maintenance</a></li>
                            <li><a href="#services">GPS Tracking</a></li>
                            <li><a href="#services">Barcode Printing</a></li>
                            <li><a href="#services">CCTV Installation</a></li>
                            <li><a href="#services">Motorgate Supply & Installation</a></li>
                        @endif
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">RESOURCES</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.clients') }}">Our Clients</a></li>
                        <li><a href="{{ route('public.gallery') }}">Project Portfolio</a></li>
                        <li><a href="{{ route('public.experiences') }}">Company Milestones</a></li>
                        <li><a href="{{ route('public.blogs') }}">Industry Insights</a></li>
                        <li><a href="{{ route('public.documentation') }}">Technical Docs</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">CONTACT US</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone mr-2"></i> 0738261240 / 0782340299</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@igeo.co.tz</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Ubungo-Dar Es Salaam, Tanzania</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright-asym">
                <p>&copy; {{ date('Y') }} iGEO Pharmacy & Consulting. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @vite(['resources/js/app.js'])
</body>
</html>