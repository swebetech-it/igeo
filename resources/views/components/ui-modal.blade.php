<!-- Modal Component -->
@props(['show' => false, 'maxWidth' => 'lg', 'title' => '', 'closeable' => true])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    '7xl' => 'max-w-7xl',
][$maxWidth];
@endphp

<div x-show="{{ $show }}"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
             @click="{{ $closeable ? 'show = false' : '' }}"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white/95 backdrop-blur-xl rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full {{ $maxWidthClass }} border border-slate-200/50"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <!-- Modal Header -->
            @if($title || $closeable)
            <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-b border-slate-200/50">
                <div class="flex items-center justify-between">
                    @if($title)
                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $title }}
                    </h3>
                    @endif

                    @if($closeable)
                    <button @click="show = false"
                            class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg p-2 transition-colors duration-200">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <!-- Modal Body -->
            <div class="px-6 py-6">
                {{ $slot }}
            </div>

            <!-- Modal Footer (if provided) -->
            @isset($footer)
            <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-t border-slate-200/50">
                {{ $footer }}
            </div>
            @endisset
        </div>
    </div>
</div>
