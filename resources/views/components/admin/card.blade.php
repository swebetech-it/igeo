@props(['title' => '', 'icon' => '', 'color' => 'blue', 'actions' => '', 'footer' => ''])

<div class="admin-card">
    @if($title || $actions)
        <div class="admin-card-header flex items-center justify-between">
            <div class="flex items-center">
                @if($icon)
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-{{ $color }}-600 to-{{ $color }}-700 text-white flex items-center justify-center mr-3 shadow">
                        <i class="{{ $icon }}"></i>
                    </div>
                @endif
                @if($title)
                    <h3 class="admin-card-title">{{ $title }}</h3>
                @endif
            </div>
            @if($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            {{ $footer }}
        </div>
    @endif
</div>