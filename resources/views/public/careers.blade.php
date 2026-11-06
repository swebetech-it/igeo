@extends('layouts.web')

@section('title', 'Careers')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">
            Join Our <span class="text-primary">Mission</span>
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Help us transform agriculture and healthcare with sustainable innovation.
        </p>
    </div>
</section>

<!-- Job Listings -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark">
    <div class="max-w-7xl mx-auto">
        @if($careers->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($careers as $career)
                <div class="group bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-primary/10 text-primary">
                                    {{ $career->department ?? 'General' }}
                                </span>
                                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-400">
                                    {{ $career->type }}
                                </span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors">
                                <a href="{{ route('public.career.detail', $career->id) }}">
                                    {{ $career->title }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-6 text-sm text-slate-500 dark:text-gray-400">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">location_on</span>
                                    {{ $career->location }}
                                </span>
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">schedule</span>
                                    Posted {{ $career->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('public.career.detail', $career->id) }}" class="flex items-center justify-center h-12 px-6 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold hover:bg-primary hover:text-background-dark dark:hover:bg-primary dark:hover:text-background-dark transition-all">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-slate-100 dark:bg-slate-800 mb-6">
                    <span class="material-symbols-outlined text-4xl text-slate-400">work_off</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">No Openings Currently</h2>
                <p class="text-slate-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    We don't have any open positions right now, but we're always looking for talent. Check back soon!
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-bold text-primary hover:text-primary/80 transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span> Back to Home
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
