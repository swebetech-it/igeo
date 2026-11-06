<!-- Data Table Component -->
@props(['headers' => [], 'searchPlaceholder' => 'Search...', 'showSearch' => true, 'showFilters' => false])

<div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/50 overflow-hidden">
    <!-- Table Header with Search and Filters -->
    @if($showSearch || $showFilters)
    <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-b border-slate-200/50">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Search -->
            @if($showSearch)
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input type="text"
                       wire:model.live="search"
                       placeholder="{{ $searchPlaceholder }}"
                       class="block w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white/80 backdrop-blur-sm text-sm font-medium">
            </div>
            @endif

            <!-- Filters Slot -->
            @if($showFilters)
            <div class="flex items-center space-x-4">
                {{ $filters ?? '' }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-gradient-to-r from-slate-50 to-blue-50/20">
                <tr>
                    @foreach($headers as $header)
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                        @if(isset($header['sortable']) && $header['sortable'])
                            <button wire:click="sortBy('{{ $header['key'] }}')"
                                    class="flex items-center space-x-1 hover:text-blue-600 transition-colors duration-200">
                                <span>{{ $header['label'] }}</span>
                                <div class="flex flex-col">
                                    <i class="fas fa-chevron-up text-xs {{ isset($sortField) && $sortField === $header['key'] && $sortDirection === 'asc' ? 'text-blue-600' : 'text-slate-400' }}"></i>
                                    <i class="fas fa-chevron-down text-xs -mt-1 {{ isset($sortField) && $sortField === $header['key'] && $sortDirection === 'desc' ? 'text-blue-600' : 'text-slate-400' }}"></i>
                                </div>
                            </button>
                        @else
                            {{ $header['label'] }}
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white/80 backdrop-blur-sm divide-y divide-slate-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @isset($pagination)
    <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-t border-slate-200/50">
        {{ $pagination }}
    </div>
    @endisset

    <!-- Empty State -->
    @isset($empty)
    <div class="text-center py-12">
        {{ $empty }}
    </div>
    @endisset
</div>
