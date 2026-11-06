@props([
    'title',
    'subtitle' => '',
    'icon' => 'fas fa-cog',
    'color' => 'blue',
    'actions' => null
])

@php
$colorMap = [
    'blue' => 'from-blue-600 to-indigo-600',
    'green' => 'from-emerald-600 to-green-600', 
    'red' => 'from-red-600 to-rose-600',
    'purple' => 'from-purple-600 to-violet-600',
    'yellow' => 'from-amber-600 to-yellow-600',
    'orange' => 'from-orange-600 to-red-600',
    'teal' => 'from-teal-600 to-cyan-600',
];
$gradient = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
            <span class="w-12 h-12 rounded-lg bg-gradient-to-br {{ $gradient }} text-white flex items-center justify-center mr-3 shadow">
                <i class="{{ $icon }} text-xl"></i>
            </span>
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>