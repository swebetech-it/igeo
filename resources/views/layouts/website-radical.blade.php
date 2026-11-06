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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/website-radical.css'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
</head>
<body>
    <!-- Cyberpunk Header -->
    <header class="cyber-header">
        <a href="{{ route('home') }}" class="cyber-logo">
            <img src="{{ asset('Logo.png') }}" alt="iGEO Pharmacy & Consulting Logo" style="height: 50px; width: auto;">
        </a>
        
        <nav class="cyber-nav">
            <a href="{{ route('home') }}" class="cyber-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            @if($services && $services->count() > 0)
                <div class="cyber-dropdown">
                    <a href="#services" class="cyber-link">Services <i class="fas fa-chevron-down"></i></a>
                    <div class="cyber-dropdown-menu">
                        @foreach($services as $service)
                            <a href="#service-{{ Str::slug($service->name) }}" class="cyber-link">{{ $service->name }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="#services" class="cyber-link">Services</a>
            @endif
            <a href="{{ route('public.clients') }}" class="cyber-link {{ request()->routeIs('public.clients') ? 'active' : '' }}">Clients</a>
            <a href="{{ route('public.gallery') }}" class="cyber-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}">Portfolio</a>
            <a href="{{ route('public.experiences') }}" class="cyber-link {{ request()->routeIs('public.experiences') ? 'active' : '' }}">Milestones</a>
            <a href="{{ route('public.blogs') }}" class="cyber-link {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'active' : '' }}">Insights</a>
            <a href="{{ route('public.documentation') }}" class="cyber-link {{ request()->routeIs('public.documentation') ? 'active' : '' }}">Docs</a>
            <a href="{{ route('public.careers') }}" class="cyber-link {{ request()->routeIs('public.careers') ? 'active' : '' }}">Careers</a>
            <a href="{{ route('public.shop') }}" class="cyber-link {{ request()->routeIs('public.shop') ? 'active' : '' }}">Shop</a>
            <a href="#contact" class="cyber-link">Contact</a>
        </nav>
        
        <a href="{{ route('login') }}" class="cyber-btn cyber-btn-secondary">
            <i class="fas fa-user-lock mr-2"></i> CLIENT LOGIN
        </a>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-radical">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-heading">iGEO Pharmacy & Consulting</h3>
                    <p>Next-gen pharmaceutical distribution, healthcare systems, and global consulting for the digital age.</p>
                    <div class="social-links-radical">
                        <a href="#" class="social-link-radical"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link-radical"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link-radical"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link-radical"><i class="fab fa-instagram"></i></a>
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
            
            <div class="copyright-radical">
                <p>&copy; {{ date('Y') }} iGEO PHARMACY & CONSULTING. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @vite(['resources/js/app.js'])
</body>
</html>