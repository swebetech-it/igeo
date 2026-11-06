<!-- Stats Card Component -->
@props(['title', 'value', 'icon', 'color' => 'primary', 'trend' => null, 'trendDirection' => null])

@php
$colorClasses = [
    'primary' => 'from-primary-500 to-primary-600 text-primary-600 bg-primary-100',
    'secondary' => 'from-secondary-500 to-secondary-600 text-secondary-600 bg-secondary-100',
    'accent' => 'from-accent-500 to-accent-600 text-accent-600 bg-accent-100',
    'green' => 'from-green-500 to-green-600 text-green-600 bg-green-100',
    'red' => 'from-red-500 to-red-600 text-red-600 bg-red-100',
    'yellow' => 'from-yellow-500 to-yellow-600 text-yellow-600 bg-yellow-100',
    'gray' => 'from-gray-500 to-gray-600 text-gray-600 bg-gray-100',
];

$gradient = $colorClasses[$color] ?? $colorClasses['primary'];
$parts = explode(' ', $gradient);
$gradientClass = $parts[0] . ' ' . $parts[1];
$textColor = $parts[2];
$bgColor = $parts[3];
@endphp

<div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-slate-200/50 p-6 group">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">{{ $title }}</p>
            <p class="text-3xl font-black text-slate-900 mb-1">{{ $value }}</p>

            @if($trend)
            <div class="flex items-center text-sm">
                @if($trendDirection === 'up')
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span class="font-semibold">{{ $trend }}</span>
                    </div>
                @elseif($trendDirection === 'down')
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-arrow-down mr-1"></i>
                        <span class="font-semibold">{{ $trend }}</span>
                    </div>
                @else
                    <div class="flex items-center text-slate-600">
                        <i class="fas fa-minus mr-1"></i>
                        <span class="font-semibold">{{ $trend }}</span>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <div class="ml-4">
            <div class="w-16 h-16 bg-gradient-to-br {{ $gradientClass }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                <i class="{{ $icon }} text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>