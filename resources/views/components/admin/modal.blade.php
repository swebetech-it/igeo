@props(['show' => false, 'title' => '', 'size' => 'md', 'icon' => ''])

<div x-data="{ open: @entangle($attributes->wire('model')) }" 
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity" 
             aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="admin-card inline-block align-bottom text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full {{ 
                $size === 'sm' ? 'sm:max-w-md' : 
                ($size === 'lg' ? 'sm:max-w-3xl' : 
                ($size === 'xl' ? 'sm:max-w-5xl' : 
                ($size === '2xl' ? 'sm:max-w-6xl' : 'sm:max-w-2xl'))) 
             }}"
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="modal-headline">
            
            <div class="admin-card-header flex items-center justify-between">
                <div class="flex items-center">
                    @if($icon)
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center mr-3 shadow">
                            <i class="{{ $icon }}"></i>
                        </div>
                    @endif
                    <h3 class="admin-card-title">{{ $title }}</h3>
                </div>
                <button @click="open = false" 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>