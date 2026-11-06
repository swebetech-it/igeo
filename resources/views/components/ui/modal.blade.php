@props([
    'show' => false,
    'title' => null,
    'size' => 'md', // sm, md, lg, xl, 2xl
    'closeText' => 'Close',
    'submitText' => null,
    'wireSubmit' => null,
    'icon' => null,
    'persistent' => false,
])

<div x-data="{ open: @js($show) }" x-show="open" x-transition.opacity.duration.200ms
     x-on:keydown.escape.window="if(!@js($persistent)){ open=false; $dispatch('modal-closed') }"
     class="fixed inset-0 z-50 flex items-start md:items-center justify-center p-4 overflow-y-auto"
     style="display: none;">
    <div x-show="open" x-transition.opacity.duration.300ms class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    @php
        $dialogWidth = [
            'sm' => 'max-w-sm',
            'md' => 'max-w-lg',
            'lg' => 'max-w-2xl',
            'xl' => 'max-w-4xl',
            '2xl' => 'max-w-6xl',
        ][$size] ?? 'max-w-lg';
    @endphp

    <div x-show="open" x-transition.scale.duration.250ms
         class="relative w-full {{ $dialogWidth }} mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-start space-x-4">
            @if($icon)
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="{{ $icon }} text-xl"></i>
                </div>
            @endif
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">{{ $title }}</h3>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $subtitle ?? '' }}
                </div>
            </div>
            <button type="button" @click="if(!@js($persistent)){ open=false; $dispatch('modal-closed') }" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
            {{ $slot }}
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/40 flex items-center justify-end space-x-3 rounded-b-2xl">
            <button type="button" @click="if(!@js($persistent)){ open=false; $dispatch('modal-closed') }" class="btn-secondary">
                {{ $closeText }}
            </button>
            @if($submitText)
                <button type="button" @if($wireSubmit) wire:click="{{ $wireSubmit }}" @endif class="btn-primary bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white">
                    <span wire:loading.remove wire:target="{{ $wireSubmit }}">{{ $submitText }}</span>
                    <span wire:loading wire:target="{{ $wireSubmit }}" class="inline-flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Processing...
                    </span>
                </button>
            @endif
        </div>
    </div>
</div>
