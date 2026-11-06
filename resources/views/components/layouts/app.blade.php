<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            .font-sans { font-family: 'Inter', system-ui, sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <div class="flex items-center">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-2">
                                    <i class="fas fa-hard-hat text-white text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h2 class="text-lg font-semibold text-gray-900">DSS CMS</h2>
                                    <p class="text-xs text-gray-500">Admin Panel</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex-grow flex flex-col">
                            <nav class="flex-1 px-2 space-y-1">
                                <!-- Dashboard -->
                                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                    Dashboard
                                </a>

                                <!-- Finance Section -->
                                <div class="pt-6">
                                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Finance</h3>
                                    <div class="mt-2 space-y-1">
                                        <a href="{{ route('admin.finance.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-chart-line mr-3 {{ request()->routeIs('admin.finance.dashboard') ? 'text-green-500' : 'text-gray-400' }}"></i>
                                            P&L Dashboard
                                        </a>
                                        <a href="{{ route('admin.finance.projects') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.projects') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-project-diagram mr-3 {{ request()->routeIs('admin.finance.projects') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                            Projects
                                        </a>
                                        <a href="{{ route('admin.finance.expenses') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.expenses') ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-receipt mr-3 {{ request()->routeIs('admin.finance.expenses') ? 'text-red-500' : 'text-gray-400' }}"></i>
                                            Expenses
                                        </a>
                                        <a href="{{ route('admin.finance.income') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.income') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-money-bill-wave mr-3 {{ request()->routeIs('admin.finance.income') ? 'text-emerald-500' : 'text-gray-400' }}"></i>
                                            Income
                                        </a>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="pt-6">
                                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Content</h3>
                                    <div class="mt-2 space-y-1">
                                        <a href="{{ route('admin.clients') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.clients') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-users mr-3 {{ request()->routeIs('admin.clients') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                            Clients
                                        </a>
                                        <a href="{{ route('admin.services') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.services') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-cogs mr-3 {{ request()->routeIs('admin.services') ? 'text-green-500' : 'text-gray-400' }}"></i>
                                            Services
                                        </a>
                                        <a href="{{ route('admin.gallery') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.gallery') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-images mr-3 {{ request()->routeIs('admin.gallery') ? 'text-purple-500' : 'text-gray-400' }}"></i>
                                            Gallery
                                        </a>
                                        <a href="{{ route('admin.blogs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.blogs') ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-blog mr-3 {{ request()->routeIs('admin.blogs') ? 'text-red-500' : 'text-gray-400' }}"></i>
                                            Blog
                                        </a>
                                        <a href="{{ route('admin.experience') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.experience') ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-award mr-3 {{ request()->routeIs('admin.experience') ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                                            Experience
                                        </a>
                                        <a href="{{ route('admin.documents') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.documents') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-file-alt mr-3 {{ request()->routeIs('admin.documents') ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                                            Documents
                                        </a>
                                        <a href="{{ route('admin.analytics') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-teal-100 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-chart-area mr-3 {{ request()->routeIs('admin.analytics') ? 'text-teal-500' : 'text-gray-400' }}"></i>
                                            Analytics
                                        </a>
                                        <a href="{{ route('admin.contracts') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.contracts') ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-file-signature mr-3 {{ request()->routeIs('admin.contracts') ? 'text-orange-500' : 'text-gray-400' }}"></i>
                                            Contracts
                                        </a>
                                    </div>
                                </div>

                                <!-- Shop & AI Section -->
                                <div class="pt-6">
                                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Shop & AI</h3>
                                    <div class="mt-2 space-y-1">
                                        <a href="{{ route('admin.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-box-open mr-3 {{ request()->routeIs('admin.products') ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                                            Inventory
                                        </a>
                                        <a href="{{ route('admin.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-shopping-cart mr-3 {{ request()->routeIs('admin.orders') ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                                            Orders
                                        </a>
                                        <a href="{{ route('admin.leads') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leads') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-robot mr-3 {{ request()->routeIs('admin.leads') ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                                            AI Leads
                                        </a>
                                        <a href="{{ route('admin.adverse-events') }}" class="group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.adverse-events') ? 'bg-emerald-100 text-emerald-800' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <span class="flex items-center">
                                                <i class="fas fa-shield-virus mr-3 {{ request()->routeIs('admin.adverse-events') ? 'text-emerald-600' : 'text-gray-400' }}"></i>
                                                Adverse Events
                                            </span>
                                            @php
                                                $newAeCount = \Illuminate\Support\Facades\Schema::hasTable('adverse_event_reports')
                                                    ? \App\Models\AdverseEventReport::where('status', 'new')->count()
                                                    : 0;
                                            @endphp
                                            @if($newAeCount > 0)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ $newAeCount }}</span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <!-- User Profile Section -->
                        @auth
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <a href="{{ route('profile.edit') }}" class="flex-shrink-0 w-full group block">
                                <div class="flex items-center">
                                    <div class="bg-gray-300 rounded-full p-2">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile sidebar -->
            <div x-show="sidebarOpen" class="lg:hidden fixed inset-0 flex z-40" x-cloak>
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <!-- Mobile menu content (same as desktop sidebar) -->
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <!-- Same navigation as desktop -->
                        <nav class="px-3 space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.adverse-events') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-emerald-800 bg-emerald-50 hover:bg-emerald-100">
                                <i class="fas fa-shield-virus mr-3 text-emerald-600"></i> Adverse Events
                            </a>
                            <a href="{{ route('admin.products') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-box-open mr-3 text-gray-400"></i> Inventory
                            </a>
                            <a href="{{ route('admin.orders') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-shopping-cart mr-3 text-gray-400"></i> Orders
                            </a>
                            <a href="{{ route('admin.leads') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-robot mr-3 text-gray-400"></i> AI Leads
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <!-- Top navigation -->
                <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                    <button @click="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="flex-1 px-4 flex justify-between">
                        <div class="flex-1 flex">
                            <!-- Breadcrumb or page title -->
                            <div class="flex items-center">
                                @if(isset($breadcrumbs))
                                    <nav class="flex" aria-label="Breadcrumb">
                                        <ol class="flex items-center space-x-4">
                                            {{ $breadcrumbs }}
                                        </ol>
                                    </nav>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4 flex items-center lg:ml-6">
                            <!-- View Website -->
                            <a href="{{ route('home') }}" class="bg-white p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-external-link-alt"></i>
                            </a>

                            @auth
                            <!-- Profile dropdown -->
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <div class="bg-gray-300 rounded-full p-2">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </button>
                                </div>
                                <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Toast Notifications -->
        <x-toast />
    </body>
</html>
