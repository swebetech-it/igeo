<!-- Common Form Input Component -->
@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'required' => false, 'value' => '', 'error' => null])

<div class="form-group">
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-800 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500 ml-1">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            wire:model="{{ $name }}"
            placeholder="{{ $placeholder }}"
            rows="4"
            {{ $required ? 'required' : '' }}
            class="form-input w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white/80 backdrop-blur-sm {{ $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
        >{{ $value }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            wire:model="{{ $name }}"
            {{ $required ? 'required' : '' }}
            class="form-input w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white/80 backdrop-blur-sm {{ $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
        >
            {{ $slot }}
        </select>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            wire:model="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            class="form-input w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white/80 backdrop-blur-sm {{ $error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
        />
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error }}
        </p>
    @endif
</div>