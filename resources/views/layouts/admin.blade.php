<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : 'iGEO CMS' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 to-blue-50/30">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-xl shadow-xl border-b border-slate-200/50 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-building text-white text-lg"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">iGEO</h1>
                                    <p class="text-xs text-slate-500 -mt-1">Content Management</p>
                                </div>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}"
                               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.clients') }}"
                               class="nav-link {{ request()->routeIs('admin.clients') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Clients</span>
                            </a>
                            <a href="{{ route('admin.projects') }}"
                               class="nav-link {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
                                <i class="fas fa-project-diagram"></i>
                                <span>Projects</span>
                            </a>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="nav-link flex items-center space-x-1">
                                    <i class="fas fa-cogs"></i>
                                    <span>Content</span>
                                    <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="absolute left-0 mt-2 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 py-2 z-50">
                                    <a href="{{ route('admin.blogs') }}" class="dropdown-link">
                                        <i class="fas fa-blog text-blue-600"></i>
                                        <span>Blog Posts</span>
                                    </a>
                                    <a href="{{ route('admin.services') }}" class="dropdown-link">
                                        <i class="fas fa-tools text-green-600"></i>
                                        <span>Services</span>
                                    </a>
                                    <a href="{{ route('admin.gallery') }}" class="dropdown-link">
                                        <i class="fas fa-images text-purple-600"></i>
                                        <span>Gallery</span>
                                    </a>
                                    <a href="{{ route('admin.documents') }}" class="dropdown-link">
                                        <i class="fas fa-file-alt text-orange-600"></i>
                                        <span>Documents</span>
                                    </a>
                                    <a href="{{ route('admin.experience') }}" class="dropdown-link">
                                        <i class="fas fa-award text-yellow-600"></i>
                                        <span>Experience</span>
                                    </a>
                                </div>
                            </div>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="nav-link flex items-center space-x-1">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Finance</span>
                                    <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="absolute left-0 mt-2 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 py-2 z-50">
                                    <a href="{{ route('admin.income') }}" class="dropdown-link">
                                        <i class="fas fa-money-bill-wave text-green-600"></i>
                                        <span>Income</span>
                                    </a>
                                    <a href="{{ route('admin.expenses') }}" class="dropdown-link">
                                        <i class="fas fa-receipt text-red-600"></i>
                                        <span>Expenses</span>
                                    </a>
                                    <a href="{{ route('admin.finance-dashboard') }}" class="dropdown-link">
                                        <i class="fas fa-chart-pie text-blue-600"></i>
                                        <span>Financial Reports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="flex items-center space-x-3 px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100/80 rounded-xl transition-all duration-200">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 py-2 z-50">
                                <a href="{{ route('profile.edit') }}" class="dropdown-link">
                                    <i class="fas fa-user-edit text-blue-600"></i>
                                    <span>Profile Settings</span>
                                </a>
                                <hr class="border-slate-200 my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-link w-full text-left">
                                        <i class="fas fa-sign-out-alt text-red-600"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                                class="p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 transition-all duration-200">
                            <i class="fas fa-bars text-lg" x-show="!open"></i>
                            <i class="fas fa-times text-lg" x-show="open"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="open" class="sm:hidden bg-white/95 backdrop-blur-xl border-t border-slate-200/50">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.clients') }}" class="mobile-nav-link">
                        <i class="fas fa-users"></i>
                        <span>Clients</span>
                    </a>
                    <a href="{{ route('admin.projects') }}" class="mobile-nav-link">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @isset($header)
            <header class="bg-white/80 backdrop-blur-xl shadow-sm border-b border-slate-200/50">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Global Toast Notifications -->
    <div x-data="{
        notifications: [],
        addNotification(notification) {
            this.notifications.push({
                id: Date.now(),
                ...notification
            });
            setTimeout(() => {
                this.removeNotification(this.notifications[0]?.id);
            }, 5000);
        },
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="addNotification($event.detail)"
    class="fixed top-4 right-4 z-50 space-y-2">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="true"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="max-w-sm w-full bg-white/95 backdrop-blur-xl shadow-2xl rounded-2xl pointer-events-auto ring-1 ring-slate-200/50 overflow-hidden border border-slate-200/50"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                 :class="{
                                     'bg-green-100': notification.type === 'success',
                                     'bg-red-100': notification.type === 'error',
                                     'bg-yellow-100': notification.type === 'warning',
                                     'bg-blue-100': notification.type === 'info'
                                 }">
                                <i :class="{
                                    'fas fa-check text-green-600': notification.type === 'success',
                                    'fas fa-times text-red-600': notification.type === 'error',
                                    'fas fa-exclamation-triangle text-yellow-600': notification.type === 'warning',
                                    'fas fa-info-circle text-blue-600': notification.type === 'info'
                                }"></i>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-semibold text-slate-800" x-text="notification.title"></p>
                            <p class="mt-1 text-sm text-slate-600" x-text="notification.message"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="removeNotification(notification.id)"
                                    class="bg-white rounded-lg inline-flex text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        .nav-link {
            @apply inline-flex items-center space-x-2 px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100/80 rounded-xl transition-all duration-200;
        }

        .nav-link.active {
            @apply bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 border border-blue-200/50;
        }

        .dropdown-link {
            @apply flex items-center space-x-3 px-4 py-3 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100/80 transition-all duration-200;
        }

        .mobile-nav-link {
            @apply flex items-center space-x-3 px-3 py-2 text-base font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100/80 rounded-xl transition-all duration-200;
        }
    </style>

    @livewireScripts

    <!-- Alpine.js is included with Livewire - no need for separate CDN -->
</body>
</html>
