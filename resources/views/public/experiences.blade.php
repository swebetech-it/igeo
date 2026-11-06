@extends('layouts.web')

@section('title', 'Our Journey & Experiences')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Cultivating Growth</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            From locally sourced seeds to international pharmaceutical access, tracking our impact over time.
        </p>
    </div>
</section>

<!-- Timeline Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-5xl mx-auto">
        @if($experiences->count() > 0)
            <div class="relative border-l-2 border-gray-200 dark:border-gray-800 ml-3 md:ml-12 space-y-12">
                @foreach($experiences as $experience)
                    <div class="relative pl-8 md:pl-12 group">
                        <!-- Timeline Dot -->
                        <div class="absolute -left-[9px] md:-left-[9px] top-0 w-5 h-5 rounded-full border-4 border-white dark:border-background-dark bg-gray-300 dark:bg-gray-700 group-hover:bg-primary transition-colors duration-300 z-10"></div>
                        
                        <!-- Content Card -->
                        <div class="bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm group-hover:shadow-xl group-hover:border-primary/50 transition-all duration-300">
                            <span class="inline-block px-3 py-1 mb-4 rounded-lg bg-primary/10 text-primary font-bold text-sm">
                                {{ $experience->year }}
                            </span>
                            
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ $experience->title }}</h3>
                            
                            <p class="text-slate-600 dark:text-gray-400 leading-relaxed mb-4">
                                {{ $experience->description }}
                            </p>
                            
                            @if($experience->image_path)
                                <div class="mt-6 rounded-xl overflow-hidden shadow-md">
                                    <img src="{{ asset('storage/' . $experience->image_path) }}" 
                                         alt="{{ $experience->title }}" 
                                         class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
             <!-- No Results -->
             <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">history_edu</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Milestones Recorded</h3>
                <p class="text-slate-600 dark:text-gray-400">
                    We are currently compiling our history. Please check back later.
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
