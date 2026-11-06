@props(['title', 'subtitle', 'icon', 'color' => 'blue'])

<div class="admin-card mb-8">
    <div class="admin-card-header flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center mb-2">
                @if($icon)
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-{{ $color }}-600 to-{{ $color }}-700 text-white flex items-center justify-center mr-3 shadow">
                        <i class="{{ $icon }} text-xl"></i>
                    </div>
                @endif
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
            </div>
            @if($subtitle)
                <p class="text-gray-600 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            {{ $actions ?? '' }}
        </div>
    </div>
</div>