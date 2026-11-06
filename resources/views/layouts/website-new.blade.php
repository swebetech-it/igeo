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
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/website-new.css'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('Logo.png') }}" alt="iGEO Pharmacy & Consulting Logo" style="height: 50px; width: auto;">
            </a>
            
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                @if($services && $services->count() > 0)
                    <li class="dropdown">
                        <a href="#services" class="nav-link">Services <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($services as $service)
                                <li><a href="#service-{{ Str::slug($service->name) }}" class="nav-link">{{ $service->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a href="#services" class="nav-link">Services</a></li>
                @endif
                <li><a href="{{ route('public.clients') }}" class="nav-link {{ request()->routeIs('public.clients') ? 'active' : '' }}">Clients</a></li>
                <li><a href="{{ route('public.gallery') }}" class="nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}">Gallery</a></li>
                <li><a href="{{ route('public.experiences') }}" class="nav-link {{ request()->routeIs('public.experiences') ? 'active' : '' }}">Milestones</a></li>
                <li><a href="{{ route('public.blogs') }}" class="nav-link {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'active' : '' }}">Insights</a></li>
                <li><a href="{{ route('public.documentation') }}" class="nav-link {{ request()->routeIs('public.documentation') ? 'active' : '' }}">Docs</a></li>
                <li><a href="{{ route('public.careers') }}" class="nav-link {{ request()->routeIs('public.careers') ? 'active' : '' }}">Careers</a></li>
                <li><a href="{{ route('public.shop') }}" class="nav-link {{ request()->routeIs('public.shop') ? 'active' : '' }}">Shop</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li><a href="{{ route('login') }}" class="btn btn-outline">Client Login</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-heading">iGEO Pharmacy & Consulting</h3>
                    <p>Professional pharmaceutical distribution, healthcare consulting, and supply chain excellence with quality and reliability.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">Services</h3>
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
                    <h3 class="footer-heading">Resources</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.clients') }}">Our Clients</a></li>
                        <li><a href="{{ route('public.gallery') }}">Project Portfolio</a></li>
                        <li><a href="{{ route('public.experiences') }}">Company Milestones</a></li>
                        <li><a href="{{ route('public.blogs') }}">Industry Insights</a></li>
                        <li><a href="{{ route('public.documentation') }}">Technical Docs</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">Contact</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone mr-2"></i> 0738261240 / 0782340299</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@igeo.co.tz</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Ubungo-Dar Es Salaam, Tanzania</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; {{ date('Y') }} iGEO Pharmacy & Consulting. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.innerHTML = navMenu.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });
        
        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    
    @livewireScripts
    @vite(['resources/js/app.js'])
</body>
</html>