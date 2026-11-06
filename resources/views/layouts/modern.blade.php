<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'iGEO - Architecting the Future' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/modern-landing.css'])
    
    @livewireStyles
</head>
<body>
    <!-- Modern Header -->
    <header class="modern-header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="modern-logo">
                <img src="{{ asset('Logo.png') }}" alt="iGEO Logo">
                <span>iGEO</span>
            </a>
            
            <nav class="modern-nav" id="mainNav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('public.about') }}" class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">About</a>
                <a href="{{ route('public.team') }}" class="nav-link {{ request()->routeIs('public.team') ? 'active' : '' }}">Team</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="{{ route('public.clients') }}" class="nav-link {{ request()->routeIs('public.clients') ? 'active' : '' }}">Clients</a>
                <a href="{{ route('public.gallery') }}" class="nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}">Gallery</a>
                <a href="{{ route('public.experiences') }}" class="nav-link {{ request()->routeIs('public.experiences') ? 'active' : '' }}">Experiences</a>
                <a href="{{ route('public.blogs') }}" class="nav-link {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('public.documentation') }}" class="nav-link {{ request()->routeIs('public.documentation') ? 'active' : '' }}">Documentation</a>
                <a href="{{ route('public.careers') }}" class="nav-link {{ request()->routeIs('public.careers') ? 'active' : '' }}">Careers</a>
                <a href="{{ route('public.shop') }}" class="nav-link {{ request()->routeIs('public.shop') ? 'active' : '' }}">Shop</a>
                <a href="{{ route('public.adverse-event') }}" class="nav-link {{ request()->routeIs('public.adverse-event') ? 'active' : '' }}">Report Adverse Event</a>
                <a href="#contact" class="nav-link">Contact</a>
            </nav>
            
            <div class="header-actions">
                <a href="{{ route('login') }}" class="modern-btn-outline">
                    <i class="fas fa-user"></i> Login
                </a>
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="modern-main">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="modern-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-heading">iGEO</h3>
                    <p>Leading pharmaceutical distribution and healthcare consulting services in Tanzania.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('public.about') }}">About</a></li>
                        <li><a href="{{ route('public.team') }}">Team</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="{{ route('public.clients') }}">Clients</a></li>
                        <li><a href="{{ route('public.gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('public.experiences') }}">Experiences</a></li>
                        <li><a href="{{ route('public.blogs') }}">Blog</a></li>
                        <li><a href="{{ route('public.documentation') }}">Documentation</a></li>
                        <li><a href="{{ route('public.careers') }}">Careers</a></li>
                        <li><a href="{{ route('public.shop') }}">Shop</a></li>
                        <li><a href="{{ route('public.adverse-event') }}">Report Adverse Event</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">Services</h3>
                    <ul class="footer-links">
                        <li><a href="#services">Software Development</a></li>
                        <li><a href="#services">Cloud Architecture</a></li>
                        <li><a href="#services">Cybersecurity</a></li>
                        <li><a href="#services">AI & Intelligence</a></li>
                        <li><a href="#services">Tech Consulting</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-heading">Contact</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone"></i> 0738261240 / 0782340299</li>
                        <li><i class="fas fa-envelope"></i> info@igeo.co.tz</li>
                        <li><i class="fas fa-map-marker-alt"></i> Dar es Salaam, Tanzania</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} iGEO. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('public.gallery') }}" class="bottom-nav-item {{ request()->routeIs('public.gallery') ? 'active' : '' }}">
            <i class="fas fa-images"></i>
            <span>Gallery</span>
        </a>
        <a href="{{ route('public.blogs') }}" class="bottom-nav-item {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'active' : '' }}">
            <i class="fas fa-blog"></i>
            <span>Blog</span>
        </a>
        <a href="{{ route('login') }}" class="bottom-nav-item">
            <i class="fas fa-user"></i>
            <span>Login</span>
        </a>
    </nav>

    @livewireScripts
    @vite(['resources/js/app.js'])
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            document.getElementById('mainNav')?.classList.toggle('active');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
