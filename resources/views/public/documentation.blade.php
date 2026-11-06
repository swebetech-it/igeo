@extends('layouts.web')

@section('title', 'Documentation & Resources')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Technical & Compliance</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            API docs for soil sensors, cold-chain tracking, and GMP compliance manuals for partners.
        </p>
    </div>
</section>

<!-- Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Search -->
        <div class="mb-12 bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search documentation..."
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
                
                <select name="category"
                        class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary min-w-[200px]">
                    <option value="" class="dark:bg-background-dark">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }} class="dark:bg-background-dark">
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="px-8 py-3 bg-primary text-background-dark font-bold rounded-xl hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span> Search
                </button>
            </form>
        </div>

        @if($documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($documents as $document)
                    <div class="group bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 group-hover:bg-primary group-hover:text-background-dark transition-all">
                            <span class="material-symbols-outlined text-3xl">description</span>
                        </div>
                        
                        @if($document->category)
                            <div class="mb-3">
                                <span class="inline-block px-2 py-1 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider rounded">
                                    {{ $document->category }}
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                            {{ $document->title }}
                        </h3>
                        
                        <p class="text-slate-600 dark:text-gray-400 text-sm mb-6 line-clamp-3">
                            {{ $document->description }}
                        </p>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-xs text-slate-400 font-mono">v{{ $document->version ?? '1.0' }}</span>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="flex items-center gap-2 text-sm font-bold text-primary hover:underline">
                                <span class="material-symbols-outlined text-sm">download</span> Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

             <!-- Pagination -->
            @if($documents->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $documents->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
             <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">folder_off</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Documents Found</h3>
                <p class="text-slate-600 dark:text-gray-400">
                    Try adjusting your search criteria or check back later.
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('public.documentation') }}" class="inline-block mt-4 text-primary font-bold hover:underline">
                        Clear filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
