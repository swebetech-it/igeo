@extends('layouts.web')

@section('title', $career->title)

@section('content')
<section class="relative bg-background-dark py-20 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto relative z-10">
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <a href="{{ route('public.careers') }}" class="flex items-center gap-1 text-sm font-bold text-gray-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span> Back to Careers
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4">
                    {{ $career->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-gray-400">
                    <span class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-base">business</span> {{ $career->department ?? 'General' }}
                    </span>
                    <span class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-base">location_on</span> {{ $career->location }}
                    </span>
                    <span class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-base">access_time</span> {{ $career->type }}
                    </span>
                    @if($career->deadline)
                    <span class="flex items-center gap-2 bg-red-500/10 text-red-400 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-base">event_busy</span> Deadline: {{ $career->deadline->format('M d, Y') }}
                    </span>
                    @endif
                </div>
            </div>
            
            <a href="mailto:careers@igeo.co.tz?subject=Application for {{ rawurlencode($career->title) }}" class="flex items-center gap-2 h-14 px-8 bg-primary text-background-dark font-bold rounded-xl hover:scale-105 transition-transform shadow-lg shadow-primary/25 whitespace-nowrap">
                Apply Now <span class="material-symbols-outlined">send</span>
            </a>
        </div>
    </div>
</section>

<section class="py-20 px-6 lg:px-20 bg-background-light dark:bg-background-dark">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-background-dark/50 p-8 md:p-12 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
            
            <h2 class="text-xl font-bold uppercase tracking-widest text-slate-400 mb-8 border-b border-gray-200 dark:border-gray-800 pb-4">Job Description</h2>
            
            <div class="prose prose-lg dark:prose-invert max-w-none text-slate-600 dark:text-gray-400 leading-relaxed">
                {!! nl2br(e($career->description)) !!}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">How to Apply</h3>
                <p class="text-slate-600 dark:text-gray-400 mb-6">
                    Interested candidates should send their CV and cover letter to <a href="mailto:careers@igeo.co.tz" class="text-primary font-bold hover:underline">careers@igeo.co.tz</a>. Please mention <strong>"{{ $career->title }}"</strong> in the subject line.
                </p>
                <a href="mailto:careers@igeo.co.tz?subject=Application for {{ rawurlencode($career->title) }}" class="inline-flex items-center gap-2 font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                    Send Application <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
