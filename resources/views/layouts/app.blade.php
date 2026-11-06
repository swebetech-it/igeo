<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased dark:bg-gray-900 transition-colors duration-200">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
            <!-- Dark Mode Toggle FAB -->
            <button @click="darkMode = !darkMode" 
                    class="fixed bottom-6 right-6 w-14 h-14 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200 flex items-center justify-center z-40">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-xl"></i>
            </button>
        </div>

        <!-- Toast Notification Container -->
        <div x-data="toastManager()"
             @show-toast.window="showToast($event.detail)"
             class="fixed top-4 right-4 z-50 space-y-2">
            <template x-for="(toast, index) in toasts" :key="toast.id">
                <div x-show="toast.visible"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="max-w-sm w-full shadow-lg rounded-xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                     :class="{
                         'bg-green-50 border-green-200': toast.type === 'success',
                         'bg-red-50 border-red-200': toast.type === 'error',
                         'bg-yellow-50 border-yellow-200': toast.type === 'warning',
                         'bg-blue-50 border-blue-200': toast.type === 'info'
                     }">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="text-xl"
                                   :class="{
                                       'fas fa-check-circle text-green-400': toast.type === 'success',
                                       'fas fa-exclamation-circle text-red-400': toast.type === 'error',
                                       'fas fa-exclamation-triangle text-yellow-400': toast.type === 'warning',
                                       'fas fa-info-circle text-blue-400': toast.type === 'info'
                                   }"></i>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium"
                                   :class="{
                                       'text-green-900': toast.type === 'success',
                                       'text-red-900': toast.type === 'error',
                                       'text-yellow-900': toast.type === 'warning',
                                       'text-blue-900': toast.type === 'info'
                                   }"
                                   x-text="toast.message"></p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button @click="removeToast(toast.id)"
                                        class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <script>
            function toastManager() {
                return {
                    toasts: [],
                    nextId: 1,

                    showToast(data) {
                        const toast = {
                            id: this.nextId++,
                            message: data.message || 'Notification',
                            type: data.type || 'info',
                            visible: true
                        };

                        this.toasts.push(toast);

                        // Auto remove after 5 seconds
                        setTimeout(() => {
                            this.removeToast(toast.id);
                        }, 5000);
                    },

                    removeToast(id) {
                        const index = this.toasts.findIndex(toast => toast.id === id);
                        if (index > -1) {
                            this.toasts[index].visible = false;
                            setTimeout(() => {
                                this.toasts.splice(index, 1);
                            }, 300);
                        }
                    }
                }
            }
        </script>
        
        <script>
            document.addEventListener('livewire:init', function() {
                Livewire.on('show-delete-modal', () => {
                    // This event is handled by Alpine.js x-data
                });
                
                Livewire.on('hide-delete-modal', () => {
                    // This event is handled by Alpine.js x-data
                });
            });
        </script>
        
        @livewireScripts
        <script src="{{ asset('js/button-debug.js') }}"></script>
        @stack('scripts')
    </body>
</html>
