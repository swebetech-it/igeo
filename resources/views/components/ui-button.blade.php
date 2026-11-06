<!-- Primary Button Component -->
@props(['type' => 'button', 'disabled' => false, 'loading' => false, 'variant' => 'primary', 'size' => 'md'])

@php
$variants = [
    'primary' => 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white shadow-lg hover:shadow-xl',
    'secondary' => 'bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white shadow-lg hover:shadow-xl',
    'success' => 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white shadow-lg hover:shadow-xl',
    'danger' => 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white shadow-lg hover:shadow-xl',
    'warning' => 'bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white shadow-lg hover:shadow-xl',
    'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white bg-white/80 backdrop-blur-sm',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-6 py-3 text-sm',
    'lg' => 'px-8 py-4 text-base',
];
@endphp

<button
    type="{{ $type }}"
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none ' . $variants[$variant] . ' ' . $sizes[$size]]) }}
>
    @if($loading)
        <div class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
    @endif

    {{ $slot }}
</button>
