<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'iGEO Pharmacy & Consulting - Leading Pharma Distribution & Consulting' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Alpine.js is included with Livewire - no need for separate CDN -->

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navigation Header -->
    <header x-data="{
        isOpen: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 50;
            });
        }
    }"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-white/90 backdrop-blur-sm'"
    class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 animate-fade-in-up">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('Logo.png') }}" alt="iGEO Pharmacy & Consulting Logo" class="h-10 w-auto">
                        <span class="text-xl md:text-2xl font-bold text-gray-900">iGEO Pharmacy & Consulting</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8 animate-fade-in-up" style="animation-delay: 0.2s">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">Home</a>
                    @if($services && $services->count() > 0)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 flex items-center">
                                Services <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute left-0 mt-2 w-64 bg-white shadow-lg rounded-md overflow-hidden z-50">
                                @foreach($services as $service)
                                    <a href="#service-{{ Str::slug($service->name) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200">{{ $service->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route('home') }}#services" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">Services</a>
                    @endif
                    <a href="{{ route('public.clients') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('public.clients') ? 'text-blue-600 font-semibold' : '' }}">Clients</a>
                    <a href="{{ route('public.gallery') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('public.gallery') ? 'text-blue-600 font-semibold' : '' }}">Gallery</a>
                    <a href="{{ route('public.experiences') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('public.experiences') ? 'text-blue-600 font-semibold' : '' }}">Experiences</a>
                    <a href="{{ route('public.blogs') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'text-blue-600 font-semibold' : '' }}">Blog</a>
                    <a href="{{ route('public.documentation') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('public.documentation') ? 'text-blue-600 font-semibold' : '' }}">Documentation</a>
                    <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">Contact</a>
                </nav>

                <!-- Login Button & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Login Button -->
                    <a href="{{ route('login') }}"
                       class="animate-fade-in-up px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg"
                       style="animation-delay: 0.4s">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>

                    <!-- Mobile Menu Button -->
                    <button @click="isOpen = !isOpen"
                            class="md:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="md:hidden bg-white/95 backdrop-blur-md border-t border-gray-200">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">Home</a>
                @if($services && $services->count() > 0)
                    <div x-data="{ open: false }" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">
                        <button @click="open = !open; isOpen = true" class="w-full flex justify-between items-center">
                            Services <i class="fas fa-chevron-down ml-1" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" class="ml-4 mt-2 space-y-1">
                            @foreach($services as $service)
                                <a href="#service-{{ Str::slug($service->name) }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200">{{ $service->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('home') }}#services" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Services</a>
                @endif
                <a href="{{ route('public.clients') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('public.clients') ? 'text-blue-600 font-semibold' : '' }}">Clients</a>
                <a href="{{ route('public.gallery') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('public.gallery') ? 'text-blue-600 font-semibold' : '' }}">Gallery</a>
                <a href="{{ route('public.experiences') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('public.experiences') ? 'text-blue-600 font-semibold' : '' }}">Experiences</a>
                <a href="{{ route('public.blogs') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('public.blogs') || request()->routeIs('public.blog') ? 'text-blue-600 font-semibold' : '' }}">Blog</a>
                <a href="{{ route('public.documentation') }}" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('public.documentation') ? 'text-blue-600 font-semibold' : '' }}">Documentation</a>
                <a href="{{ route('home') }}#contact" @click="isOpen = false" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Contact</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('Logo.png') }}" alt="iGEO Pharmacy & Consulting Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold text-white">iGEO Pharmacy & Consulting</span>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        iGEO Pharmacy & Consulting is a high-performance pharmaceutical distribution and healthcare consulting firm dedicated to driving revenue and excellence in the pharma industry.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        @if($services && $services->count() > 0)
                            @foreach($services as $service)
                                <li><a href="#service-{{ Str::slug($service->name) }}" class="text-gray-400 hover:text-white transition-colors duration-200">{{ $service->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">Software Development</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">Tech Device Maintenance</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">GPS Tracking</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">Barcode Printing</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">CCTV Installation</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition-colors duration-200">Motorgate Supply & Installation</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('public.clients') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Our Clients</a></li>
                        <li><a href="{{ route('public.gallery') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Project Portfolio</a></li>
                        <li><a href="{{ route('public.experiences') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Company Milestones</a></li>
                        <li><a href="{{ route('public.blogs') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Industry Insights</a></li>
                        <li><a href="{{ route('public.documentation') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Technical Docs</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-phone mr-2"></i>
                            <span>0738261240 / 0782340299</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>info@igeo.co.tz</span>
                        </li>
                        <li class="flex items-start text-gray-400">
                            <i class="fas fa-map-marker-alt mr-2 mt-1"></i>
                            <span>Ubungo-Dar Es Salaam, Tanzania</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} iGEO Pharmacy & Consulting. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div x-data="{
        show: false,
        init() {
            window.addEventListener('scroll', () => {
                this.show = window.scrollY > 300;
            });
        }
    }" x-show="show" x-transition class="fixed bottom-4 right-4 z-40">
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transform hover:scale-110 transition-all duration-200 flex items-center justify-center">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    @livewireScripts
</body>
</html>
